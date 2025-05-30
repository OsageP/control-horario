
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Historial de cambios en roles y permisos</h2>

    <table class="w-full table-auto border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Usuario</th>
                <th class="p-2 border">Acción</th>
                <th class="p-2 border">Modelo</th>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Cambios</th>
                <th class="p-2 border">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">{{ $log->user->name ?? 'N/A' }}</td>
                    <td class="p-2 border">{{ $log->action }}</td>
                    <td class="p-2 border">{{ class_basename($log->target_model) }}</td>
                    <td class="p-2 border">{{ $log->target_id }}</td>
                    <td class="p-2 border text-xs whitespace-pre-wrap">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</td>
                    <td class="p-2 border">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-2 border text-center text-gray-500">No hay registros.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
