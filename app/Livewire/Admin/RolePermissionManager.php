<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $this->roles = Role::pluck('name', 'id')->toArray(); // clave => valor
        $this->permissions = Permission::pluck('name', 'id')->toArray();
    }

    public function updatedSelectedUserId($value)
    {
        $user = User::find($value);
        if (!$user) {
            $this->userRoles = [];
            $this->userPermissions = [];
            return;
        }

        $this->userRoles = $user->roles->pluck('id')->toArray();
        $this->userPermissions = $user->permissions->pluck('id')->toArray();
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
                'roles_after' => Role::whereIn('id', $this->userRoles)->pluck('name')->toArray(),
                'permissions_before' => $oldPermissions,
                'permissions_after' => Permission::whereIn('id', $this->userPermissions)->pluck('name')->toArray(),
            ],
        ]);

        session()->flash('success', 'Roles y permisos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}
