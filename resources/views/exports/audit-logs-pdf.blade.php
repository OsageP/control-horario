<h2>Logs de Auditoría</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Fecha</th><th>Entidad</th><th>Acción</th><th>Usuario</th><th>Cambios</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $log)
        <tr>
            <td>{{ $log['Fecha'] }}</td>
            <td>{{ $log['Entidad'] }}</td>
            <td>{{ ucwords(str_replace('_', ' ', $log->action)) }}
</td>
            <td>{{ $log['Usuario'] }}</td>
            <td><pre>{{ $log['Cambios'] }}</pre></td>
        </tr>
        @endforeach
    </tbody>
</table>
