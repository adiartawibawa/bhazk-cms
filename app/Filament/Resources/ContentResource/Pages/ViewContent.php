<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ViewRecord\Concerns\Translatable;

class ViewContent extends ViewRecord
{
    use Translatable;

    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
            Actions\Action::make('revisions')
                ->label(__('resource.content.view.actions.revisions'))
                ->icon('heroicon-o-clock')
                ->url(fn() => ContentResource::getUrl('revisions', ['record' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }
}
