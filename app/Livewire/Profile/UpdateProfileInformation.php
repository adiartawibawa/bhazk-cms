<?php

namespace App\Livewire\Profile;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdateProfileInformation extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use WithRateLimiting;

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'username'   => $user->username,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Update Profile Information')
                    ->aside()
                    ->description('Update your account\'s profile information and email address.')
                    ->icon('heroicon-o-swatch')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->label('Profile Avatar')
                            ->collection('avatars')
                            ->model(Auth::user())
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->helperText('Max 2MB. Allowed types: JPG, PNG, GIF, WEBP'),

                        TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: User::class,
                                ignorable: Auth::user()
                            )
                            ->regex('/^[a-zA-Z0-9_]+$/')
                            ->helperText('Only letters, numbers, and underscores are allowed'),

                        TextInput::make('first_name')
                            ->label('First Name')
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->label('Last Name')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(
                                table: User::class,
                                ignorable: Auth::user()
                            ),

                        Actions::make([
                            Action::make('save')
                                ->label('Save')
                                ->action('updateProfile')
                        ])->alignLeft(),
                    ]),
            ])
            ->statePath('data');
    }

    public function updateProfile(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->addError(
                'rate_limit',
                'Too many attempts. Please try again in ' . $exception->secondsUntilAvailable . ' seconds.'
            );
            return;
        }

        $validated = $this->validate([
            'data.username'    => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users', 'username')->ignore(Auth::id()),
            ],
            'data.first_name'  => ['nullable', 'string', 'max:255'],
            'data.last_name'   => ['nullable', 'string', 'max:255'],
            'data.email'       => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(Auth::id()),
            ],
            // avatar tidak divalidasi manual
        ]);

        $data = $validated['data'];
        $user = Auth::user();

        $isUpdatingEmail = $data['email'] !== $user->email;

        DB::transaction(function () use ($data, $user, $isUpdatingEmail) {
            $user->forceFill([
                'username'   => $data['username'],
                'first_name' => $data['first_name'] ?? null,
                'last_name'  => $data['last_name'] ?? null,
                'email'      => $data['email'],
            ])->save();

            if ($isUpdatingEmail) {
                $user->forceFill(['email_verified_at' => null])->save();
                $user->sendEmailVerificationNotification();
            }
            $this->form->model($user)->saveRelationships();
        });

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information');
    }
}
