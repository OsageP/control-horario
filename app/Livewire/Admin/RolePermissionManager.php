<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AuditLog;

class RolePermissionManager extends Component
{
    public $users = [];
    public $roles = [];
    public $permissions = [];

    public $selectedUserId = null;
    public $userRoles = [];
    public $userPermissions = [];

    public function mount()
    {
        $this->users = User::all();
        $this->roles = Role::pluck('name')->toArray();
        $this->permissions = Permission::pluck('name')->toArray();
    }

    public function updatedSelectedUserId($value)
    {
        $user = User::find($value);
        $this->userRoles = $user->roles->pluck('name')->toArray();
        $this->userPermissions = $user->permissions->pluck('name')->toArray();
    }

    public function save()
    {
        if (!$this->selectedUserId) return;

        $user = User::findOrFail($this->selectedUserId);

        $oldRoles = $user->getRoleNames()->toArray();
        $oldPermissions = $user->getPermissionNames()->toArray();

        $user->syncRoles($this->userRoles);
        $user->syncPermissions($this->userPermissions);

        AuditLog::create([
            'actor_id' => auth()->id(),
            'user_id' => $user->id,
            'action' => 'update_roles_permissions',
            'target_model' => User::class,
            'target_id' => $user->id,
            'changes' => [
                'roles_before' => $oldRoles,
                'roles_after' => $this->userRoles,
                'permissions_before' => $oldPermissions,
                'permissions_after' => $this->userPermissions,
            ],
        ]);

        session()->flash('success', 'Roles y permisos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}
