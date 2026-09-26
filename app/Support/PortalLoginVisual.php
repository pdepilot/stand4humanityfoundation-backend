<?php

namespace App\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class PortalLoginVisual
{
    public const COOKIE = 'portal_login_visual';

    /**
     * @return array{index: int, wash: string, soft: string, accent: string, image: string}
     */
    public function current(Request $request): array
    {
        $washes = $this->washes();
        $index = $this->indexFrom($request, count($washes));

        return [
            ...$washes[$index],
            'index' => $index,
        ];
    }

    public function nextCookie(Request $request): Cookie
    {
        $count = count($this->washes());
        $next = ($this->indexFrom($request, $count) + 1) % $count;

        return $this->cookie($next);
    }

    public function cookie(int $index): Cookie
    {
        return cookie(self::COOKIE, (string) $index, 60 * 24 * 365);
    }

    /**
     * @return list<array{wash: string, soft: string, accent: string, image: string}>
     */
    private function washes(): array
    {
        /** @var list<array{wash: string, soft: string, accent: string, image: string}> $washes */
        $washes = config('portal.login_visuals');

        return $washes;
    }

    private function indexFrom(Request $request, int $count): int
    {
        if ($count < 1 || ! $request->hasCookie(self::COOKIE)) {
            return 0;
        }

        $index = (int) $request->cookie(self::COOKIE);

        if ($index < 0) {
            return 0;
        }

        return $index % $count;
    }
}
