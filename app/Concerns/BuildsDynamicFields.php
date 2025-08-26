<?php

namespace App\Concerns;

use App\Models\ContentType;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

trait BuildsDynamicFields
{
    protected static function mapField(array $field): Component
    {
        $path = "body." . $field['name'];
        $label = $field['label'] ?? Str::headline($field['name']);
        $rules = $field['rules'] ?? null;

        $applyRules = function ($component) use ($rules) {
            if ($rules) {
                foreach (explode('|', $rules) as $rule) {
                    $component = $component->rule($rule);
                }
            }
            return $component;
        };

        return match ($field['type']) {
            'text' => $applyRules(
                TextInput::make($path)
                    ->label($label)
                    ->placeholder($field['placeholder'] ?? null)
                    ->default($field['default'] ?? null)
            ),
            'textarea' => $applyRules(
                Textarea::make($path)
                    ->label($label)
                    ->placeholder($field['placeholder'] ?? null)
                    ->default($field['default'] ?? null)
                    ->rows(4)
            ),
            'richtext' => $applyRules(
                RichEditor::make($path)
                    ->label($label)
                    ->default($field['default'] ?? null)
            ),
            'markdown' => $applyRules(
                MarkdownEditor::make($path)
                    ->label($label)
                    ->default($field['default'] ?? null)
            ),
            'number' => $applyRules(
                TextInput::make($path)
                    ->label($label)
                    ->numeric()
                    ->default($field['default'] ?? null)
            ),
            'boolean' => Toggle::make($path)
                ->label($label)
                ->default((bool) ($field['default'] ?? false)),
            'date' => DatePicker::make($path)
                ->label($label)
                ->default($field['default'] ?? null),
            'datetime' => DateTimePicker::make($path)
                ->label($label)
                ->default($field['default'] ?? null),
            'image' => FileUpload::make($path)
                ->label($label)
                ->image()
                ->directory($field['directory'] ?? 'uploads/images')
                ->multiple((bool) ($field['multiple'] ?? false)),
            'file' => FileUpload::make($path)
                ->label($label)
                ->directory($field['directory'] ?? 'uploads/files')
                ->multiple((bool) ($field['multiple'] ?? false)),
            'gallery' => FileUpload::make($path)
                ->label($label)
                ->image()
                ->directory($field['directory'] ?? 'uploads/gallery')
                ->multiple(true),
            'select' => (function () use ($path, $label, $field, $rules, $applyRules) {
                $component = Select::make($path)
                    ->label($label)
                    ->options($field['options'] ?? [])
                    ->searchable()
                    ->multiple((bool) ($field['multiple'] ?? false));

                return $applyRules($component);
            })(),
            'tags' => (function () use ($path, $label, $field) {
                $component = TagsInput::make($path)->label($label);
                if (!empty($field['options'])) {
                    $component->suggestions(array_values($field['options']));
                }
                return $component;
            })(),
            'repeater' => (function () use ($path, $label, $field) {
                $nested = collect($field['schema'] ?? [])->map(function ($nestedField) {
                    return self::mapField(array_merge($nestedField, [
                        'name' => $nestedField['name'],
                        'is_nested' => true
                    ]));
                })->toArray();

                return Repeater::make($path)
                    ->label($label)
                    ->schema($nested)
                    ->collapsible();
            })(),
            default => TextInput::make($path)->label($label),
        };
    }

    protected static function buildDynamicSchema(?ContentType $type): array
    {
        if (!$type) {
            return [];
        }

        return collect($type->fields ?? [])
            ->map(fn($field) => self::mapField($field))
            ->toArray();
    }
}
