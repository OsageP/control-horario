<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar la caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir permisos
        $permissions = [
            'view companies',
            'create companies',
            'edit companies',
            'delete companies',

            // Añade más permisos a medida que se expanden los módulos
            'manage users',
            'manage work entries',
            'view reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Crear roles y asignar permisos específicos
        $roles = [
            'SuperAdmin' => Permission::all()->pluck('name')->toArray(),
            'Administrador de Empresa' => [
                'view companies',
                'create companies',
                'edit companies',
                'delete companies',
                'manage users',
                'manage work entries',
                'view reports',
            ],
            'Encargado' => [
                'view companies',
                'manage work entries',
                'view reports',
            ],
            'Empleado' => [
                'view companies',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }

        // (Opcional) Asignar un rol por defecto al primer usuario
        $admin = User::find(1); // Modifica si ya tienes un usuario distinto
        if ($admin && !$admin->hasRole('SuperAdmin')) {
            $admin->assignRole('SuperAdmin');
        }
    }
}

