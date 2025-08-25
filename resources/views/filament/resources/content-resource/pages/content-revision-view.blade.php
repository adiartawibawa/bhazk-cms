<x-filament-panels::page>
    <x-filament-panels::header :title="'Revision v' . $revision->version" :description="'Viewing details of this content revision'" />

    <div
        class="fi-section fi-section-with-header divide-y divide-gray-200 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        <!-- Header Information -->
        <div class="fi-section-header-ctn p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-user" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Author</p>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $revision->author->username ?? 'System' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-calendar" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</p>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ $revision->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-tag" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Change Type</p>
                        <x-filament::badge :color="match ($revision->change_type) {
                            'created' => 'primary',
                            'update' => 'success',
                            'rollback' => 'warning',
                            'status_change' => 'info',
                            default => 'gray',
                        }" :label="ucfirst(str_replace('_', ' ', $revision->change_type))" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Description -->
        @if ($revision->change_description)
            <div class="p-6">
                <x-filament::section.heading>
                    Change Description
                </x-filament::section.heading>
                <div class="mt-2">
                    <div
                        class="fi-alert fi-color-info fi-size-md rounded-lg bg-info-50 px-4 py-3 ring-1 ring-inset ring-info-100 dark:bg-info-500/10 dark:ring-info-400/20">
                        <div class="flex gap-x-3">
                            <x-filament::icon icon="heroicon-o-information-circle"
                                class="h-5 w-5 text-info-600 dark:text-info-400" />
                            <div class="flex-1">
                                <p class="text-sm text-info-700 dark:text-info-400">
                                    {{ $revision->change_description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content Details -->
        <div class="p-6">
            <x-filament::section.heading>
                Content Details
            </x-filament::section.heading>

            <div class="mt-4 grid grid-cols-1 gap-4">
                <!-- Title -->
                <div>
                    <x-filament::fieldset.label>
                        Title
                    </x-filament::fieldset.label>
                    <div class="fi-fieldset-content-ctn mt-1">
                        <div class="fi-input-wrp">
                            <div class="fi-input-container">
                                <div class="fi-field fi-field-w-fi-text-input fi-field-size-md">
                                    <div class="fi-field-container">
                                        <div class="fi-field-content">
                                            <div class="fi-field-input-wrp">
                                                <div class="fi-input-block">
                                                    <p class="text-gray-900 dark:text-white">
                                                        {{ $revision->title }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Excerpt -->
                @if ($revision->excerpt)
                    <div>
                        <x-filament::fieldset.label>
                            Excerpt
                        </x-filament::fieldset.label>
                        <div class="fi-fieldset-content-ctn mt-1">
                            <div class="fi-input-wrp">
                                <div class="fi-input-container">
                                    <div class="fi-field fi-field-w-fi-textarea fi-field-size-md">
                                        <div class="fi-field-container">
                                            <div class="fi-field-content">
                                                <div class="fi-field-input-wrp">
                                                    <div class="fi-input-block">
                                                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">
                                                            {{ $revision->excerpt }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Content Body -->
                @if ($revision->body)
                    <div>
                        <x-filament::fieldset.label>
                            Content Body
                        </x-filament::fieldset.label>
                        <div class="fi-fieldset-content-ctn mt-1">
                            <div class="fi-input-wrp">
                                <div class="fi-input-container">
                                    <div class="fi-field fi-field-w-fi-rich-editor fi-field-size-md">
                                        <div class="fi-field-container">
                                            <div class="fi-field-content">
                                                <div class="fi-field-input-wrp">
                                                    <div class="fi-input-block prose max-w-none dark:prose-invert">
                                                        {!! $revision->body !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metadata -->
        @if ($revision->metadata && count($revision->metadata) > 0)
            <div class="p-6">
                <x-filament::section.heading>
                    Metadata
                </x-filament::section.heading>

                <div class="mt-4">
                    <div class="fi-fieldset fi-fieldset-size-md">
                        <div class="fi-fieldset-content">
                            <div
                                class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                                <table class="w-full divide-y divide-gray-200 dark:divide-white/10">
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        @foreach ($revision->metadata as $key => $value)
                                            <tr class="transition duration-75 hover:bg-gray-50 dark:hover:bg-white/5">
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-950 dark:text-white">
                                                    {{ $key }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                                    @if (is_array($value))
                                                        <pre class="text-xs bg-gray-50 p-2 rounded dark:bg-gray-800">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Diff Summary -->
        @if ($revision->diff_summary && count($revision->diff_summary) > 0)
            <div class="p-6">
                <x-filament::section.heading>
                    Changes Summary
                </x-filament::section.heading>

                <div class="mt-4 space-y-4">
                    @foreach ($revision->diff_summary as $field => $change)
                        <div class="fi-fieldset fi-fieldset-size-md">
                            <x-filament::fieldset.label>
                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                            </x-filament::fieldset.label>

                            <div class="mt-2 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <!-- Old Value -->
                                <div>
                                    <div class="fi-fieldset-content-ctn">
                                        <div class="fi-input-wrp">
                                            <div class="fi-input-container">
                                                <div class="fi-field fi-field-w-fi-text-input fi-field-size-md">
                                                    <x-filament::fieldset.label size="sm">
                                                        Old Value
                                                    </x-filament::fieldset.label>
                                                    <div class="fi-field-container">
                                                        <div class="fi-field-content">
                                                            <div class="fi-field-input-wrp">
                                                                <div
                                                                    class="fi-input-block bg-danger-50 border border-danger-200 rounded-lg p-3 dark:bg-danger-500/10 dark:border-danger-500/20">
                                                                    <pre class="text-xs text-danger-800 dark:text-danger-400 whitespace-pre-wrap overflow-auto">
@if (is_array($change['old']))
{{ json_encode($change['old'], JSON_PRETTY_PRINT) }}
@else
{{ $change['old'] ?? 'null' }}
@endif
                                                            </pre>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- New Value -->
                                <div>
                                    <div class="fi-fieldset-content-ctn">
                                        <div class="fi-input-wrp">
                                            <div class="fi-input-container">
                                                <div class="fi-field fi-field-w-fi-text-input fi-field-size-md">
                                                    <x-filament::fieldset.label size="sm">
                                                        New Value
                                                    </x-filament::fieldset.label>
                                                    <div class="fi-field-container">
                                                        <div class="fi-field-content">
                                                            <div class="fi-field-input-wrp">
                                                                <div
                                                                    class="fi-input-block bg-success-50 border border-success-200 rounded-lg p-3 dark:bg-success-500/10 dark:border-success-500/20">
                                                                    <pre class="text-xs text-success-800 dark:text-success-400 whitespace-pre-wrap overflow-auto">
@if (is_array($change['new']))
{{ json_encode($change['new'], JSON_PRETTY_PRINT) }}
@else
{{ $change['new'] ?? 'null' }}
@endif
                                                            </pre>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Technical Information -->
        <div class="p-6">
            <x-filament::section.heading>
                Technical Information
            </x-filament::section.heading>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-identification" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Revision ID</p>
                        <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $revision->id }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-document-text" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Content ID</p>
                        <p class="text-sm font-mono text-gray-900 dark:text-white">{{ $revision->content_id }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-cloud" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Autosave</p>
                        <x-filament::badge :color="$revision->is_autosave ? 'success' : 'gray'" :label="$revision->is_autosave ? 'Yes' : 'No'" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-arrow-path" class="h-5 w-5 text-gray-400" />
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Version</p>
                        <x-filament::badge color="primary" :label="'v' . $revision->version" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .prose {
            max-width: none;
        }

        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4,
        .prose h5,
        .prose h6 {
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            font-weight: 600;
        }

        .prose p {
            margin-bottom: 1em;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1em;
            padding-left: 1.5em;
        }

        .prose li {
            margin-bottom: 0.5em;
        }

        .prose table {
            width: 100%;
            margin-bottom: 1em;
            border-collapse: collapse;
        }

        .prose table th,
        .prose table td {
            padding: 0.5em;
            border: 1px solid #e5e7eb;
        }

        .prose table th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        .dark .prose table th {
            background-color: #374151;
            border-color: #4b5563;
        }

        .dark .prose table td {
            border-color: #4b5563;
        }
    </style>
</x-filament-panels::page>
