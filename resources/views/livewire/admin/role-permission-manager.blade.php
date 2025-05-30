<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Gestión de Roles y Permisos</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <label>Seleccionar Usuario</label>
        <select wire:model="selectedUserId" class="w-full border p-2 rounded">
            <option value="">-- Seleccionar --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>

    @if ($selectedUserId)
        <div class="mb-4">
            <label>Roles</label>
            @foreach ($roles as $role)
                <div>
                    <input type="checkbox" wire:model="userRoles" value="{{ $role }}"> {{ $role }}
                </div>
            @endforeach
        </div>

        <div class="mb-4">
            <label>Permisos</label>
            @foreach ($permissions as $permission)
                <div>
                    <input type="checkbox" wire:model="userPermissions" value="{{ $permission }}"> {{ $permission }}
                </div>
            @endforeach
        </div>

        <button wire:click="save" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Guardar</button>
    @endif
</div>
