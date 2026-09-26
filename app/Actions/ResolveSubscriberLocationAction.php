<?php

namespace App\Actions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ResolveSubscriberLocationAction
{
    /**
     * Resolve an approximate country and city from a public IP address.
     *
     * @return array{country: ?string, country_code: ?string, city: ?string, ip_address: ?string}
     */
    public function handle(?string $ip): array
    {
        $empty = [
            'country' => null,
            'country_code' => null,
            'city' => null,
            'ip_address' => null,
        ];

        if (! is_string($ip) || $ip === '' || ! $this->isPublicIp($ip)) {
            return $empty;
        }

        $empty['ip_address'] = $ip;

        try {
            /** @var array{country: ?string, country_code: ?string, city: ?string, ip_address: ?string} $location */
            $location = Cache::remember(
                'newsletter-geo:'.sha1($ip),
                now()->addDay(),
                fn (): array => $this->lookup($ip),
            );

            return [
                'country' => $location['country'] ?? null,
                'country_code' => $location['country_code'] ?? null,
                'city' => $location['city'] ?? null,
                'ip_address' => $ip,
            ];
        } catch (Throwable $exception) {
            Log::warning('Newsletter geo lookup failed.', [
                'ip' => $ip,
                'message' => $exception->getMessage(),
            ]);

            return $empty;
        }
    }

    /**
     * @return array{country: ?string, country_code: ?string, city: ?string, ip_address: ?string}
     */
    private function lookup(string $ip): array
    {
        $response = Http::connectTimeout(2)
            ->timeout(3)
            ->acceptJson()
            ->get('https://ipwho.is/'.$ip);

        if (! $response->successful()) {
            return [
                'country' => null,
                'country_code' => null,
                'city' => null,
                'ip_address' => $ip,
            ];
        }

        /** @var array<string, mixed> $payload */
        $payload = $response->json() ?? [];

        if (($payload['success'] ?? false) !== true) {
            return [
                'country' => null,
                'country_code' => null,
                'city' => null,
                'ip_address' => $ip,
            ];
        }

        $country = is_string($payload['country'] ?? null) ? trim($payload['country']) : null;
        $countryCode = is_string($payload['country_code'] ?? null) ? strtoupper(trim($payload['country_code'])) : null;
        $city = is_string($payload['city'] ?? null) ? trim($payload['city']) : null;

        return [
            'country' => $country !== '' ? mb_substr($country, 0, 80) : null,
            'country_code' => ($countryCode !== null && preg_match('/^[A-Z]{2}$/', $countryCode) === 1) ? $countryCode : null,
            'city' => $city !== '' ? mb_substr($city, 0, 80) : null,
            'ip_address' => $ip,
        ];
    }

    private function isPublicIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
        ) !== false;
    }
}
