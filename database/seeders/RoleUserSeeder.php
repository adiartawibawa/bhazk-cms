<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat roles (tanpa super_admin)
        $roles = [
            'admin' => 'Administrator',
            'agent' => 'Support Agent',
            'customer' => 'Customer',
            'manager' => 'Support Manager',
            'content_editor' => 'Content Editor',
            'billing_specialist' => 'Billing Specialist',
        ];

        foreach ($roles as $name => $displayName) {
            Role::create([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }

        // Buat permissions untuk sistem ticketing
        $permissions = [
            // Ticket permissions
            'view_any_tickets' => 'View Any Tickets',
            'view_ticket' => 'View Ticket',
            'create_ticket' => 'Create Ticket',
            'update_ticket' => 'Update Ticket',
            'delete_ticket' => 'Delete Ticket',
            'restore_ticket' => 'Restore Ticket',
            'force_delete_ticket' => 'Force Delete Ticket',
            'assign_ticket' => 'Assign Ticket',
            'change_ticket_status' => 'Change Ticket Status',

            // Ticket message permissions
            'view_ticket_messages' => 'View Ticket Messages',
            'create_ticket_message' => 'Create Ticket Message',
            'update_ticket_message' => 'Update Ticket Message',
            'delete_ticket_message' => 'Delete Ticket Message',

            // Report permissions
            'view_ticket_reports' => 'View Ticket Reports',
            'export_ticket_reports' => 'Export Ticket Reports',

            // User management permissions
            'view_users' => 'View Users',
            'create_users' => 'Create Users',
            'update_users' => 'Update Users',
            'delete_users' => 'Delete Users',

            // Settings permissions
            'manage_ticket_settings' => 'Manage Ticket Settings',
            'manage_ticket_categories' => 'Manage Ticket Categories',
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::create([
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions ke roles
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::findByName('manager');
        $managerRole->givePermissionTo([
            'view_any_tickets',
            'view_ticket',
            'create_ticket',
            'update_ticket',
            'assign_ticket',
            'change_ticket_status',
            'view_ticket_messages',
            'create_ticket_message',
            'update_ticket_message',
            'view_ticket_reports',
            'export_ticket_reports',
            'view_users'
        ]);

        $agentRole = Role::findByName('agent');
        $agentRole->givePermissionTo([
            'view_any_tickets',
            'view_ticket',
            'update_ticket',
            'view_ticket_messages',
            'create_ticket_message',
            'update_ticket_message',
            'change_ticket_status'
        ]);

        $billingRole = Role::findByName('billing_specialist');
        $billingRole->givePermissionTo([
            'view_any_tickets',
            'view_ticket',
            'update_ticket',
            'view_ticket_messages',
            'create_ticket_message',
            'change_ticket_status'
        ]);

        // Buat users
        $users = [
            // Admin
            [
                'username' => 'admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'UTC',
                'email_verified_at' => now(),
            ],
            // Support Manager
            [
                'username' => 'manager',
                'first_name' => 'Support',
                'last_name' => 'Manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'UTC',
                'email_verified_at' => now(),
            ],
            // Support Agents
            [
                'username' => 'agent1',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'agent1@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'America/New_York',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'agent2',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'agent2@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'America/Los_Angeles',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'agent3',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'agent3@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'Europe/London',
                'email_verified_at' => now(),
            ],
            // Billing Specialist
            [
                'username' => 'billing',
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'billing@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'UTC',
                'email_verified_at' => now(),
            ],
            // Content Editor
            [
                'username' => 'editor',
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'editor@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'UTC',
                'email_verified_at' => now(),
            ],
            // Customers
            [
                'username' => 'customer1',
                'first_name' => 'Alice',
                'last_name' => 'Cooper',
                'email' => 'customer1@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'America/Chicago',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'customer2',
                'first_name' => 'Bob',
                'last_name' => 'Wilson',
                'email' => 'customer2@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'America/Denver',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'customer3',
                'first_name' => 'Charlie',
                'last_name' => 'Davis',
                'email' => 'customer3@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'Europe/Paris',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'customer4',
                'first_name' => 'Diana',
                'last_name' => 'Miller',
                'email' => 'customer4@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'Asia/Tokyo',
                'email_verified_at' => now(),
            ],
            [
                'username' => 'customer5',
                'first_name' => 'Edward',
                'last_name' => 'Taylor',
                'email' => 'customer5@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'timezone' => 'Australia/Sydney',
                'email_verified_at' => now(),
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['username' => $userData['username']], // cek unik berdasarkan username
                $userData
            );
            $createdUsers[$userData['username']] = $user;
        }

        // Assign roles ke users
        $createdUsers['admin']->assignRole('admin');
        $createdUsers['manager']->assignRole('manager');
        $createdUsers['agent1']->assignRole('agent');
        $createdUsers['agent2']->assignRole('agent');
        $createdUsers['agent3']->assignRole('agent');
        $createdUsers['billing']->assignRole('billing_specialist');
        $createdUsers['editor']->assignRole('content_editor');
        $createdUsers['customer1']->assignRole('customer');
        $createdUsers['customer2']->assignRole('customer');
        $createdUsers['customer3']->assignRole('customer');
        $createdUsers['customer4']->assignRole('customer');
        $createdUsers['customer5']->assignRole('customer');

        $this->command->info('Roles and users seeded successfully!');
        $this->command->info('Created ' . count($roles) . ' roles and ' . count($users) . ' users.');
    }
}
