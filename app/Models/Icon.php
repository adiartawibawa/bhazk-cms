<?php

namespace App\Models;

use BladeUI\Icons\Factory as IconFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;
use Illuminate\Support\Str;
use App\Services\IconService;

class Icon extends Model
{
    use Sushi;

    protected $schema = [
        'label' => 'string',
        'name' => 'string',
        'provider' => 'string',
        'template' => 'string',
        'template_class' => 'string'
    ];

    public function getRows()
    {
        $iconsCollections = [];
        $iconService = app(IconService::class);

        // Load default icons dari blade-ui-kit
        $iconsFactory = App::make(IconFactory::class);
        $sets = collect($iconsFactory->all());

        foreach ($sets as $key => $iconGroup) {
            $getPathes = $iconGroup['paths'];
            foreach ($getPathes as $path) {
                if (!File::exists($path)) {
                    continue;
                }

                $icons = File::files($path);
                foreach ($icons as $icon) {
                    $getSVGContent = File::get($icon->getRealPath());
                    $fileName = $icon->getFileName();
                    $baseName = str($fileName)->replace('.svg', '');

                    $iconsArray['label'] = '
                        <div class="flex justify-start items-center gap-2">
                            <div class="w-10 h-10 p-2 border border-gray-200 dark:border-gray-700 rounded-lg flex justify-center items-center">
                                ' . $getSVGContent . '
                            </div>
                            <div class="flex flex-col gap-1">
                                <h1 class="text-sm font-medium">' . $baseName
                        ->replaceFirst('c-', '')
                        ->replaceFirst('o-', '')
                        ->replaceFirst('s-', '')
                        ->replace('-', ' ')
                        ->title() . '</h1>
                                <p class="text-xs text-gray-600 dark:text-gray-400">' . $iconGroup['prefix'] . '-' . $baseName . '</p>
                            </div>
                        </div>
                    ';

                    $iconsArray['name'] = $iconGroup['prefix'] . '-' . $baseName;
                    $iconsArray['provider'] = $iconGroup['prefix'];
                    $iconsArray['template'] = null;
                    $iconsArray['template_class'] = null;
                    $iconsCollections[] = $iconsArray;
                }
            }
        }

        // Load custom icons dari service
        try {
            $customIcons = $iconService->getAllIcons();

            foreach ($customIcons as $iconData) {
                $iconInfo = $iconService->generateIconLabel($iconData['name'], $iconData['set']);
                $iconsCollections[] = $iconInfo;
            }
        } catch (\Exception $e) {
            logger()->error('Error loading custom icons: ' . $e->getMessage());
        }

        return $iconsCollections;
    }

    protected function sushiShouldCache()
    {
        return true;
    }
}
