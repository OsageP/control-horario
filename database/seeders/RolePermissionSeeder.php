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
        // Crear empresa demo si no existe (asociación para usuarios, si aplica)
        $empresa = Company::firstOrCreate(['name' => 'Empresa Demo']);

        // Definir y crear permisos base de la aplicación
        $permissions = [
            'view companies', 'create companies', 'edit companies', 'delete companies',
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'assign roles', 'edit permissions',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Definir roles y asignar permisos (SuperAdmin obtiene todos los permisos)
        $roles = ['SuperAdmin', 'Administrador de Empresa', 'Encargado', 'Empleado'];
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if ($roleName === 'SuperAdmin') {
                // SuperAdmin tiene acceso total a todos los permisos
                $role->syncPermissions(Permission::all());
            }
            // Nota: Los demás roles inicialmente no se les asignan permisos aquí.
            // (Se pueden asignar luego vía la interfaz de Roles/Permisos según necesidades.)
        }

        // Crear un usuario administrador por defecto y asignarle el rol SuperAdmin
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'company_id' => $empresa->id,
            ]
        );
        $admin->syncRoles(['SuperAdmin']);
    }
}
