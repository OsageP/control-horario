<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Crear empresa demo si no existe
        $empresa = Company::firstOrCreate(['name' => 'Empresa Demo']);

        // Definir permisos base
        $permissions = [
            'view companies', 'create companies', 'edit companies', 'delete companies',
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'assign roles', 'edit permissions',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Crear roles y asignar permisos
        $roles = ['SuperAdmin', 'Administrador de Empresa', 'Encargado', 'Empleado'];
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if ($roleName === 'SuperAdmin') {
                $role->syncPermissions(Permission::all());
            }
        }

        // Crear usuario admin y asignarle rol
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'company_id' => $empresa->id,
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['SuperAdmin']);
    }
}
