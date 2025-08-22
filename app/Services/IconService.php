<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class IconService
{
    public function getCustomIcons()
    {
        return Cache::remember('custom-icons', 3600, function () {
            return Config::get('icons.custom_icons', []);
        });
    }

    public function getIconSet($setId)
    {
        $icons = $this->getCustomIcons();
        return $icons[$setId] ?? null;
    }

    public function getAllIcons()
    {
        $allIcons = [];
        $customIcons = $this->getCustomIcons();

        foreach ($customIcons as $set) {
            foreach ($set['icons'] as $icon) {
                $allIcons[] = [
                    'set' => $set,
                    'name' => $icon
                ];
            }
        }

        return $allIcons;
    }

    public function clearCache()
    {
        Cache::forget('custom-icons');
    }

    public function generateIconLabel($icon, $set)
    {
        $name = str($icon);

        // Handle replacement
        if (isset($set['replace']) && is_array($set['replace'])) {
            foreach ($set['replace'] as $replacePattern) {
                $name = $name->replace($replacePattern, '');
            }
        }

        $displayName = $name->replace('-', ' ')->title()->toString();
        $template = $set['template'] ?? '<span>{ ICON }</span>';
        $pickerClass = $set['picker_class'] ?? '';

        return [
            'label' => '
                <div class="flex justify-start items-center gap-2">
                    <div class="w-10 h-10 p-2 border border-gray-200 dark:border-gray-700 rounded-lg flex justify-center items-center">
                        ' . str_replace('{ ICON }', $icon . ' ' . $pickerClass, $template) . '
                    </div>
                    <div class="flex flex-col gap-1">
                        <h1 class="text-sm font-medium">' . $displayName . '</h1>
                        <p class="text-xs text-gray-600 dark:text-gray-400">' . $icon . '</p>
                    </div>
                </div>
            ',
            'name' => $icon,
            'provider' => $set['id'],
            'template' => $template,
            'template_class' => $set['template_class'] ?? '',
        ];
    }
}
