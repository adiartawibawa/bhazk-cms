<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_activitylog","view_any_activitylog","create_activitylog","update_activitylog","restore_activitylog","restore_any_activitylog","replicate_activitylog","reorder_activitylog","delete_activitylog","delete_any_activitylog","force_delete_activitylog","force_delete_any_activitylog","view_category","view_any_category","create_category","update_category","restore_category","restore_any_category","replicate_category","reorder_category","delete_category","delete_any_category","force_delete_category","force_delete_any_category","view_content","view_any_content","create_content","update_content","restore_content","restore_any_content","replicate_content","reorder_content","delete_content","delete_any_content","force_delete_content","force_delete_any_content","view_content::type","view_any_content::type","create_content::type","update_content::type","restore_content::type","restore_any_content::type","replicate_content::type","reorder_content::type","delete_content::type","delete_any_content::type","force_delete_content::type","force_delete_any_content::type","view_faq","view_any_faq","create_faq","update_faq","restore_faq","restore_any_faq","replicate_faq","reorder_faq","delete_faq","delete_any_faq","force_delete_faq","force_delete_any_faq","view_faq::category","view_any_faq::category","create_faq::category","update_faq::category","restore_faq::category","restore_any_faq::category","replicate_faq::category","reorder_faq::category","delete_faq::category","delete_any_faq::category","force_delete_faq::category","force_delete_any_faq::category","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_ticket","view_any_ticket","create_ticket","update_ticket","restore_ticket","restore_any_ticket","replicate_ticket","reorder_ticket","delete_ticket","delete_any_ticket","force_delete_ticket","force_delete_any_ticket","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
