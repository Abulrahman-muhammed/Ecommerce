<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public string $site_email;

    public string $site_phone;

    public string $site_address;

    public ?string $logo;

    public string $facebook_url;
    public string $twitter_url;
    public string $instagram_url;
    
    public static function group(): string
    {
        return 'general';
    }
}