<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <x-filament::section>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Revision v{{ $revision->version }}
            </h2>
            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center">
                    <x-filament::icon name="heroicon-o-user" class="w-4 h-4 mr-1" />
                    <span>{{ $revision->author->username ?? 'System' }}</span>
                </div>
                <div class="flex items-center">
                    <x-filament::icon name="heroicon-o-calendar" class="w-4 h-4 mr-1" />
                    <span>{{ $revision->created_at->format('M d, Y H:i') }}</span>
                </div>
                <x-filament::badge size="sm" :color="$revision->change_type === 'created' ? 'info' : ($revision->change_type === 'update' ? 'success' : ($revision->change_type === 'rollback' ? 'purple' : 'warning'))">
                    {{ ucfirst(str_replace('_', ' ', $revision->change_type)) }}
                </x-filament::badge>
            </div>
        </x-filament::section>

        <!-- Change Description -->
        @if ($revision->change_description)
            <x-filament::section>
                <div class="flex">
                    <x-filament::icon name="heroicon-o-information-circle" class="w-5 h-5 text-primary-500" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-primary-700 dark:text-primary-400">
                            Change Description
                        </p>
                        <div class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                            <p>{{ $revision->change_description }}</p>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

        <!-- Title Section -->
        <x-filament::section>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</p>
            <div class="mt-1 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-3">
                <p class="text-gray-900 dark:text-gray-100">{{ $revision->title }}</p>
            </div>
        </x-filament::section>

        <!-- Excerpt Section -->
        @if ($revision->excerpt)
            <x-filament::section>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Excerpt</p>
                <div
                    class="mt-1 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-3">
                    <p class="text-gray-900 dark:text-gray-100">{{ $revision->excerpt }}</p>
                </div>
            </x-filament::section>
        @endif

        <!-- Content Body Section -->
        @if ($revision->body)
            <x-filament::section>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Content Body</p>
                <div
                    class="mt-2 border border-gray-200 dark:border-gray-700 rounded-md p-4 prose dark:prose-invert max-w-none">
                    {!! $revision->body !!}
                </div>
            </x-filament::section>
        @endif

        <!-- Metadata Section -->
        @if ($revision->metadata && count($revision->metadata) > 0)
            <x-filament::section>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Metadata</p>
                <div
                    class="mt-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md p-3">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($revision->metadata as $key => $value)
                            <div class="flex border-b border-gray-100 dark:border-gray-700 py-2 last:border-b-0">
                                <span
                                    class="font-medium text-gray-600 dark:text-gray-400 w-1/4">{{ $key }}:</span>
                                <span class="text-gray-900 dark:text-gray-100 flex-1">
                                    @if (is_array($value))
                                        {{ json_encode($value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </x-filament::section>
        @endif

        <!-- Diff Summary -->
        @if ($revision->diff_summary && count($revision->diff_summary) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Changes Summary</label>
                <div class="space-y-3">
                    @foreach ($revision->diff_summary as $field => $change)
                        <div class="border border-gray-200 rounded-md p-3">
                            <h4 class="font-medium text-gray-900 mb-2">
                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Old Value -->
                                <div class="flex flex-col">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Old Value</label>
                                    <div class="bg-red-50 border border-red-200 rounded p-2 overflow-x-auto">
                                        @php
                                            $oldValue = is_array($change['old'])
                                                ? json_encode(
                                                    $change['old'],
                                                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
                                                )
                                                : $change['old'];
                                        @endphp
                                        <pre class="text-xs text-red-800 whitespace-pre-wrap sm:whitespace-pre">{{ $oldValue }}</pre>
                                    </div>
                                </div>
                                <!-- New Value -->
                                <div class="flex flex-col">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">New Value</label>
                                    <div class="bg-green-50 border border-green-200 rounded p-2 overflow-x-auto">
                                        @php
                                            $newValue = is_array($change['new'])
                                                ? json_encode(
                                                    $change['new'],
                                                    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
                                                )
                                                : $change['new'];
                                        @endphp
                                        <pre class="text-xs text-green-800 whitespace-pre-wrap sm:whitespace-pre">{{ $newValue }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Technical Info -->
        <x-filament::section>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Technical Information</p>
            <div class="grid grid-cols-2 gap-4 text-sm mt-2">
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Revision ID:</span>
                    <span class="text-gray-900 dark:text-gray-100 block font-mono text-xs">{{ $revision->id }}</span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Content ID:</span>
                    <span
                        class="text-gray-900 dark:text-gray-100 block font-mono text-xs">{{ $revision->content_id }}</span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Autosave:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $revision->is_autosave ? 'Yes' : 'No' }}</span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-400">Version:</span>
                    <span class="text-gray-900 dark:text-gray-100">v{{ $revision->version }}</span>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
