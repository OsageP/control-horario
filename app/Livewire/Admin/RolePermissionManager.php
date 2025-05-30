<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\AuditLog;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionManager extends Component
{
    public $users;
    public $roles;
    public $permissions;

    public $selectedUserId;
    public $userRoles = [];
    public $userPermissions = [];

    public function mount()
    {
        $this->users = User::all();
        $this->roles = Role::pluck('name')->toArray();
        $this->permissions = Permission::pluck('name')->toArray();
    }

    public function updatedSelectedUserId($userId)
{
    $user = User::find($userId);
    $this->userRoles = $user->roles->pluck('name')->toArray();
    $this->userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

    \Log::info('Usuario seleccionado:', [
        'id' => $user->id,
        'roles' => $this->userRoles,
        'permissions' => $this->userPermissions
    ]);
}

    public function save()
    {
        $user = User::findOrFail($this->selectedUserId);

        $originalRoles = $user->roles->pluck('name')->toArray();
        $originalPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        // Sync roles y permisos
        $user->syncRoles($this->userRoles);
        $user->syncPermissions($this->userPermissions);

        // Registrar en logs
        AuditLog::create([
            'actor_id' => auth()->id(),
            'user_id' => $user->id,
            'action' => 'update_roles_permissions',
            'target_model' => User::class,
            'target_id' => $user->id,
            'changes' => [
                'roles' => ['before' => $originalRoles, 'after' => $this->userRoles],
                'permissions' => ['before' => $originalPermissions, 'after' => $this->userPermissions],
            ],
        ]);

        session()->flash('success', 'Roles y permisos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}

