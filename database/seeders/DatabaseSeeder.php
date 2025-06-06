<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear roles principales
        $roles = [
            'SuperAdmin',
            'Administrador',
            'Administrador de Empresa',
            'Encargado',
            'Empleado'
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear permisos básicos
        $permissions = [
            'view_users',
            'edit_users',
            'create_users',
            'delete_users',
            'view_roles',
            'edit_roles',
            'view_logs',
            'view_companies'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos a SuperAdmin
        $superAdminRole = Role::findByName('SuperAdmin');
        $superAdminRole->syncPermissions(Permission::all());

        // Crear usuario SuperAdmin
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);
        $superAdmin->assignRole('SuperAdmin');

        // Crear usuario Administrador
        $admin = User::firstOrCreate([
            'email' => 'administrador@control-horario.com'
        ], [
            'name' => 'Administrador',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);
        $admin->assignRole('Administrador');

        // Crear usuario Administrador de Empresa
        $empresaAdmin = User::firstOrCreate([
            'email' => 'empresa@control-horario.com'
        ], [
            'name' => 'Empresa',
            'password' => bcrypt('password'),
            'email_verified_at' => now()
        ]);
        $empresaAdmin->assignRole('Administrador de Empresa');
    }
}