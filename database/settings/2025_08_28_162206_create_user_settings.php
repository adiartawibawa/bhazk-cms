<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = (new \App\Settings\UserSettings())->defaults();

        foreach ($defaults as $key => $value) {
            $this->migrator->add("user.{$key}", $value);
        }
    }

    public function down(): void
    {
        $defaults = (new \App\Settings\UserSettings())->defaults();

        foreach (array_keys($defaults) as $key) {
            $this->migrator->delete("user.{$key}");
        }
    }
};
