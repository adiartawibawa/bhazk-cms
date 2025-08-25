<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentTypeResource\Pages;
use App\Filament\Resources\ContentTypeResource\RelationManagers;
use App\Models\ContentType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ContentTypeResource extends Resource
{
    use Translatable;

    protected static ?string $model = ContentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content Type Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
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

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Fields Configuration')
                    ->description('Define the custom fields for this content type')
                    ->schema([
                        Forms\Components\Repeater::make('fields')
                            ->label('Fields')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Field Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('label')
                                    ->label('Field Label')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('type')
                                    ->label('Field Type')
                                    ->options([
                                        'text' => 'Text',
                                        'textarea' => 'Textarea',
                                        'richtext' => 'Rich Text',
                                        'number' => 'Number',
                                        'email' => 'Email',
                                        'url' => 'URL',
                                        'date' => 'Date',
                                        'datetime' => 'DateTime',
                                        'boolean' => 'Boolean',
                                        'select' => 'Select',
                                        'multiselect' => 'Multi Select',
                                        'image' => 'Image',
                                        'file' => 'File',
                                        'color' => 'Color',
                                    ])
                                    ->required()
                                    ->reactive(),

                                Forms\Components\Textarea::make('options')
                                    ->label('Options (for select/multiselect)')
                                    ->placeholder("option1: Label 1\noption2: Label 2")
                                    ->rows(3)
                                    ->hidden(fn($get) => !in_array($get('type'), ['select', 'multiselect'])),

                                Forms\Components\Toggle::make('required')
                                    ->label('Required')
                                    ->default(false),

                                Forms\Components\TextInput::make('default_value')
                                    ->label('Default Value')
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('validation_rules')
                                    ->label('Validation Rules')
                                    ->placeholder('min:3|max:255')
                                    ->rows(2),

                                Forms\Components\Textarea::make('help_text')
                                    ->label('Help Text')
                                    ->rows(2),
                            ])
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->grid(2)
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('fields_count')
                    ->label('Fields')
                    ->getStateUsing(fn($record) => count($record->fields ?? []))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contents_count')
                    ->label('Contents')
                    ->counts('contents')
                    ->numeric()
                    ->sortable(),

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

                Tables\Filters\Filter::make('is_active')
                    ->label('Active Types')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('is_active', true)),

                Tables\Filters\Filter::make('inactive')
                    ->label('Inactive Types')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('is_active', false)),

                Tables\Filters\Filter::make('has_fields')
                    ->label('Has Fields')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->whereJsonLength('fields', '>', 0)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentTypes::route('/'),
            'create' => Pages\CreateContentType::route('/create'),
            'view' => Pages\ViewContentType::route('/{record}'),
            'edit' => Pages\EditContentType::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->withCount('contents');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }
}
