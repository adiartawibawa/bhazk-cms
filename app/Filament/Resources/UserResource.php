<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Avatar')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                            ->collection('avatars')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->circleCropper()
                            ->maxSize(2048) // 2MB
                            ->label('Profile Picture')
                            ->helperText('Upload a profile picture (max 2MB)'),
                    ])
                    ->collapsible(),

                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('username')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Username'),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Email Address'),

                        TextInput::make('first_name')
                            ->maxLength(255)
                            ->label('First Name'),

                        TextInput::make('last_name')
                            ->maxLength(255)
                            ->label('Last Name'),
                    ]),

                Section::make('Security & Preferences')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->label('Password'),

                        Select::make('roles')
                            ->label('Roles')
                            ->multiple()
                            ->relationship('roles', 'name', fn($query) => $query->where('name', '!=', 'super_admin'))
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->label('Role Name')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ])
                            ->createOptionAction(function ($action) {
                                return $action
                                    ->modalHeading('Create Role')
                                    ->modalButton('Create')
                                    ->modal(); // slideOver()
                            })
                            ->default(
                                fn() => Role::where('name', 'user')->pluck('id')->toArray()
                            ),

                        Select::make('timezone')
                            ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                            ->searchable()
                            ->default('UTC')
                            ->label('Timezone'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('User account is enabled'),
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
                    ->label('Avatar'),

                TextColumn::make('username')
                    ->searchable()
                    ->sortable()
                    ->label('Username'),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),

                TextColumn::make('full_name')
                    ->getStateUsing(fn($record) => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->label('Full Name'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('last_login_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Last Login'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Updated'),

                Tables\Columns\TextColumn::make('creator.username')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),

                Tables\Columns\TextColumn::make('updater.username')
                    ->label('Updated By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter by Role'),

                Tables\Filters\Filter::make('is_active')
                    ->label('Active Users')
                    ->query(fn(Builder $query) => $query->where('is_active', true)),

                Tables\Filters\Filter::make('never_logged_in')
                    ->label('Never Logged In')
                    ->query(fn(Builder $query) => $query->whereNull('last_login_at')),
            ])
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
        return [
            // RelationManagers\ContentsRelationManager::class,
            // RelationManagers\ContentRevisionsRelationManager::class,
            // RelationManagers\RolesRelationManager::class,
        ];
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
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['username', 'email', 'first_name', 'last_name'];
    }
}
