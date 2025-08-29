<x-filament-panels::page>
    {{-- Bagian Form Profil (bawaan BaseEditProfile::form) --}}
    {{ $this->form }}

    {{-- Update Password --}}
    <x-filament::section heading="Update Password"
        description="Ensure your account is using a long, random password to stay secure." class="mt-6">
        <form wire:submit.prevent="updatePassword" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-filament::input type="password" wire:model.defer="passwordData.current_password"
                    placeholder="Current Password" :label="__('Current Password')" autocomplete="current-password" required />
                <x-filament::input type="password" wire:model.defer="passwordData.password" placeholder="New Password"
                    :label="__('New Password')" autocomplete="new-password" required />
                <x-filament::input type="password" wire:model.defer="passwordData.password_confirmation"
                    placeholder="Confirm New Password" :label="__('Confirm Password')" autocomplete="new-password" required />
            </div>

            <x-filament::button type="submit" class="mt-4">
                {{ __('Save') }}
            </x-filament::button>
        </form>
    </x-filament::section>

    {{-- Two Factor Authentication --}}
    <x-filament::section heading="Two Factor Authentication"
        description="Add additional security to your account using two factor authentication." class="mt-6">
        @if (\Laravel\Fortify\Features::enabled(\Laravel\Fortify\Features::twoFactorAuthentication()))
            @if ($this->twoFactorEnabled)
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">
                        {{ __('Two-factor authentication is enabled.') }}
                    </p>

                    @if ($this->twoFactorQrSvg)
                        <div>
                            <p class="text-sm font-medium">
                                {{ __('Scan this QR code with your authenticator app:') }}
                            </p>
                            <div class="mt-2 p-3 inline-block bg-white rounded-md shadow">
                                {!! $this->twoFactorQrSvg !!}
                            </div>
                        </div>
                    @endif

                    @if (count($this->recoveryCodes))
                        <div class="mt-4">
                            <p class="text-sm font-medium">{{ __('Recovery Codes') }}</p>
                            <ul class="mt-2 grid grid-cols-1 gap-2 md:grid-cols-2">
                                @foreach ($this->recoveryCodes as $code)
                                    <li class="font-mono text-sm p-2 rounded bg-gray-100">
                                        {{ $code }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex gap-3 mt-4">
                        <x-filament::button wire:click="regenerateRecoveryCodes" color="gray">
                            {{ __('Regenerate Recovery Codes') }}
                        </x-filament::button>

                        <x-filament::button wire:click="disableTwoFactor" color="danger">
                            {{ __('Disable') }}
                        </x-filament::button>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        {{ __('You have not enabled two-factor authentication.') }}
                    </p>
                    <x-filament::button wire:click="enableTwoFactor">
                        {{ __('Enable') }}
                    </x-filament::button>
                </div>
            @endif
        @else
            <p class="text-sm text-gray-600">
                {{ __('Two-factor authentication is not enabled in Fortify configuration.') }}
            </p>
        @endif
    </x-filament::section>

    {{-- Browser Sessions --}}
    <x-filament::section heading="Browser Sessions"
        description="Manage and log out your active sessions on other browsers and devices." class="mt-6">
        <form wire:submit.prevent="logoutOtherSessions" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <x-filament::input type="password" wire:model.defer="sessionsData.password"
                    placeholder="Current Password" :label="__('Current Password')" autocomplete="current-password" required />
            </div>

            <x-filament::button type="submit" color="warning" class="mt-4">
                {{ __('Log Out Other Browser Sessions') }}
            </x-filament::button>
        </form>
    </x-filament::section>

    {{-- Delete Account --}}
    <x-filament::section heading="Delete Account" description="Permanently delete your account." class="mt-6">
        <form wire:submit.prevent="deleteAccount" class="space-y-4">
            <p class="text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mt-4">
                <x-filament::input type="password" wire:model.defer="deleteData.password" placeholder="Current Password"
                    :label="__('Current Password')" autocomplete="current-password" required />
            </div>

            <x-filament::button type="submit" color="danger" class="mt-4">
                {{ __('Delete Account') }}
            </x-filament::button>
        </form>
    </x-filament::section>
</x-filament-panels::page>
