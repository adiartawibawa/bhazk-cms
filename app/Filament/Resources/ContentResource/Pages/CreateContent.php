<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use App\Models\ContentRevision;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Illuminate\Support\Facades\Auth;

class CreateContent extends CreateRecord
{
    use Translatable;

    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function afterCreate(): void
    {
        // Buat revisi awal
        ContentRevision::create([
            'content_id' => $this->record->id,
            'author_id' => Auth::id(),
            'version' => 1,
            'title' => $this->record->title,
            'body' => $this->record->body,
            'metadata' => $this->record->metadata,
            'change_type' => 'created',
            'change_description' => 'Initial version created',
            'is_autosave' => false,
        ]);

        // Update current version
        $this->record->update(['current_version' => 1]);
    }
}
