<?php

namespace App\Filament\Resources;

use App\Concerns\BuildsDynamicFields;
use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use App\Models\ContentType;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
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

    public static function getNavigationGroup(): ?string
    {
        return __('resource.content.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource.content.navigation.label');
    }

    public static function getPluralLabel(): ?string
    {
        return __('resource.content.navigation.label');
    }

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section 1: Content Information
                Forms\Components\Section::make(__('resource.content.sections.information.label'))
                    ->description(__('resource.content.sections.information.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('content_type_id')
                                    ->label(__('resource.content.fields.content_type_id'))
                                    ->options(ContentType::query()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(fn($state, callable $set) => $set('body', [])),

                                Forms\Components\Select::make('status')
                                    ->label(__('resource.content.fields.status'))
                                    ->options([
                                        Content::STATUS_DRAFT     => __('resource.content.options.status.draft'),
                                        Content::STATUS_PUBLISHED => __('resource.content.options.status.published'),
                                        Content::STATUS_ARCHIVED  => __('resource.content.options.status.archived'),
                                    ])
                                    ->default(Content::STATUS_DRAFT)
                                    ->required()
                                    ->reactive(),

                                Forms\Components\TextInput::make('title')
                                    ->label(__('resource.content.fields.title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, $set, $operation) {
                                        if (in_array($operation, ['create', 'edit'])) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('resource.content.fields.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->disabled(fn($operation) => $operation === 'edit'),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label(__('resource.content.fields.published_at'))
                                    ->hidden(fn($get) => $get('status') !== Content::STATUS_PUBLISHED),

                                Forms\Components\Select::make('author_id')
                                    ->label(__('resource.content.fields.author'))
                                    ->relationship('author', 'username')
                                    ->default(Auth::id())
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('editor_id')
                                    ->label(__('resource.content.fields.editor'))
                                    ->relationship('editor', 'username')
                                    ->searchable()
                                    ->preload()
                                    ->hidden(fn($operation) => $operation === 'create'),
                            ]),
                    ])
                    ->collapsible(),

                // Section 2: Content Body
                Forms\Components\Section::make(__('resource.content.sections.body.label'))
                    ->description(__('resource.content.sections.body.description'))
                    ->schema(fn(Forms\Get $get) => self::buildDynamicSchema(ContentType::find($get('content_type_id'))))
                    ->columnSpanFull()
                    ->hidden(fn(Forms\Get $get) => empty($get('content_type_id')))
                    ->collapsible(),

                // Section 3: Excerpt and Metadata
                Forms\Components\Section::make(__('resource.content.sections.summary.label'))
                    ->description(__('resource.content.sections.summary.description'))
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label(__('resource.content.fields.excerpt'))
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('metadata')
                            ->label(__('resource.content.fields.metadata'))
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Section 4: Content Settings
                Forms\Components\Section::make(__('resource.content.sections.settings.label'))
                    ->description(__('resource.content.sections.settings.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('featured')
                                    ->label(__('resource.content.fields.featured'))
                                    ->default(false),

                                Forms\Components\Toggle::make('commentable')
                                    ->label(__('resource.content.fields.commentable'))
                                    ->default(true),
                            ]),
                    ])
                    ->collapsible(),

                // Section 5: Categorization
                Forms\Components\Section::make(__('resource.content.sections.categorization.label'))
                    ->description(__('resource.content.sections.categorization.description'))
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('categories')
                                    ->label(__('resource.content.fields.categories'))
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('tags')
                                    ->label(__('resource.content.fields.tags'))
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('resource.content.columns.title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('contentType.name')
                    ->label(__('resource.content.columns.type'))
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('resource.content.columns.status'))
                    ->colors([
                        'warning' => Content::STATUS_DRAFT,
                        'success' => Content::STATUS_PUBLISHED,
                        'danger'  => Content::STATUS_ARCHIVED,
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.username')
                    ->label(__('resource.content.columns.author'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('featured')
                    ->label(__('resource.content.columns.featured'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('resource.content.columns.published'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('comments_count')
                    ->label(__('resource.content.columns.comments'))
                    ->counts('comments')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('commentable')
                    ->label(__('resource.content.columns.commentable'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('revisions_count')
                    ->label(__('resource.content.columns.revisions'))
                    ->counts('revisions')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resource.content.columns.created'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('resource.content.columns.updated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label(__('resource.content.filters.start_date'))->default(now()->subDays(30)),
                        DatePicker::make('end_date')
                            ->label(__('resource.content.filters.end_date'))->default(now()),
                    ])->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_date'] ?? null) {
                            $indicators['start_date'] = __('resource.content.filters.start_date') . ': ' . Carbon::parse($data['start_date'])->toFormattedDateString();
                        }

                        if ($data['end_date'] ?? null) {
                            $indicators['end_date'] = __('resource.content.filters.end_date') . ': ' . Carbon::parse($data['end_date'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('content_type_id')
                    ->label(__('resource.content.filters.content_type'))
                    ->relationship('contentType', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('resource.content.filters.status'))
                    ->options([
                        Content::STATUS_DRAFT     => __('resource.content.options.status.draft'),
                        Content::STATUS_PUBLISHED => __('resource.content.options.status.published'),
                        Content::STATUS_ARCHIVED  => __('resource.content.options.status.archived'),
                    ]),

                Tables\Filters\SelectFilter::make('author_id')
                    ->label(__('resource.content.filters.author'))
                    ->relationship('author', 'username')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('featured')
                    ->label(__('resource.content.filters.featured'))
                    ->toggle(),

                Tables\Filters\Filter::make('published')
                    ->label(__('resource.content.filters.published'))
                    ->query(fn(Builder $query) => $query->published()),

                Tables\Filters\Filter::make('needs_review')
                    ->label(__('resource.content.filters.needs_review'))
                    ->query(fn(Builder $query) => $query->where('status', Content::STATUS_DRAFT)),

            ], layout: FiltersLayout::Modal)
            ->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label(__('resource.content.actions.view')),

                Tables\Actions\EditAction::make()
                    ->label(__('resource.content.actions.edit')),

                Tables\Actions\Action::make('revisions')
                    ->label(__('resource.content.actions.revisions'))
                    ->icon('heroicon-o-clock')
                    ->url(fn(Content $record) => ContentResource::getUrl('revisions', ['record' => $record])),

                Tables\Actions\DeleteAction::make()
                    ->label(__('resource.content.actions.delete')),

                Tables\Actions\ForceDeleteAction::make()
                    ->label(__('resource.content.actions.force_delete')),

                Tables\Actions\RestoreAction::make()
                    ->label(__('resource.content.actions.restore')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('resource.content.actions.delete')),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label(__('resource.content.actions.force_delete')),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label(__('resource.content.actions.restore')),

                    Tables\Actions\BulkAction::make('publish')
                        ->label(__('resource.content.actions.publish'))
                        ->action(fn($records) => $records->each->update([
                            'status'       => Content::STATUS_PUBLISHED,
                            'published_at' => now(),
                        ]))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('resource.content.actions.create')),
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
            'index'     => Pages\ListContents::route('/'),
            'create'    => Pages\CreateContent::route('/create'),
            'view'      => Pages\ViewContent::route('/{record}'),
            'edit'      => Pages\EditContent::route('/{record}/edit'),
            'revisions' => Pages\ContentRevisions::route('/{record}/revisions'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->with(['contentType', 'author', 'editor', 'revisions'])
            ->withCount(['comments', 'revisions']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'excerpt'];
    }
}
