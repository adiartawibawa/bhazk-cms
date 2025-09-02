<?php

namespace App\Livewire\Profile;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeleteAccount extends Component implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Delete Account')
                    ->description('Permanently delete your account and all associated data.')
                    ->aside()
                    ->icon('heroicon-o-user-minus')
                    ->schema([
                        Placeholder::make('deleteAccountNotice')
                            ->hiddenLabel()
                            ->content('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'),

                        Actions::make([
                            Action::make('deleteAccount')
                                ->label('Delete Account')
                                ->color('danger')
                                ->requiresConfirmation()
                                ->modalHeading('Delete Account')
                                ->modalDescription('Are you sure you want to delete your account? This action cannot be undone.')
                                ->modalSubmitActionLabel('Delete Account')
                                ->modalCancelAction(false)
                                ->form([
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->label('Password')
                                        ->required()
                                        ->currentPassword(),
                                ])
                                ->action(fn(array $data) => $this->deleteAccount()),
                        ])->alignLeft(),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Delete the current user.
     */
    protected function deleteAccount(): Redirector | RedirectResponse
    {
        $user = Auth::user();

        DB::transaction(function () use ($user) {

            $user->delete();
        });

        Auth::logout();

        Notification::make()
            ->success()
            ->title('Account Deleted')
            ->body('Your account has been deleted successfully.')
            ->send();

        return redirect(filament()->getLoginUrl());
    }

    public function render()
    {
        return view('livewire.profile.delete-account');
    }
}
