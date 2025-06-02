<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionManager extends Component
{
    public $selectedUserId;
    public $userRoles = [];
    public $userPermissions = [];
    public $roles = [];
    public $permissions = [];
    public $users = [];

    public function mount()
    {
        abort_unless(auth()->user()->can('view roles'), 403);

        $this->users = User::all();
        $this->roles = Role::pluck('name', 'id')->toArray();
        $this->permissions = Permission::pluck('name', 'id')->toArray();
    }

    public function updatedSelectedUserId($value)
    {
        if (!$value) {
            $this->userRoles = [];
            $this->userPermissions = [];
            return;
        }

        $user = User::find($value);
        $this->userRoles = $user->roles->pluck('id')->toArray();
        $this->userPermissions = $user->permissions->pluck('id')->toArray();
    }

    public function save()
    {
        abort_unless(auth()->user()->can('edit roles'), 403);

        $user = User::findOrFail($this->selectedUserId);

        $roleNames = Role::whereIn('id', $this->userRoles)->pluck('name')->toArray();
        $permissionNames = Permission::whereIn('id', $this->userPermissions)->pluck('name')->toArray();

        $originalRoles = $user->getRoleNames()->toArray();
        $originalPermissions = $user->getPermissionNames()->toArray();

        $user->syncRoles($roleNames);
        $user->syncPermissions($permissionNames);

        AuditLog::create([
            'actor_id' => auth()->id(),
            'user_id' => $user->id,
            'action' => 'update_user_roles_permissions',
            'target_model' => User::class,
            'target_id' => $user->id,
            'changes' => [
                'roles' => [
                    'before' => $originalRoles,
                    'after' => $roleNames,
                ],
                'permissions' => [
                    'before' => $originalPermissions,
                    'after' => $permissionNames,
                ],
            ],
        ]);

        session()->flash('success', 'Roles y permisos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}
