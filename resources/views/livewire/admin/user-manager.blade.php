<div class="w-full max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-6">{{ $editMode ? 'Editar Usuario' : 'Crear Usuario' }}</h2>

    {{-- Mensajes --}}
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Formulario --}}
    <form wire:submit.prevent="createUser" class="space-y-4 mb-8">
        <div>
            <label class="block font-medium mb-1">Nombre</label>
            <input type="text" wire:model.defer="name" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Correo electrónico</label>
            <input type="email" wire:model.defer="email" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Contraseña {{ $editMode ? '(opcional)' : '' }}</label>
            <input type="password" wire:model.defer="password" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" />
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Rol</label>
            <select wire:model.defer="role" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-200">
                <option value="">Seleccionar rol</option>
                @foreach ($availableRoles as $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                @endforeach
            </select>
            @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex flex-wrap gap-2 pt-4">
            <button 
                type="submit" 
                class="bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1"
            >
                {{ $editMode ? 'Actualizar Usuario' : 'Crear Usuario' }}
            </button>

            @if ($editMode)
                <button 
                    type="button" 
                    wire:click="resetForm" 
                    class="bg-gray-300 text-gray-800 font-semibold px-4 py-2 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1"
                >
                    Cancelar
                </button>
            @endif
        </div>
    </form>

    {{-- Búsqueda --}}
    <div class="mb-6">
        <input 
            type="text" 
            wire:model.debounce.500ms="search" 
            placeholder="Buscar usuario..." 
            class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-blue-200" 
        />
    </div>

    {{-- Tabla --}}
    <h2 class="text-xl font-bold mb-4">Usuarios Registrados</h2>

    <div class="overflow-auto">
        <table class="w-full text-left border border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2 cursor-pointer" wire:click="sortBy('name')">Nombre</th>
                    <th class="border p-2 cursor-pointer" wire:click="sortBy('email')">Correo</th>
                    <th class="border p-2 cursor-pointer" wire:click="sortBy('company_id')">Empresa</th>
                    <th class="border p-2">Rol</th>
                    <th class="border p-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">{{ $user->company?->name ?? '-' }}</td>
                        <td class="border p-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                        <td class="border p-2 space-x-2">
                            <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:underline">Editar</button>
                            <button wire:click="delete({{ $user->id }})" onclick="return confirm('¿Eliminar este usuario?')" class="text-red-600 hover:underline">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border p-2 text-center text-gray-500">No hay usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
