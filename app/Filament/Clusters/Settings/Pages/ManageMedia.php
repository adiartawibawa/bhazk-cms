<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Filament\Clusters\Settings;
use App\Settings\MediaSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageMedia extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static string $settings = MediaSettings::class;

    protected static ?string $cluster = Settings::class;

    protected static ?string $navigationLabel = 'Media Settings';

    protected static ?string $title = 'Manage Media Settings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Upload Organization')
                    ->description('Configure how media files are organized in storage')
                    ->icon('heroicon-o-folder')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Select::make('upload_organization')
                            ->label('Upload Organization')
                            ->options([
                                'flat' => 'All files in single folder',
                                'year' => 'Folder per year',
                                'year_month' => 'Folder per year/month',
                            ])
                            ->default('year_month')
                            ->required()
                            ->native(false)
                            ->helperText('Folder structure for storing uploaded files.'),
                    ]),

                Forms\Components\Section::make('Image Sizes')
                    ->description('Default dimensions for thumbnail, medium, and large images')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Fieldset::make('Thumbnail Size')
                                    ->schema([
                                        Forms\Components\TextInput::make('thumbnail_size.width')
                                            ->label('Width (px)')
                                            ->numeric()
                                            ->default(150)
                                            ->required(),
                                        Forms\Components\TextInput::make('thumbnail_size.height')
                                            ->label('Height (px)')
                                            ->numeric()
                                            ->default(150)
                                            ->required(),
                                    ]),

                                Forms\Components\Fieldset::make('Medium Size')
                                    ->schema([
                                        Forms\Components\TextInput::make('medium_size.width')
                                            ->label('Width (px)')
                                            ->numeric()
                                            ->default(300)
                                            ->required(),
                                        Forms\Components\TextInput::make('medium_size.height')
                                            ->label('Height (px)')
                                            ->numeric()
                                            ->default(300)
                                            ->required(),
                                    ]),

                                Forms\Components\Fieldset::make('Large Size')
                                    ->schema([
                                        Forms\Components\TextInput::make('large_size.width')
                                            ->label('Width (px)')
                                            ->numeric()
                                            ->default(1024)
                                            ->required(),
                                        Forms\Components\TextInput::make('large_size.height')
                                            ->label('Height (px)')
                                            ->numeric()
                                            ->default(1024)
                                            ->required(),
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Upload Limit')
                    ->description('Configure maximum upload size for media files')
                    ->icon('heroicon-o-cloud-arrow-up')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('max_upload_size')
                            ->label('Maximum Upload Size')
                            ->numeric()
                            ->default(2048)
                            ->required()
                            ->suffix('KB')
                            ->helperText('Maximum file size allowed for upload (in kilobytes).'),
                    ]),
            ]);
    }
}
