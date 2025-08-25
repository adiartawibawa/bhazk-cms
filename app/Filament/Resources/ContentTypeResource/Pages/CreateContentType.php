<?php

namespace App\Filament\Resources\ContentTypeResource\Pages;

use App\Filament\Resources\ContentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateContentType extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ContentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
