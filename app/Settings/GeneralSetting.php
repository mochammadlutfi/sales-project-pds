<?php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSetting extends Settings
{
    public string $app_name;
    public string $app_version;
    public string $favicon;
    public string $logo_light;
    public string $logo_dark;
    public string $logo_light_sm;
    public string $logo_dark_sm;
    public string $locale;
    public string $company_name;
    public string $company_email;
    public string $company_phone;
    public string $company_address;
    public int $branch;

    public static function group(): string
    {
        return 'general';
    }
}