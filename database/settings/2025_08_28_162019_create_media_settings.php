<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = (new \App\Settings\MediaSettings())->defaults();

        foreach ($defaults as $key => $value) {
            $this->migrator->add("media.{$key}", $value);
        }
    }

    public function down(): void
    {
        $defaults = (new \App\Settings\MediaSettings())->defaults();

        foreach (array_keys($defaults) as $key) {
            $this->migrator->delete("media.{$key}");
        }
    }
};
