<?php

namespace App\Actions;

use App\Enums\NewsletterSource;
use App\Enums\NewsletterStatus;
use App\Events\FrontendActivityRecorded;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;

class CreateNewsletterSubscriberAction
{
    public function __construct(
        private ResolveSubscriberLocationAction $resolveSubscriberLocation,
    ) {}

    /**
     * @param  array{email: string}  $data
     * @return array{subscriber: NewsletterSubscriber, created: bool}
     */
    public function handle(
        array $data,
        NewsletterSource $source = NewsletterSource::Public,
        ?string $ip = null,
    ): array {
        $email = Str::lower(trim($data['email']));
        $location = $source === NewsletterSource::Public
            ? $this->resolveSubscriberLocation->handle($ip)
            : [
                'country' => null,
                'country_code' => null,
                'city' => null,
                'ip_address' => null,
            ];

        $existing = NewsletterSubscriber::query()->where('email', $email)->first();

        if ($existing !== null) {
            $wasInactive = ! $existing->status->isActive();

            $updates = [];

            if ($wasInactive) {
                $updates['status'] = NewsletterStatus::Active;
                $updates['unsubscribed_at'] = null;
                $updates['source'] = $source;
            }

            if ($source === NewsletterSource::Public && ($location['country'] !== null || $location['city'] !== null)) {
                $updates['country'] = $location['country'];
                $updates['country_code'] = $location['country_code'];
                $updates['city'] = $location['city'];
                $updates['ip_address'] = $location['ip_address'];
            }

            if ($updates !== []) {
                $existing->update($updates);

                if ($source === NewsletterSource::Public && $wasInactive) {
                    $this->notify($existing->fresh() ?? $existing, reactivated: true);
                }
            }

            return [
                'subscriber' => $existing->fresh() ?? $existing,
                'created' => false,
            ];
        }

        $subscriber = NewsletterSubscriber::query()->create([
            'reference' => 'nws-'.Str::lower(bin2hex(random_bytes(6))),
            'status' => NewsletterStatus::Active,
            'source' => $source,
            'email' => $email,
            'country' => $location['country'],
            'country_code' => $location['country_code'],
            'city' => $location['city'],
            'ip_address' => $location['ip_address'],
            'unsubscribed_at' => null,
        ]);

        if ($source === NewsletterSource::Public) {
            $this->notify($subscriber, reactivated: false);
        }

        return [
            'subscriber' => $subscriber,
            'created' => true,
        ];
    }

    private function notify(NewsletterSubscriber $subscriber, bool $reactivated): void
    {
        $place = collect([$subscriber->city, $subscriber->country])
            ->filter()
            ->implode(', ');

        FrontendActivityRecorded::dispatch(
            'newsletter',
            $reactivated ? 'Newsletter subscriber returned' : 'Newsletter subscriber joined',
            $place !== '' ? $subscriber->email.' · '.$place : $subscriber->email,
            route('portal.subscribers.show', $subscriber, false),
            $subscriber->reference,
        );
    }
}
