<?php

namespace App\Filament\Resources\ContentTypeResource\Pages;

use App\Filament\Resources\ContentTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentType extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ContentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
