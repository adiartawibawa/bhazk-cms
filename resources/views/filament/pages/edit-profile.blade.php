@php
    use Laravel\Fortify\Features;
@endphp

<x-filament-panels::page>
    @if (Features::enabled(Features::updateProfileInformation()))
        @livewire(App\Livewire\Profile\UpdateProfileInformation::class)
    @endif

    @if (Features::enabled(Features::updatePasswords()))
        @livewire(App\Livewire\Profile\UpdatePassword::class)
    @endif

    @livewire(App\Livewire\Profile\LogoutOtherBrowserSessions::class)

    @livewire(App\Livewire\Profile\DeleteAccount::class)

</x-filament-panels::page>
