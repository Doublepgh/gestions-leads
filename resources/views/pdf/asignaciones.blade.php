<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asignaciones</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte General de Asignaciones</h2>

    <table>
        <thead>
            <tr>
                <th>Operador</th>
                <th>Lead</th>
                <th>Correo</th>
                <th>Asignado en</th>
                <th>Cerrado en</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asignaciones as $asignacion)
                <tr>
                    <td>{{ $asignacion->operador->name ?? 'N/A' }}</td>
                    <td>{{ $asignacion->lead->nombre ?? 'N/A' }}</td>
                    <td>{{ $asignacion->lead->correo ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($asignacion->asignado_en)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($asignacion->cerrado_en)
                            {{ \Carbon\Carbon::parse($asignacion->cerrado_en)->format('d/m/Y H:i') }}
                        @else
                            <span style="color: red;">Pendiente</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
