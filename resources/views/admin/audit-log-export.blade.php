<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Exportación de Logs de Auditoría</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
        .label {
            padding: 2px 4px;
            font-size: 11px;
            border-radius: 3px;
            font-weight: bold;
            color: #fff;
        }
        .created { background-color: #28a745; }
        .updated { background-color: #ffc107; color: #000; }
        .deleted { background-color: #dc3545; }
        .default { background-color: #6c757d; }
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
        @foreach ($logs as $log)
            @php
                $colorClass = match($log->action) {
                    'created' => 'created',
                    'updated' => 'updated',
                    'deleted' => 'deleted',
                    default => 'default',
                };
            @endphp
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst($log->entity_type) }} #{{ $log->entity_id }}</td>
                <td><span class="label {{ $colorClass }}">{{ ucfirst($log->action) }}</span></td>
                <td>{{ $log->actor->name ?? 'Sistema' }}</td>
                <td>{{ $log->description ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
