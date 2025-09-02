<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="text-sm mb-4">
        If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your
        recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been
        compromised, you should also update your password.
    </div>

    @if (count($sessions) > 0)
        <div class="space-y-6">
            <!-- Other Browser Sessions -->
            @foreach ($sessions as $session)
                <div class="flex items-center">
                    <div>
                        @if ($session->agent->isDesktop())
                            <x-filament::icon icon="heroicon-o-computer-desktop" class="w-8 h-8" />
                        @else
                            <x-filament::icon icon="heroicon-o-device-phone-mobile" class="w-8 h-8" />
                        @endif
                    </div>

                    <div class="ms-3">
                        <div class="text-sm">
                            {{ $session->agent->platform() ?: 'Unknown Device' }}
                            - {{ $session->agent->browser() ?: 'Unknown Device' }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ $session->ip_address }},

                                @if ($session->is_current_device)
                                    <span class="font-semibold">This Device</span>
                                @else
                                    Last active {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-dynamic-component>
