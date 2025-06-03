<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Exportación de Logs de Auditoría</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
        }
        .label {
            padding: 2px 4px;
            font-size: 10px;
            border-radius: 3px;
            color: white;
        }
        .created { background-color: #28a745; }
        .updated { background-color: #ffc107; color: black; }
        .deleted { background-color: #dc3545; }
    </style>
</head>
<body>

    <h2>Logs de Auditoría</h2>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Entidad</th>
                <th>Acción</th>
                <th>Usuario</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($log->entity_type) }} #{{ $log->entity_id }}</td>
                    <td>
                        <span class="label {{ strtolower($log->action) }}">
                            {{ ucwords(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </td>
                    <td>{{ $log->actor->name ?? 'Sistema' }}</td>
                    <td>{{ $log->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay registros disponibles.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
