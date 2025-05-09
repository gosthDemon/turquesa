<?php

namespace App\Helpers\Tools;

use Illuminate\Support\Facades\Config;

class Tools
{
    /**
     * Generates a slug by keeping only letters, numbers, and hyphens, 
     * converting to lowercase, replacing "ñ" with "n", and replacing spaces with hyphens.
     *
     * @param string $text Input text.
     * @return string Slug-formatted string.
     */
    public static function BuildSlug(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = str_replace('ñ', 'n', $text);
        $text = preg_replace('/[^a-z0-9 -]/', '', $text);
        $text = str_replace(' ', '-', trim($text));
        return preg_replace('/-+/', '-', $text);
    }

    /**
     * Checks if a log channel is configured and available in the config.
     *
     * @param string $channel The name of the channel to check.
     * @return bool True if the channel is available, false otherwise.
     */
    public static function isChannelAvailable(string $channel): bool
    {
        // Check if the channel exists in the logging configuration
        return array_key_exists($channel, Config::get('logging.channels'));
    }
}