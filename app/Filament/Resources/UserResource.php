<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('resource.user.navigation.group');
    }

    public static function getPluralLabel(): ?string
    {
        return __('resource.user.navigation.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('resource.user.sections.avatar'))
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                            ->collection('avatars')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->circleCropper()
                            ->maxSize(2048)
                            ->label(__('resource.user.fields.avatar.label'))
                            ->helperText(__('resource.user.fields.avatar.helper')),
                    ])
                    ->collapsible(),

                Section::make(__('resource.user.sections.basic'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('username')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label(__('resource.user.fields.username')),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label(__('resource.user.fields.email')),

                        TextInput::make('first_name')
                            ->maxLength(255)
                            ->label(__('resource.user.fields.first_name')),

                        TextInput::make('last_name')
                            ->maxLength(255)
                            ->label(__('resource.user.fields.last_name')),
                    ]),

                Section::make(__('resource.user.sections.security'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->label(__('resource.user.fields.password')),

                        Select::make('roles')
                            ->label(__('resource.user.fields.roles'))
                            ->multiple()
                            ->relationship('roles', 'name', fn($query) => $query->where('name', '!=', 'super_admin'))
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label(__('resource.user.fields.role_name'))
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ])
                            ->createOptionAction(function ($action) {
                                return $action
                                    ->modalHeading(__('resource.user.actions.create_role.heading'))
                                    ->modalButton(__('resource.user.actions.create_role.button'))
                                    ->modal();
                            })
                            ->default(fn() => Role::where('name', 'user')->pluck('id')->toArray()),

                        Select::make('timezone')
                            ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                            ->searchable()
                            ->default('UTC')
                            ->label(__('resource.user.fields.timezone')),

                        Toggle::make('is_active')
                            ->label(__('resource.user.fields.is_active.label'))
                            ->default(true)
                            ->helperText(__('resource.user.fields.is_active.helper')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatars')
                    ->circular()
                    ->defaultImageUrl(fn($record) => $record->getFilamentAvatarUrl())
                    ->label(__('resource.user.table.avatar')),

                TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->label(__('resource.user.table.username')),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__('resource.user.table.email')),

                TextColumn::make('full_name')
                    ->getStateUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->label(__('resource.user.table.full_name')),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('resource.user.table.is_active'))
                    ->sortable(),

                TextColumn::make('last_login_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('resource.user.table.last_login')),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('resource.user.table.created')),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('resource.user.table.updated')),

                TextColumn::make('creator.username')
                    ->label(__('resource.user.table.created_by'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                TextColumn::make('updater.username')
                    ->label(__('resource.user.table.updated_by'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label(__('resource.user.filters.start_date'))->default(now()->subDays(30)),
                        DatePicker::make('end_date')
                            ->label(__('resource.user.filters.end_date'))->default(now()),
                    ])->columns(2)
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_date'] ?? null) {
                            $indicators['start_date'] = __('resource.user.filters.start_date') . ': ' . Carbon::parse($data['start_date'])->toFormattedDateString();
                        }

                        if ($data['end_date'] ?? null) {
                            $indicators['end_date'] = __('resource.user.filters.end_date') . ': ' . Carbon::parse($data['end_date'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label(__('resource.user.filters.role')),

            ], layout: FiltersLayout::AboveContentCollapsible)->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['username', 'email', 'first_name', 'last_name'];
    }
}
