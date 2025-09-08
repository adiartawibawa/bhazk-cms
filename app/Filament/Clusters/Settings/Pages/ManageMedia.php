<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\MediaSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

class ManageMedia extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static string $settings = MediaSettings::class;

    protected static ?string $cluster = Settings::class;

    public static function getNavigationLabel(): string
    {
        return __('resource.settings.media.navigation.label');
    }

    public function getTitle(): string|Htmlable
    {
        return __('resource.settings.media.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('resource.settings.media.navigation.group');
    }

    protected static ?int $navigationSort = 3;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('resource.settings.media.sections.upload_organization.label'))
                    ->description(__('resource.settings.media.sections.upload_organization.description'))
                    ->icon('heroicon-o-folder')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('upload_organization')
                            ->label(__('resource.settings.media.fields.upload_organization'))
                            ->options([
                                'flat' => __('resource.settings.media.options.upload_organization.flat'),
                                'year' => __('resource.settings.media.options.upload_organization.year'),
                                'year_month' => __('resource.settings.media.options.upload_organization.year_month'),
                            ])
                            ->default('year_month')
                            ->required()
                            ->native(false)
                            ->helperText(__('resource.settings.media.helpers.upload_organization')),

                        Forms\Components\Select::make('storage_disk')
                            ->label(__('resource.settings.media.fields.storage_disk'))
                            ->options(collect(config('filesystems.disks'))
                                ->mapWithKeys(fn($disk, $key) => [$key => ucfirst($key)]))
                            ->default(config('filesystems.default'))
                            ->required()
                            ->native(false)
                            ->helperText(__('resource.settings.media.helpers.storage_disk')),
                    ]),

                Forms\Components\Section::make(__('resource.settings.media.sections.image_sizes.label'))
                    ->description(__('resource.settings.media.sections.image_sizes.description'))
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Fieldset::make(__('resource.settings.media.sections.image_sizes.fields.thumbnail.label'))
                                    ->schema([
                                        Forms\Components\TextInput::make('thumbnail_size.width')
                                            ->label(__('resource.settings.media.fields.width'))
                                            ->numeric()
                                            ->default(150)
                                            ->required(),
                                        Forms\Components\TextInput::make('thumbnail_size.height')
                                            ->label(__('resource.settings.media.fields.height'))
                                            ->numeric()
                                            ->default(150)
                                            ->required(),
                                    ]),

                                Forms\Components\Fieldset::make(__('resource.settings.media.sections.image_sizes.fields.medium.label'))
                                    ->schema([
                                        Forms\Components\TextInput::make('medium_size.width')
                                            ->label(__('resource.settings.media.fields.width'))
                                            ->numeric()
                                            ->default(300)
                                            ->required(),
                                        Forms\Components\TextInput::make('medium_size.height')
                                            ->label(__('resource.settings.media.fields.height'))
                                            ->numeric()
                                            ->default(300)
                                            ->required(),
                                    ]),

                                Forms\Components\Fieldset::make(__('resource.settings.media.sections.image_sizes.fields.large.label'))
                                    ->schema([
                                        Forms\Components\TextInput::make('large_size.width')
                                            ->label(__('resource.settings.media.fields.width'))
                                            ->numeric()
                                            ->default(1024)
                                            ->required(),
                                        Forms\Components\TextInput::make('large_size.height')
                                            ->label(__('resource.settings.media.fields.height'))
                                            ->numeric()
                                            ->default(1024)
                                            ->required(),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make(__('resource.settings.media.sections.upload_limit.label'))
                    ->description(__('resource.settings.media.sections.upload_limit.description'))
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('max_upload_size')
                            ->label(__('resource.settings.media.fields.max_upload_size'))
                            ->numeric()
                            ->default(2048)
                            ->required()
                            ->suffix(__('resource.settings.media.placeholders.max_upload_size'))
                            ->helperText(__('resource.settings.media.helpers.max_upload_size')),
                    ]),
            ]);
    }
}
