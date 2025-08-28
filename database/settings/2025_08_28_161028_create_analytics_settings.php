<?php

use App\Settings\AnalyticsSettings;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $defaults = (new \App\Settings\AnalyticsSettings())->defaults();

        foreach ($defaults as $key => $value) {
            $this->migrator->add("analytics.{$key}", $value);
        }
    }

    public function down(): void
    {
        $defaults = (new \App\Settings\AnalyticsSettings())->defaults();

        foreach (array_keys($defaults) as $key) {
            $this->migrator->delete("analytics.{$key}");
        }
    }
};
