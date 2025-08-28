<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = (new \App\Settings\ContentSettings())->defaults();

        foreach ($defaults as $key => $value) {
            $this->migrator->add("content.{$key}", $value);
        }
    }

    public function down(): void
    {
        $defaults = (new \App\Settings\ContentSettings())->defaults();

        foreach (array_keys($defaults) as $key) {
            $this->migrator->delete("content.{$key}");
        }
    }
};
