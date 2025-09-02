<?php

namespace App\Livewire\Profile;

use App\Services\Agent;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LogoutOtherBrowserSessions extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Browser Sessions')
                    ->description('Manage and log out other active browser sessions.')
                    ->aside()
                    ->icon('heroicon-o-computer-desktop')
                    ->schema([
                        ViewField::make('browserSessions')
                            ->hiddenLabel()
                            ->view('components.browser-sessions')
                            ->viewData(['sessions' => self::browserSessions()]),

                        Actions::make([
                            Action::make('deleteBrowserSessions')
                                ->label('Log Out Other Browsers')
                                ->requiresConfirmation()
                                ->modalHeading('Log Out Other Browser Sessions')
                                ->modalDescription('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.')
                                ->modalSubmitActionLabel('Log Out Other Browsers')
                                ->form([
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label('Password')
                                        ->required()
                                        ->currentPassword(),
                                ])
                                ->action(fn(array $data) => $this->logoutOtherBrowserSessions($data['password'])),
                        ])->alignLeft(),
                    ]),
            ])
            ->statePath('data');
    }

    protected function logoutOtherBrowserSessions(string $password): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        auth(filament()->getAuthGuard())->logoutOtherDevices($password);

        DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', filament()->auth()->user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();

        request()->session()->put([
            'password_hash_' . Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
        ]);

        Notification::make()
            ->success()
            ->title('Done')
            ->body('You have been logged out from other browser sessions.')
            ->send();
    }

    public static function browserSessions(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', filament()->auth()->user()->getAuthIdentifier())
            ->orderByDesc('last_activity')
            ->get()
            ->map(fn($session) => (object) [
                'agent' => Agent::fromUserAgent($session->user_agent),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ]);
    }

    public function render()
    {
        return view('livewire.profile.logout-other-browser-sessions');
    }
}
