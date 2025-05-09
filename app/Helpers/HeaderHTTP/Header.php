<?php

namespace App\Helpers\HeaderHTTP;

use App\Enums\Types\DeviceType;
use Illuminate\Http\Request;

class Header
{
    protected $headers;

    public function __construct(Request $request)
    {
        $this->headers = collect($request->headers->all());
    }

    /**
     * Detects the device type based on the User-Agent.
     */
    public function getDeviceType(): string
    {
        $userAgent = strtolower($this->headers->get('user-agent', [''])[0]);

        if ($this->isTablet($userAgent)) {
            return DeviceType::Tablet->get();
        } elseif ($this->isMobile($userAgent)) {
            return DeviceType::Phone->get();
        } elseif ($this->isPC($userAgent)) {
            return DeviceType::PC_Laptop->get();
        } else {
            return DeviceType::Unknown->get();
        }
    }

    /**
     * Retrieves the preferred language from the "Accept-Language" header.
     */
    public function getPreferredLanguage(): string
    {
        $acceptLanguage = $this->headers->get('accept-language', ['en'])[0];
        return explode(',', $acceptLanguage)[0] ?? 'en';
    }

    /**
     * Gets the client IP address.
     */
    public function getClientIP(): string
    {
        return $this->headers->get('x-forwarded-for', [request()->ip()])[0];
    }

    /**
     * Retrieves the browser name from the User-Agent.
     */
    public function getBrowser(): string
    {
        $userAgent = strtolower($this->headers->get('user-agent', [''])[0]);

        return match (true) {
            str_contains($userAgent, 'chrome') => 'Chrome',
            str_contains($userAgent, 'firefox') => 'Firefox',
            str_contains($userAgent, 'safari') => 'Safari',
            str_contains($userAgent, 'edge') => 'Edge',
            str_contains($userAgent, 'opera') || str_contains($userAgent, 'opr') => 'Opera',
            str_contains($userAgent, 'msie') || str_contains($userAgent, 'trident') => 'Internet Explorer',
            default => 'Unknown',
        };
    }

    /**
     * Retrieves the operating system from the User-Agent.
     */
    public function getOS(): string
    {
        $userAgent = strtolower($this->headers->get('user-agent', [''])[0]);

        return match (true) {
            str_contains($userAgent, 'windows') => 'Windows',
            str_contains($userAgent, 'macintosh') || str_contains($userAgent, 'mac os x') => 'MacOS',
            str_contains($userAgent, 'linux') => 'Linux',
            str_contains($userAgent, 'android') => 'Android',
            str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad') => 'iOS',
            default => 'Unknown',
        };
    }

    /**
     * Checks if the device is a mobile phone.
     */
    private function isMobile(string $userAgent): bool
    {
        return (bool) preg_match('/iphone|ipod|android.*mobile|windows phone|blackberry/i', $userAgent);
    }

    /**
     * Checks if the device is a tablet.
     */
    private function isTablet(string $userAgent): bool
    {
        return (bool) preg_match('/ipad|android(?!.*mobile)/i', $userAgent);
    }

    /**
     * Checks if the device is a PC or Laptop.
     */
    private function isPC(string $userAgent): bool
    {
        return (bool) (preg_match('/windows|macintosh|linux/i', $userAgent) && !$this->isMobile($userAgent) && !$this->isTablet($userAgent));
    }

    /**
     * Builds a session-like data structure from headers.
     */
    public function buildSession(): array
    {
        $userAgent = strtolower($this->headers->get('user-agent', ['Unknown'])[0]);

        return [
            'token' => bin2hex(random_bytes(32)),
            'user_id' => null,
            'ip_address' => $this->getClientIP(),
            'device_type' => $this->getDeviceType(),
            'user_agent' => $userAgent,
            'expires_at' => now()->addSeconds(config('auth.bearer_expired_at', 3600))->toDateTimeString(),
            'forced_expires_at' => now()->tomorrow()->setHour(1)->setMinute(0)->setSecond(0)->toDateTimeString(),
            'is_active' => true,
            'last_activity' => now()->toDateTimeString(),
            'location' => 'Unknown',
            'is_expired' => false,
            'browser' => $this->getBrowser(),
            'os' => $this->getOS(),
            'is_mobile' => $this->isMobile($userAgent),
            'failed_attempts' => 0,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
    }
}
