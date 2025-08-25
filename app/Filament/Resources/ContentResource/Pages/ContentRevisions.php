<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use App\Models\Content;
use App\Models\ContentRevision;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class ContentRevisions extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = ContentResource::class;

    protected static string $view = 'filament.resources.content-resource.pages.content-revisions';

    public Content $record;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to Content')
                ->url(fn() => ContentResource::getUrl('edit', ['record' => $this->record])),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "Revisions for: {$this->record->title}";
    }

    public function getBreadcrumb(): string
    {
        return 'Revisions';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ContentRevision::where('content_id', $this->record->id))
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->label('Version')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => 'v' . $state),

                Tables\Columns\TextColumn::make('author.username')
                    ->label('Author')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('change_type')
                    ->label('Change Type')
                    ->badge()
                    ->colors([
                        'primary' => 'created',
                        'success' => 'update',
                        'warning' => 'status_change',
                        'info' => 'rollback',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('change_description')
                    ->label('Description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_autosave')
                    ->label('Autosave')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn(ContentRevision $record) => "Revision v{$record->version}")
                    ->modalContent(fn(ContentRevision $record) => view('filament.resources.content-resource.pages.content-revision-view', [
                        'revision' => $record,
                    ])),

                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Revision')
                    ->modalDescription('Are you sure you want to restore this revision? The current content will be replaced with this version.')
                    ->action(function (ContentRevision $record) {
                        try {
                            // Dapatkan content yang akan di-update (buat baru)
                            $content = Content::findOrFail($record->content_id);

                            // Simpan data lama untuk diff comparison
                            $oldData = $content->getAttributes();

                            // Update content dengan data dari revision
                            $content->update([
                                'title' => $record->title,
                                'body' => $record->body,
                                'excerpt' => $record->excerpt,
                                'metadata' => $record->metadata,
                                'editor_id' => Auth::id(),
                            ]);

                            // Buat revision baru untuk mencatat rollback
                            $newVersion = $content->current_version + 1;

                            ContentRevision::create([
                                'content_id' => $content->id,
                                'author_id' => Auth::id(),
                                'version' => $newVersion,
                                'title' => $content->title,
                                'body' => $content->body,
                                'excerpt' => $content->excerpt,
                                'metadata' => $content->metadata,
                                'change_type' => 'rollback',
                                'change_description' => "Restored from version {$record->version}",
                                'diff_summary' => $this->getDiffSummary($oldData, $content->getAttributes()),
                                'is_autosave' => false,
                            ]);

                            // Update current version
                            $content->update(['current_version' => $newVersion]);

                            // Refresh table data
                            // $this->refreshTable();

                            // Show success notification
                            Notification::make()
                                ->title("Content restored from version {$record->version}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title("Failed to restore revision: " . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('version', 'desc')
            ->emptyStateHeading('No revisions yet')
            ->emptyStateDescription('Revisions will appear here when you make changes to the content.');
    }

    private function getDiffSummary(array $oldData, array $newData): array
    {
        $changes = [];
        $significantFields = ['title', 'body', 'excerpt', 'metadata'];

        foreach ($significantFields as $field) {
            $oldValue = $oldData[$field] ?? null;
            $newValue = $newData[$field] ?? null;

            // Convert to JSON string for comparison if array
            $oldValueCompare = is_array($oldValue) ? json_encode($oldValue) : $oldValue;
            $newValueCompare = is_array($newValue) ? json_encode($newValue) : $newValue;

            if ($oldValueCompare != $newValueCompare) {
                $changes[$field] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    // private function refreshTable(): void
    // {
    //     // Refresh table data setelah update
    //     if (method_exists($this, 'getTable')) {
    //         $this->getTable()->refresh();
    //     }
    // }

    protected function getTableQuery()
    {
        return ContentRevision::where('content_id', $this->record->id)
            ->with('author')
            ->orderBy('version', 'desc');
    }
}
