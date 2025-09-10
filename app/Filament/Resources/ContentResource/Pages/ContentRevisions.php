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
                ->label(__('resource.content.revisions.actions.back'))
                ->url(fn() => ContentResource::getUrl('edit', ['record' => $this->record])),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.content.revisions.title', ['title' => $this->record->title]);
    }

    public function getBreadcrumb(): string
    {
        return __('resource.content.revisions.breadcrumb');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ContentRevision::where('content_id', $this->record->id))
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->label(__('resource.content.revisions.columns.version'))
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => 'v' . $state),

                Tables\Columns\TextColumn::make('author.username')
                    ->label(__('resource.content.revisions.columns.author'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('change_type')
                    ->label(__('resource.content.revisions.columns.change_type'))
                    ->badge()
                    ->colors([
                        'primary' => 'created',
                        'success' => 'update',
                        'warning' => 'status_change',
                        'info'    => 'rollback',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('change_description')
                    ->label(__('resource.content.revisions.columns.description'))
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resource.content.revisions.columns.date'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_autosave')
                    ->label(__('resource.content.revisions.columns.autosave'))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('resource.content.revisions.actions.view'))
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn(ContentRevision $record) => __('resource.content.revisions.modal.view_heading', ['version' => $record->version]))
                    ->modalContent(fn(ContentRevision $record) => view('filament.resources.content-resource.pages.content-revision-view', [
                        'revision' => $record,
                    ])),

                Tables\Actions\Action::make('restore')
                    ->label(__('resource.content.revisions.actions.restore'))
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->modalHeading(__('resource.content.revisions.modal.restore_heading'))
                    ->modalDescription(__('resource.content.revisions.modal.restore_description'))
                    ->action(fn(ContentRevision $record) => $this->restoreRevision($record)),
            ])
            ->defaultSort('version', 'desc')
            ->emptyStateHeading(__('resource.content.revisions.empty.heading'))
            ->emptyStateDescription(__('resource.content.revisions.empty.description'));
    }

    private function restoreRevision(ContentRevision $record): void
    {
        try {
            $content = Content::findOrFail($record->content_id);
            $oldData = $content->getAttributes();

            $content->update([
                'title'     => $record->title,
                'body'      => $record->body,
                'excerpt'   => $record->excerpt,
                'metadata'  => $record->metadata,
                'editor_id' => Auth::id(),
            ]);

            $newVersion = $content->current_version + 1;

            ContentRevision::create([
                'content_id'       => $content->id,
                'author_id'        => Auth::id(),
                'version'          => $newVersion,
                'title'            => $content->title,
                'body'             => $content->body,
                'excerpt'          => $content->excerpt,
                'metadata'         => $content->metadata,
                'change_type'      => 'rollback',
                'change_description' => __('resource.content.revisions.restore_message', ['version' => $record->version]),
                'diff_summary'     => $this->getDiffSummary($oldData, $content->getAttributes()),
                'is_autosave'      => false,
            ]);

            $content->update(['current_version' => $newVersion]);

            Notification::make()
                ->title(__('resource.content.revisions.notifications.success', ['version' => $record->version]))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('resource.content.revisions.notifications.error', ['message' => $e->getMessage()]))
                ->danger()
                ->send();
        }
    }
}
