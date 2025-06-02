<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">Gestión de Roles y Permisos</h2>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <label class="block font-medium mb-1">Seleccionar Usuario</label>
        <select wire:model="selectedUserId" class="w-full border p-2 rounded">
            <option value="">-- Seleccionar --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>

    @if ($selectedUserId)
        <div class="mb-4">
            <label class="block font-medium mb-1">Roles</label>
            @foreach ($roles as $id => $role)
    <div class="flex items-center mb-1">
        <input type="checkbox" wire:model="userRoles" value="{{ $id }}" class="mr-2">
        <span>{{ $role }}</span>
    </div>
@endforeach
        </div>

        <div class="mb-4">
            <label class="block font-medium mb-1">Permisos</label>
            @foreach ($permissions as $id => $permission)
    <div class="flex items-center mb-1">
        <input type="checkbox" wire:model="userPermissions" value="{{ $id }}" class="mr-2">
        <span>{{ $permission }}</span>
    </div>
@endforeach
        </div>

        <button wire:click="save" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Guardar
        </button>
    @endif
</div>
