<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    public $name;
    public $email;
    public $password;
    public $role;

    public $availableRoles;
    public $users;

    public $editMode = false;
    public $editUserId = null;

    public $search = '';
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        $this->availableRoles = Role::pluck('name')->toArray();
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $query = User::with('roles', 'company');

        if (!auth()->user()->hasRole('SuperAdmin')) {
            $query->where('company_id', auth()->user()->company_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        $this->users = $query->orderBy($this->sortColumn, $this->sortDirection)->get();
    }

    public function sortBy($column)
{
    if (!in_array($column, ['name', 'email', 'company_id'])) {
        return; // no ordenar por columnas no válidas
    }

    if ($this->sortColumn === $column) {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        $this->sortColumn = $column;
        $this->sortDirection = 'asc';
    }

    $this->loadUsers();
}

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $this->editUserId,
            'password' => $this->editMode ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required|in:' . implode(',', $this->availableRoles),
        ]);

        if ($this->editMode) {
            $user = User::findOrFail($this->editUserId);
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $user->syncRoles([$this->role]);

            session()->flash('success', 'Usuario actualizado con éxito.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'company_id' => auth()->user()->company_id,
            ]);
            $user->assignRole($this->role);
            session()->flash('success', 'Usuario creado con éxito.');
        }

        $this->resetForm();
        $this->loadUsers();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->pluck('name')->first();
        $this->editMode = true;
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propio usuario.');
            return;
        }

        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('success', 'Usuario eliminado.');
        $this->loadUsers();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role', 'editMode', 'editUserId']);
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.admin.user-manager');
    }
}
