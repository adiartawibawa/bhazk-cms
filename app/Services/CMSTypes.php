<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use App\Services\Contracts\CmsType;

class CMSTypes
{
    public static array $cmsTypes = [];

    public static function register(CmsType|array $cmsType)
    {
        if (is_array($cmsType)) {
            foreach ($cmsType as $type) {
                self::register($type);
            }
            return;
        }

        self::$cmsTypes[] = $cmsType;
    }

    public static function autoRegisterFromConfig()
    {
        $configTypes = Config::get('cms-types.types', []);

        foreach ($configTypes as $key => $config) {
            $cmsType = CmsType::make($key);
            self::register($cmsType);
        }
    }

    public static function getOptions()
    {
        // Auto-register dari config jika belum ada
        if (empty(self::$cmsTypes)) {
            self::autoRegisterFromConfig();
        }

        return collect(self::$cmsTypes);
    }

    public static function getType(string $key): ?CmsType
    {
        return self::getOptions()->firstWhere('key', $key);
    }

    public static function clear()
    {
        self::$cmsTypes = [];
    }
}
