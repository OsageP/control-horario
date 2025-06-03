<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Registro de Auditoría</h2>

    <!-- Filtros -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <input type="text" wire:model.defer="entityType" class="p-2 border rounded" placeholder="Entidad (user, role...)">
        <select wire:model.defer="action" class="p-2 border rounded">
            <option value="">-- Acción --</option>
            <option value="created">Creación</option>
            <option value="updated">Actualización</option>
            <option value="deleted">Eliminación</option>
        </select>
        <select wire:model.defer="userId" class="p-2 border rounded">
            <option value="">-- Usuario --</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <div class="flex space-x-2">
            <input type="date" wire:model.defer="dateFrom" class="p-2 border rounded w-1/2">
            <input type="date" wire:model.defer="dateTo" class="p-2 border rounded w-1/2">
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="p-2">Fecha</th>
                    <th class="p-2">Entidad</th>
                    <th class="p-2">Acción</th>
                    <th class="p-2">Usuario</th>
                    <th class="p-2">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr class="border-b">
                        <td class="p-2">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2">{{ ucfirst($log->entity_type) }} #{{ $log->entity_id }}</td>
                        <td class="p-2 text-center">
                            <span class="px-2 py-1 rounded text-white text-sm
                                @if($log->action === 'created') bg-green-600
                                @elseif($log->action === 'updated') bg-yellow-500
                                @elseif($log->action === 'deleted') bg-red-600
                                @else bg-gray-500
                                @endif">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="p-2">{{ $log->actor->name ?? 'Sistema' }}</td>
                        <td class="p-2">
                            <button wire:click="$set('showDetails', {{ $log->id }})" class="text-blue-600 underline">Ver</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Modal de Detalles -->
    @if ($showDetails)
        @php
            $log = $logs->firstWhere('id', $showDetails);
        @endphp
        @if ($log)
            <div class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow-xl w-full max-w-3xl">
                    <h3 class="text-xl font-semibold mb-4">Detalles de log #{{ $log->id }}</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold">Valores anteriores</h4>
                            <pre class="text-sm bg-gray-100 p-2 rounded">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        <div>
                            <h4 class="font-bold">Valores nuevos</h4>
                            <pre class="text-sm bg-gray-100 p-2 rounded">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        IP: {{ $log->ip_address }}<br>
                        Navegador: {{ $log->user_agent }}
                    </div>
                    <button wire:click="$set('showDetails', null)" class="mt-4 bg-gray-800 text-white px-4 py-2 rounded">Cerrar</button>
                </div>
            </div>
        @endif
    @endif
    <div class="flex justify-between mb-4">
    <input type="text" wire:model="search" placeholder="Buscar en logs..." class="p-2 border rounded w-1/2" />
    <div>
        <button wire:click="export('csv')" class="bg-green-600 text-white px-3 py-1 rounded mr-2">Exportar CSV</button>
        <button wire:click="export('pdf')" class="bg-blue-600 text-white px-3 py-1 rounded">Exportar PDF</button>
    </div>
</div>

</div>
