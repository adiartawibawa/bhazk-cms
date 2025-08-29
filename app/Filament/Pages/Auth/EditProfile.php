<?php

namespace App\Filament\Pages\Auth;

use Closure;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ViewField;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use Laravel\Fortify\Features;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\TwoFactorAuthenticationProvider;

class EditProfile extends BaseEditProfile
{
    // --- State untuk multi form ---
    public ?array $profileData = [];
    public ?array $passwordData = [];
    public ?array $twoFactorData = [];
    public ?array $sessionsData = [];
    public ?array $deleteData = [];

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();

        // isi form profil awal
        $this->profileData = [
            'username' => $user->username ?? '',
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'profile_photo_path' => $user->profile_photo_path ?? null,
        ];

        $this->passwordData = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->twoFactorData = []; // tidak ada field input; hanya tombol actions
        $this->sessionsData = [
            'password' => '',
        ];

        $this->deleteData = [
            'password' => '',
        ];
    }

    // ======== FORM UTAMA (Profile Info) ========
    public function form(Form $form): Form
    {
        return $form
            ->model(Auth::user())
            ->statePath('profileData')
            ->schema([
                Section::make('Profile Information')
                    ->description('Update your account\'s profile information and email address.')
                    ->schema([
                        Grid::make()
                            ->schema([
                                FileUpload::make('profile_photo_path')
                                    ->label('Profile Photo')
                                    ->avatar()
                                    ->disk('public')
                                    ->directory('profile-photos')
                                    ->image()
                                    ->maxSize(1024)
                                    ->columnSpan(2),

                                Grid::make(2)->schema([
                                    TextInput::make('username')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true, table: fn() => auth()->user()->getTable(), column: 'username'),

                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('email')
                                        ->email()
                                        ->required()
                                        ->rule(fn() => Rule::unique(auth()->user()->getTable(), 'email')->ignoreModel(auth()->user())),
                                ])->columnSpan(10),
                            ])->columns(12),

                        // Info verifikasi email seperti Jetstream
                        ViewField::make('email_verification_hint')
                            ->view('filament.profile._email-verification-hint')
                            ->visible(fn() => method_exists(auth()->user(), 'hasVerifiedEmail') && ! auth()->user()->hasVerifiedEmail()),
                    ]),
            ]);
    }

    // Override aksi submit default untuk profile info
    protected function getFormActions(): array
    {
        return [
            Action::make('saveProfile')
                ->label('Save')
                ->submit('saveProfile')
                ->color('primary'),
            // Tombol kirim ulang verifikasi email seperti Jetstream
            Action::make('resendVerification')
                ->label('Resend Verification Email')
                ->visible(fn() => method_exists(auth()->user(), 'hasVerifiedEmail') && ! auth()->user()->hasVerifiedEmail())
                ->action(function () {
                    $user = auth()->user();
                    if ($user && method_exists($user, 'sendEmailVerificationNotification')) {
                        $user->sendEmailVerificationNotification();

                        Notification::make()
                            ->title('Verification link sent! Check your email.')
                            ->success()
                            ->send();
                    }
                }),
        ];
    }

    public function saveProfile(): void
    {
        $data = $this->profileData;

        $this->validate([
            'profileData.username' => ['required', 'string', 'max:255', Rule::unique(auth()->user()->getTable(), 'username')->ignore(auth()->id())],
            'profileData.name' => ['required', 'string', 'max:255'],
            'profileData.email' => ['required', 'string', 'email', 'max:255', Rule::unique(auth()->user()->getTable(), 'email')->ignore(auth()->id())],
        ]);

        $user = auth()->user();

        $emailChanged = $data['email'] !== $user->email;

        $user->fill([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['profile_photo_path'])) {
            $user->profile_photo_path = $data['profile_photo_path'];
        }

        // Jika email berubah dan model implement MustVerifyEmail, tandai unverified seperti Jetstream
        if ($emailChanged && method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            $user->email_verified_at = null;
        }

        $user->save();

        Notification::make()->title('Profile updated.')->success()->send();
    }

    // ======== FORM UPDATE PASSWORD (separate like Jetstream) ========
    protected function getHeaderActions(): array
    {
        // Header actions opsional; kita fokus ke section di bawah via custom view.
        return [];
    }

    // Kita render forms tambahan via custom view blade:
    protected static string $view = 'filament.pages.auth.full-edit-profile';

    // Helper untuk validasi current password
    protected function assertCurrentPassword(string $fieldPath): void
    {
        $current = data_get($this, $fieldPath);

        if (! Hash::check($current, auth()->user()->password)) {
            throw ValidationException::withMessages([
                $fieldPath => __('The provided password does not match your current password.'),
            ]);
        }
    }

    // ---- Actions yang dipakai di blade ----

    public function updatePassword(): void
    {
        $this->validate([
            'passwordData.current_password' => ['required', 'current_password'],
            'passwordData.password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = auth()->user();
        $user->forceFill([
            'password' => Hash::make($this->passwordData['password']),
        ])->save();

        // reset state
        $this->passwordData = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        Notification::make()->title('Password updated.')->success()->send();
    }

    public function enableTwoFactor(): void
    {
        if (! Features::enabled(Features::twoFactorAuthentication())) {
            Notification::make()->title('Two-factor is not enabled in Fortify.')->danger()->send();
            return;
        }

        app(EnableTwoFactorAuthentication::class)(auth()->user());

        Notification::make()->title('Two-factor authentication enabled.')->success()->send();
    }

    public function disableTwoFactor(): void
    {
        if (! Features::enabled(Features::twoFactorAuthentication())) {
            return;
        }

        app(DisableTwoFactorAuthentication::class)(auth()->user());

        Notification::make()->title('Two-factor authentication disabled.')->success()->send();
    }

    public function regenerateRecoveryCodes(): void
    {
        if (! Features::enabled(Features::twoFactorAuthentication())) {
            return;
        }

        app(GenerateNewRecoveryCodes::class)(auth()->user());

        Notification::make()->title('Recovery codes regenerated.')->success()->send();
    }

    public function logoutOtherSessions(): void
    {
        $this->validate([
            'sessionsData.password' => ['required', 'current_password'],
        ]);

        // Logout devices lain
        Auth::logoutOtherDevices($this->sessionsData['password']);

        // Hapus sesi database lain jika driver database
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', auth()->id())
                ->where('id', '!=', session()->getId())
                ->delete();
        }

        $this->sessionsData['password'] = '';

        Notification::make()->title('Logged out from other browser sessions.')->success()->send();
    }

    public function deleteAccount(): mixed
    {
        $this->validate([
            'deleteData.password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        Auth::logout();

        // Hapus user
        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Redirect ke login
        return redirect()->route('login');
    }

    // Data untuk Two-Factor (QR & recovery codes) diambil via Fortify seperti Jetstream
    public function getTwoFactorEnabledProperty(): bool
    {
        $user = auth()->user();

        return ! is_null($user->two_factor_secret ?? null);
    }

    public function getTwoFactorQrSvgProperty(): ?string
    {
        $user = auth()->user();

        if (! $this->twoFactorEnabled) {
            return null;
        }

        // Mirip Jetstream: pakai provider untuk QR
        /** @var TwoFactorAuthenticationProvider $provider */
        $provider = app(TwoFactorAuthenticationProvider::class);

        return $provider->qrCodeSvg(
            decrypt($user->two_factor_secret),
            $user->email
        );
    }

    public function getRecoveryCodesProperty(): array
    {
        $user = auth()->user();

        if (! $this->twoFactorEnabled) {
            return [];
        }

        $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);

        return is_array($codes) ? $codes : [];
    }
}
