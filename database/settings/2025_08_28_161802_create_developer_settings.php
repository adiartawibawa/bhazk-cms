<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = (new \App\Settings\DeveloperSettings())->defaults();

        foreach ($defaults as $key => $value) {
            $this->migrator->add("developer.{$key}", $value);
        }
    }

    public function down(): void
    {
        $defaults = (new \App\Settings\DeveloperSettings())->defaults();

        foreach (array_keys($defaults) as $key) {
            $this->migrator->delete("developer.{$key}");
        }
    }
};
