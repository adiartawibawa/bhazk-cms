<?php

namespace App\Services\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class CmsType
{
    public string $key;
    public string $label;
    public ?string $icon = null;
    public ?string $color = null;
    public array $sub = [];

    public static function make(string $key): self
    {
        return (new self)->key($key);
    }

    public function key(string $key): self
    {
        $this->key = $key;

        // Auto-load dari config jika ada
        $configTypes = Config::get('cms-types.types', []);
        if (isset($configTypes[$key])) {
            $config = $configTypes[$key];

            $this->label(trans($config['label'] ?? $key));
            $this->icon($config['icon'] ?? null);
            $this->color($config['color'] ?? null);

            if (isset($config['sub']) && is_array($config['sub'])) {
                $subTypes = [];
                foreach ($config['sub'] as $subKey => $subConfig) {
                    $subTypes[] = (new self())
                        ->key($subKey)
                        ->label(trans($subConfig['label'] ?? $subKey))
                        ->icon($subConfig['icon'] ?? null)
                        ->color($subConfig['color'] ?? null);
                }
                $this->sub($subTypes);
            }
        } else {
            // Fallback jika tidak ada di config
            $this->label(Str::of($key)->title()->toString());
        }

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function icon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function color(?string $color): self
    {
        $this->color = $color;
        return $this;
    }

    public function sub(array $sub): self
    {
        $this->sub = $sub;
        return $this;
    }

    public function getSub(): Collection
    {
        return collect($this->sub);
    }

    public function toArray()
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'icon' => $this->icon,
            'color' => $this->color,
            'sub' => $this->sub,
        ];
    }
}
