<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <!-- Título principal -->
    <h2 class="text-2xl font-bold mb-6">Gestión de Roles y Permisos</h2>

    <!-- Mensaje de éxito -->
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Selector de usuario -->
    <div class="mb-6">
        <label class="block font-medium mb-2">Seleccionar Usuario</label>
        <select wire:model.change="selectedUserId" class="w-full border p-2 rounded">
            <option value="">-- Seleccionar --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    </div>

    @if ($selectedUserId)
        <!-- Sección de Roles -->
        <div class="mb-6">
            <label class="block font-medium mb-2">Roles</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($roles as $id => $name)
                    <div class="flex items-center">
                        <input type="checkbox" 
                               wire:model="userRoles" 
                               value="{{ $id }}" 
                               class="mr-2 rounded text-blue-600">
                        <span>{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sección de Permisos -->
        <div class="mb-6">
            <label class="block font-medium mb-2">Permisos</label>
            
            @if (empty($groupedPermissions))
                <!-- Mensaje si no hay permisos -->
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                    No se encontraron permisos para mostrar.
                </div>
            @else
                <!-- Contenedor grid para los grupos de permisos -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($actionGroups as $action => $actionLabel)
                        @if (!empty($groupedPermissions[$action]))
                            <!-- Tarjeta para cada grupo de permisos -->
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <!-- Título del grupo -->
                                <h3 class="font-bold text-lg mb-3 text-center">{{ $actionLabel }}</h3>
                                
                                <!-- Lista de permisos -->
                                <div class="space-y-2">
                                    @foreach ($groupedPermissions[$action] as $permission)
                                        <div class="flex items-center">
                                            <!-- Checkbox para seleccionar permiso -->
                                            <input type="checkbox" 
                                                   wire:model="userPermissions" 
                                                   value="{{ $permission['id'] }}" 
                                                   class="mr-2 rounded text-blue-600">
                                            <!-- Nombre del permiso -->
                                            <span class="capitalize">{{ $permission['display_name'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
   
        <!-- Botón de guardar o mensaje de solo lectura -->
        @if (auth()->user()->can('edit roles'))
            <button wire:click="save" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Guardar
            </button>
        @else
            <div class="mt-4 p-3 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded">
                No tienes permisos para editar roles o permisos. Vista en modo solo lectura.
            </div>
        @endif
    @endif  
</div>