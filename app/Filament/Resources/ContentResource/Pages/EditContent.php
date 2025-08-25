<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use App\Models\ContentRevision;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;
use Illuminate\Support\Facades\Auth;

class EditContent extends EditRecord
{
    use Translatable;

    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\Action::make('revisions')
                ->label('Revisions')
                ->icon('heroicon-o-clock')
                ->url(fn() => ContentResource::getUrl('revisions', ['record' => $this->record])),
        ];
    }

    protected function beforeSave(): void
    {
        // Simpan data sebelum diubah untuk comparison
        $this->oldData = $this->record->getOriginal();
    }

    protected function afterSave(): void
    {
        $changes = $this->getContentChanges($this->oldData, $this->record->getAttributes());

        if (!empty($changes)) {
            // Buat revisi baru
            $newVersion = $this->record->current_version + 1;

            ContentRevision::create([
                'content_id' => $this->record->id,
                'author_id' => Auth::id(),
                'version' => $newVersion,
                'title' => $this->record->title,
                'body' => $this->record->body,
                'metadata' => $this->record->metadata,
                'change_type' => 'update',
                'change_description' => $this->getChangeDescription($changes),
                'diff_summary' => $changes,
                'is_autosave' => false,
            ]);

            // Update current version
            $this->record->update(['current_version' => $newVersion]);
        }

        // Update editor_id
        $this->record->update(['editor_id' => Auth::id()]);
    }

    private function getContentChanges(array $oldData, array $newData): array
    {
        $changes = [];
        $significantFields = ['title', 'body', 'excerpt', 'status', 'metadata'];

        foreach ($significantFields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            if ($oldValue != $newValue) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    private function getChangeDescription(array $changes): string
    {
        $descriptions = [];

        if (isset($changes['title'])) {
            $descriptions[] = 'Title updated';
        }

        if (isset($changes['body'])) {
            $descriptions[] = 'Content body updated';
        }

        if (isset($changes['excerpt'])) {
            $descriptions[] = 'Excerpt updated';
        }

        if (isset($changes['status'])) {
            $descriptions[] = 'Status changed from ' . $changes['status']['old'] . ' to ' . $changes['status']['new'];
        }

        if (isset($changes['metadata'])) {
            $descriptions[] = 'Metadata updated';
        }

        return implode(', ', $descriptions) ?: 'Content updated';
    }
}
