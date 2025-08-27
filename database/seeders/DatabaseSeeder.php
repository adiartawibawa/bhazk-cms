<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            ContentTypeSeeder::class,
            TicketingSystemSeeder::class,
            FaqsSeeder::class,
        ]);

        // Generate for all entities of filament shield
        $this->command->info('Generating all entities of filament shield...');
        Artisan::call('shield:generate --all --panel=backend');
        $this->command->info(Artisan::output());

        $this->command->info('Database seeding completed successfully!');
    }
}
