<?php

namespace App\Livewire\Profile;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UpdatePassword extends Component implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms, WithRateLimiting;

    public ?array $data = [];

    public function mount(): void {}

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Update Password')
                    ->aside()
                    ->description('Ensure your account is using a long, random password to stay secure.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('currentPassword')
                            ->label('Current Password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required()
                            ->autocomplete('current-password')
                            ->currentPassword(),

                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrated(fn($state): bool => filled($state))
                            ->dehydrateStateUsing(fn($state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation'),

                        TextInput::make('passwordConfirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required()
                            ->visible(fn(Get $get): bool => filled($get('password')))
                            ->dehydrated(false),

                        Actions::make([
                            Action::make('save')
                                ->label('Save')
                                ->submit('updatePassword'),
                        ]),
                    ]),
            ])
            ->statePath('data')
            ->model(Auth::user());
    }

    public function updatePassword(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->sendRateLimitedNotification($exception);
            return;
        }

        $data = Arr::only($this->form->getState(), 'password');
        $user = Auth::user();

        $user->fill($data);

        if (! $user->isDirty('password')) {
            return;
        }

        $user->save();

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);
        }

        // reset form fields
        $this->data['password'] = null;
        $this->data['currentPassword'] = null;
        $this->data['passwordConfirmation'] = null;

        Notification::make()
            ->title('Password updated successfully')
            ->success()
            ->send();

        $this->dispatch('password-updated');
    }

    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
