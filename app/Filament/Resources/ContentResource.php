<?php

namespace App\Filament\Resources;

use App\Concerns\BuildsDynamicFields;
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
    use BuildsDynamicFields;

    protected static ?string $model = Content::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Content Information
                Forms\Components\Section::make('Content Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('content_type_id')
                                    ->label('Content Type')
                                    ->options(ContentType::query()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('body', []);
                                    }),

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

                                Forms\Components\TextInput::make('title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $operation) {
                                        if ($operation === 'create' || $operation === 'edit') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn($operation) => $operation === 'edit'),

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
                            ]),
                    ])
                    ->collapsible(),

                // Section 2: Content Body (Dynamic Fields)
                Forms\Components\Section::make('Content Body')
                    ->schema(function (Forms\Get $get) {
                        $type = ContentType::find($get('content_type_id'));
                        return self::buildDynamicSchema($type);
                    })
                    ->columnSpanFull()
                    ->hidden(fn(Forms\Get $get) => empty($get('content_type_id')))
                    ->collapsible(),

                // Section 3: Excerpt and Metadata
                Forms\Components\Section::make('Summary & Metadata')
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Excerpt')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->columns(1),

                // Section 4: Content Settings
                Forms\Components\Section::make('Content Settings')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('featured')
                                    ->label('Featured Content')
                                    ->default(false),

                                Forms\Components\Toggle::make('commentable')
                                    ->label('Allow Comments')
                                    ->default(true),
                            ]),
                    ])
                    ->collapsible(),

                // Section 5: Categories & Tags
                Forms\Components\Section::make('Categorization')
                    ->schema([
                        Forms\Components\Grid::make(2)
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
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Title Column
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn(Content $record) => $record->title),

                // Content Type Column
                Tables\Columns\TextColumn::make('contentType.name')
                    ->label('Type')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                // Status Column
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => Content::STATUS_DRAFT,
                        'success' => Content::STATUS_PUBLISHED,
                        'danger' => Content::STATUS_ARCHIVED,
                    ])
                    ->sortable(),

                // Author Column
                Tables\Columns\TextColumn::make('author.username')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                // Featured Column
                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                // Published Date Column
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                // Comment Count Column
                Tables\Columns\TextColumn::make('comment_count')
                    ->label('Comments')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                // Commentable Column
                Tables\Columns\IconColumn::make('commentable')
                    ->label('Commentable')
                    ->boolean()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Revisions Count Column
                Tables\Columns\TextColumn::make('revisions_count')
                    ->label('Revisions')
                    ->counts('revisions')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Created At Column
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Updated At Column
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Trashed Filter
                Tables\Filters\TrashedFilter::make(),

                // Content Type Filter
                Tables\Filters\SelectFilter::make('content_type_id')
                    ->label('Content Type')
                    ->relationship('contentType', 'name')
                    ->searchable()
                    ->preload(),

                // Status Filter
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Content::STATUS_DRAFT => 'Draft',
                        Content::STATUS_PUBLISHED => 'Published',
                        Content::STATUS_ARCHIVED => 'Archived',
                    ]),

                // Author Filter
                Tables\Filters\SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'username')
                    ->searchable()
                    ->preload(),

                // Featured Filter
                Tables\Filters\Filter::make('featured')
                    ->label('Featured Content')
                    ->toggle(),

                // Published Filter
                Tables\Filters\Filter::make('published')
                    ->label('Published Content')
                    ->query(fn(Builder $query) => $query->published()),

                // Needs Review Filter
                Tables\Filters\Filter::make('needs_review')
                    ->label('Needs Review')
                    ->query(fn(Builder $query) => $query->where('status', Content::STATUS_DRAFT)),
            ])
            ->actions([
                // View Action
                Tables\Actions\ViewAction::make(),

                // Edit Action
                Tables\Actions\EditAction::make(),

                // Revisions Action
                Tables\Actions\Action::make('revisions')
                    ->label('Revisions')
                    ->icon('heroicon-o-clock')
                    ->url(fn(Content $record) => ContentResource::getUrl('revisions', ['record' => $record])),

                // Delete Action
                Tables\Actions\DeleteAction::make(),

                // Force Delete Action
                Tables\Actions\ForceDeleteAction::make(),

                // Restore Action
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Delete Bulk Action
                    Tables\Actions\DeleteBulkAction::make(),

                    // Force Delete Bulk Action
                    Tables\Actions\ForceDeleteBulkAction::make(),

                    // Restore Bulk Action
                    Tables\Actions\RestoreBulkAction::make(),

                    // Publish Bulk Action
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->action(fn($records) => $records->each->update([
                            'status' => Content::STATUS_PUBLISHED,
                            'published_at' => now()
                        ]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistFiltersInSession()
            ->persistSearchInSession();
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
