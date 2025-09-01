<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected static string $view = 'filament.pages.edit-profile';

    public function mount(): void
    {
        parent::mount();
    }

    public static function isSimple(): bool
    {
        return false;
    }
}
