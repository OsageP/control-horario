<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\AuditLog;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionManager extends Component
{
    public $roles;
    public $permissions;
    public $selectedRole = null;
    public $selectedPermissions = [];

    public function mount()
    {
        $this->roles = Role::all(); // corregido aquí
        $this->permissions = Permission::all();
    }

    public function updatedSelectedRole($value)
    {
        $role = Role::findById($value);
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function savePermissions()
    {
        if (!auth()->user()->hasRole('SuperAdmin')) {
            abort(403);
        }

        $role = Role::findOrFail($this->selectedRole);
        $original = $role->permissions->pluck('name')->toArray();

        $role->syncPermissions($this->selectedPermissions);

        $changes = [
            'before' => $original,
            'after' => $this->selectedPermissions,
        ];

        AuditLog::create([
            'actor_id' => auth()->id(),
            'user_id' => null,
            'action' => 'update_permissions',
            'target_model' => Role::class,
            'target_id' => $role->id,
            'changes' => $changes,
        ]);

        session()->flash('success', 'Permisos actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.role-permission-manager');
    }
}
