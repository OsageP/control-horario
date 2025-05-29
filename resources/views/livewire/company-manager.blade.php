<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Gestión de Empresas</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="mb-6">
        <div class="mb-4">
            <label class="block text-gray-700">Nombre</label>
            <input type="text" wire:model="name" class="w-full px-3 py-2 border rounded" />
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" wire:model="email" class="w-full px-3 py-2 border rounded" />
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Dirección</label>
            <textarea wire:model="address" class="w-full px-3 py-2 border rounded"></textarea>
            @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ $isEditMode ? 'Actualizar' : 'Crear' }}
        </button>
        @if($isEditMode)
            <button type="button" wire:click="resetInputFields" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">
                Cancelar
            </button>
        @endif
    </form>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Nombre</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Dirección</th>
                <th class="border px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td class="border px-4 py-2">{{ $company->name }}</td>
                    <td class="border px-4 py-2">{{ $company->email }}</td>
                    <td class="border px-4 py-2">{{ $company->address }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $company->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Editar</button>
                        <button wire:click="delete({{ $company->id }})" class="bg-red-500 text-white px-2 py-1 rounded ml-2">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
