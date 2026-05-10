<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'My Ecommerce');
        $this->migrator->add('general.site_email', 'admin@example.com');
        $this->migrator->add('general.site_phone', '+201000000000');
        $this->migrator->add('general.site_address', 'Egypt');
        $this->migrator->add('general.logo', null);
        // social media
        $this->migrator->add('general.facebook_url', 'https://www.facebook.com/');
        $this->migrator->add('general.twitter_url', 'https://www.twitter.com/');
        $this->migrator->add('general.instagram_url', 'https://www.instagram.com/');
    }
};
