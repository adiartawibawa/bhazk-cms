<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jalankan ShieldSeeder terlebih dahulu
        $this->call(ShieldSeeder::class);

        // Buat roles default
        $roles = [
            'admin',
            'author',
            'contributor',
            'editor',
            'subscriber',
            'user',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // Buat user Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'super.admin@pradstudio.com'],
            [
                'username' => 'Super Administrator',
                'password' => Hash::make('fujiyama'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Buat user Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@bhazk.com'],
            [
                'username' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        // Buat user dengan role lainnya
        $roleUsers = [
            'author' => [
                'email' => 'author@cms.com',
                'username' => 'Content Author'
            ],
            'editor' => [
                'email' => 'editor@cms.com',
                'username' => 'Content Editor'
            ],
            'contributor' => [
                'email' => 'contributor@cms.com',
                'username' => 'Content Contributor'
            ],
            'subscriber' => [
                'email' => 'subscriber@cms.com',
                'username' => 'Newsletter Subscriber'
            ],
            'user' => [
                'email' => 'user@cms.com',
                'username' => 'Regular User'
            ]
        ];

        foreach ($roleUsers as $role => $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'username' => $userData['username'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $user->assignRole($role);
        }

        // Buat beberapa user tambahan untuk testing
        $this->createAdditionalUsers();

        $this->command->info('User & Role Seeding Completed.');
        $this->command->info('Super Admin: super.admin@pradstudio.com / loveofmylife');
        $this->command->info('Admin: admin@cms.com / password');
    }

    protected function createAdditionalUsers(): void
    {
        // Buat beberapa author tambahan
        for ($i = 1; $i <= 3; $i++) {
            $author = User::firstOrCreate(
                ['email' => "author{$i}@cms.com"],
                [
                    'username' => "Author {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $author->assignRole('author');
        }

        // Buat beberapa subscriber tambahan
        for ($i = 1; $i <= 5; $i++) {
            $subscriber = User::firstOrCreate(
                ['email' => "subscriber{$i}@cms.com"],
                [
                    'username' => "Subscriber {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $subscriber->assignRole('subscriber');
        }

        // Buat beberapa regular user
        for ($i = 1; $i <= 10; $i++) {
            $user = User::firstOrCreate(
                ['email' => "user{$i}@cms.com"],
                [
                    'username' => "Regular User {$i}",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('user');
        }
    }
}
