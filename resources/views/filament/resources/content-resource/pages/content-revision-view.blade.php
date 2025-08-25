<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="border-b pb-4">
            <h2 class="text-xl font-bold text-gray-900">
                Revision v{{ $revision->version }}
            </h2>
            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ $revision->author->username ?? 'System' }}</span>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ $revision->created_at->format('M d, Y H:i') }}</span>
                </div>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $revision->change_type === 'created' ? 'blue' : ($revision->change_type === 'update' ? 'green' : ($revision->change_type === 'rollback' ? 'purple' : 'yellow')) }}-100 text-{{ $revision->change_type === 'created' ? 'blue' : ($revision->change_type === 'update' ? 'green' : ($revision->change_type === 'rollback' ? 'purple' : 'yellow')) }}-800">
                    {{ ucfirst(str_replace('_', ' ', $revision->change_type)) }}
                </span>
            </div>
        </div>

        <!-- Change Description -->
        @if ($revision->change_description)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Change Description</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>{{ $revision->change_description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Title Section -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
            <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                <p class="text-gray-900">{{ $revision->title }}</p>
            </div>
        </div>

        <!-- Excerpt Section -->
        @if ($revision->excerpt)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                    <p class="text-gray-900">{{ $revision->excerpt }}</p>
                </div>
            </div>
        @endif

        <!-- Content Body Section -->
        @if ($revision->body)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Content Body</label>
                <div class="bg-white border border-gray-200 rounded-md p-4 prose max-w-none">
                    {!! $revision->body !!}
                </div>
            </div>
        @endif

        <!-- Metadata Section -->
        @if ($revision->metadata && count($revision->metadata) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metadata</label>
                <div class="bg-gray-50 border border-gray-200 rounded-md p-3 overflow-hidden">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($revision->metadata as $key => $value)
                            <div class="flex border-b border-gray-100 py-2 last:border-b-0">
                                <span class="font-medium text-gray-600 w-1/4">{{ $key }}:</span>
                                <span class="text-gray-900 flex-1">
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
            </div>
        @endif

        <!-- Diff Summary (Jika ada) -->
        @if ($revision->diff_summary && count($revision->diff_summary) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Changes Summary</label>
                <div class="space-y-3">
                    @foreach ($revision->diff_summary as $field => $change)
                        <div class="border border-gray-200 rounded-md p-3">
                            <h4 class="font-medium text-gray-900 mb-2">{{ ucfirst(str_replace('_', ' ', $field)) }}
                            </h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Old Value</label>
                                    <div class="bg-red-50 border border-red-200 rounded p-2">
                                        <pre class="text-xs text-red-800 whitespace-pre-wrap">
@if (is_array($change['old']))
{{ json_encode($change['old'], JSON_PRETTY_PRINT) }}@else{{ $change['old'] }}
@endif
</pre>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1">New Value</label>
                                    <div class="bg-green-50 border border-green-200 rounded p-2">
                                        <pre class="text-xs text-green-800 whitespace-pre-wrap">
@if (is_array($change['new']))
{{ json_encode($change['new'], JSON_PRETTY_PRINT) }}@else{{ $change['new'] }}
@endif
</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Technical Info -->
        <div class="border-t pt-4">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Technical Information</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Revision ID:</span>
                    <span class="text-gray-900 block font-mono text-xs">{{ $revision->id }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Content ID:</span>
                    <span class="text-gray-900 block font-mono text-xs">{{ $revision->content_id }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Autosave:</span>
                    <span class="text-gray-900">{{ $revision->is_autosave ? 'Yes' : 'No' }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Version:</span>
                    <span class="text-gray-900">v{{ $revision->version }}</span>
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
    </style>
</x-filament-panels::page>
