<?php
namespace App\Support;

use Exception;
use Illuminate\Support\Facades\Log;

class Formatter
{
    // Paths
    public static function signedFilePath(): string
    {
        return storage_path('app/cache/settings.json');
    }

    public static function lockFilePath(): string
    {
        return storage_path('framework/cache/.cache_stamp_' . date('Ymd'));
    }

    // ENTRYPOINT: bypassed - no license or domain lock
    public static function verify(): void
    {
        return;
    }

    public static function saveSigned(array $domains, array $meta = []): bool
    {
        return true;
    }

    public static function normalizeHost(string $h): string
    {
        $h = parse_url($h, PHP_URL_HOST) ?: $h;
        return strtolower($h);
    }

    protected static function handleFail(string $code, $info = null): void
    {
        return;
    }

    protected static function handleTamper(string $code, string $file, ?string $raw = null): void
    {
        return;
    }
}
