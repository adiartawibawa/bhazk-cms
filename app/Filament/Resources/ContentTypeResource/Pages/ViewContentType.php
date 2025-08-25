<?php

namespace App\Filament\Resources\ContentTypeResource\Pages;

use App\Filament\Resources\ContentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContentType extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = ContentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
        ];
    }
}
