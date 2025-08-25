<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use App\Models\ContentType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContentResource extends Resource
{
    use Translatable;

    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content Information')
                    ->schema([
                        Forms\Components\Select::make('content_type_id')
                            ->label('Content Type')
                            ->relationship('contentType', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $contentType = ContentType::find($state);
                                    if ($contentType && isset($contentType->fields)) {
                                        $set('custom_fields', $contentType->fields);
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set, $operation) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn($operation) => $operation === 'edit'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                Content::STATUS_DRAFT => 'Draft',
                                Content::STATUS_PUBLISHED => 'Published',
                                Content::STATUS_ARCHIVED => 'Archived',
                            ])
                            ->default(Content::STATUS_DRAFT)
                            ->required()
                            ->reactive(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Publish Date')
                            ->hidden(fn($get) => $get('status') !== Content::STATUS_PUBLISHED),

                        Forms\Components\Select::make('author_id')
                            ->label('Author')
                            ->relationship('author', 'username')
                            ->default(Auth::id())
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('editor_id')
                            ->label('Editor')
                            ->relationship('editor', 'username')
                            ->searchable()
                            ->preload()
                            ->hidden(fn($operation) => $operation === 'create'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content Body')
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Excerpt')
                            ->rows(3)
                            ->maxLength(500),

                        Forms\Components\RichEditor::make('body')
                            ->label('Body')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('content')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Metadata')
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('featured')
                            ->label('Featured Content')
                            ->default(false),

                        Forms\Components\Toggle::make('commentable')
                            ->label('Allow Comments')
                            ->default(true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Categories & Tags')
                    ->schema([
                        Forms\Components\Select::make('categories')
                            ->label('Categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                            ]),

                        Forms\Components\Select::make('tags')
                            ->label('Tags')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),
                            ]),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('contentType.name')
                    ->label('Type')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('author.username')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => Content::STATUS_DRAFT,
                        'success' => Content::STATUS_PUBLISHED,
                        'danger' => Content::STATUS_ARCHIVED,
                    ])
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('commentable')
                    ->label('Commentable')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('comment_count')
                    ->label('Comments')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('revisions_count')
                    ->label('Revisions')
                    ->counts('revisions')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('content_type_id')
                    ->label('Content Type')
                    ->relationship('contentType', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Content::STATUS_DRAFT => 'Draft',
                        Content::STATUS_PUBLISHED => 'Published',
                        Content::STATUS_ARCHIVED => 'Archived',
                    ]),

                Tables\Filters\SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'username')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('featured')
                    ->label('Featured Content')
                    ->toggle(),

                Tables\Filters\Filter::make('published')
                    ->label('Published Content')
                    ->query(fn(Builder $query) => $query->published()),

                Tables\Filters\Filter::make('needs_review')
                    ->label('Needs Review')
                    ->query(fn(Builder $query) => $query->where('status', Content::STATUS_DRAFT)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('revisions')
                    ->label('Revisions')
                    ->icon('heroicon-o-clock')
                    ->url(fn(Content $record) => ContentResource::getUrl('revisions', ['record' => $record])),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->action(fn($records) => $records->each->update(['status' => Content::STATUS_PUBLISHED, 'published_at' => now()]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'view' => Pages\ViewContent::route('/{record}'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
            'revisions' => Pages\ContentRevisions::route('/{record}/revisions'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with(['contentType', 'author', 'editor', 'revisions'])
            ->withCount(['comments', 'revisions']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'excerpt'];
    }
}
