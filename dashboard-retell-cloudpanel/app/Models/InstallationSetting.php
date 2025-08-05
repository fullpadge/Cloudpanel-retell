<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallationSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get($key, $default = null)
    {
        try {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function isInstalled()
    {
        try {
            return self::get('installation_completed', false) === 'true';
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function markAsInstalled()
    {
        self::set('installation_completed', 'true');
        self::set('installation_date', now()->toDateTimeString());
    }
}