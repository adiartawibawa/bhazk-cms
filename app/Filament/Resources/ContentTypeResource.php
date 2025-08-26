<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentTypeResource\Pages;
use App\Filament\Resources\ContentTypeResource\RelationManagers;
use App\Models\ContentType;
use App\Tables\Columns\IconsColumn;
use App\View\Components\IconPicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
                Forms\Components\Section::make('General')->schema([
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

                    IconPicker::make('icon')
                        ->label('Icon')
                        ->helperText('Pilih ikon untuk content type ini'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->required(),
                ])->columns(2),
                Forms\Components\Section::make('Schema Builder')
                    ->description('Define the custom fields for this content type')
                    ->schema([
                        Forms\Components\Repeater::make('fields')
                            ->label('Fields')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->rule('alpha_dash')
                                    ->helperText('Unique key, gunakan huruf, angka, dash/underscore.'),
                                Forms\Components\TextInput::make('label')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->required()
                                    ->options([
                                        'text' => 'Text',
                                        'textarea' => 'Textarea',
                                        'richtext' => 'RichText',
                                        'markdown' => 'Markdown',
                                        'number' => 'Number',
                                        'boolean' => 'Boolean',
                                        'date' => 'Date',
                                        'datetime' => 'DateTime',
                                        'image' => 'Image',
                                        'file' => 'File',
                                        'gallery' => 'Gallery (multiple images)',
                                        'select' => 'Select (static options)',
                                        'tags' => 'Tags',
                                        'repeater' => 'Repeater (nested fields)',
                                    ]),
                                Forms\Components\KeyValue::make('options')
                                    ->label('Options (untuk select/tags)')
                                    ->keyLabel('value')
                                    ->valueLabel('label')
                                    ->visible(fn(Forms\Get $get) => in_array($get('type'), ['select'])),
                                Forms\Components\Toggle::make('multiple')
                                    ->visible(fn(Forms\Get $get) => in_array($get('type'), ['file', 'image', 'gallery', 'tags', 'select'])),
                                Forms\Components\TextInput::make('placeholder')
                                    ->maxLength(200),
                                Forms\Components\TextInput::make('help')
                                    ->label('Help Text'),
                                Forms\Components\TextInput::make('default')
                                    ->label('Default (string/json)')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('rules')
                                    ->label('Validation rules (pipe)')
                                    ->helperText('contoh: required|min:3'),
                                Forms\Components\TextInput::make('directory')
                                    ->label('Upload dir (image/file)')
                                    ->visible(fn(Forms\Get $get) => in_array($get('type'), ['image', 'file', 'gallery'])),

                                // nested schema for repeater
                                Forms\Components\Repeater::make('schema')
                                    ->label('Repeater Fields')
                                    ->visible(fn(Forms\Get $get) => $get('type') === 'repeater')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->rule('alpha_dash'),
                                        Forms\Components\TextInput::make('label')
                                            ->required(),
                                        Forms\Components\Select::make('type')
                                            ->required()
                                            ->options([
                                                'text' => 'Text',
                                                'textarea' => 'Textarea',
                                                'number' => 'Number',
                                                'boolean' => 'Boolean',
                                                'date' => 'Date',
                                                'datetime' => 'DateTime',
                                                'select' => 'Select',
                                            ]),
                                        Forms\Components\KeyValue::make('options')
                                            ->visible(fn(Forms\Get $get) => $get('type') === 'select'),
                                        Forms\Components\TextInput::make('rules')
                                            ->label('Validation'),
                                    ])->collapsed(),
                            ])
                            ->collapsed()
                            ->reorderable()
                            ->grid(1)
                            ->columns(2),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconsColumn::make('icon')
                    ->label('Icon')
                    ->getStateUsing(function ($record) {
                        return $record->icon ?? 'heroicon-o-rectangle-stack';
                    }),

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

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $names = collect($data['schema'] ?? [])->pluck('name');
        if ($names->duplicates()->isNotEmpty()) {
            throw ValidationException::withMessages([
                'schema' => 'Nama field pada schema tidak boleh duplikat.'
            ]);
        }
        return $data;
    }
}
