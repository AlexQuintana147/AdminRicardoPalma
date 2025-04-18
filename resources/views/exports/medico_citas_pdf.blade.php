<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Citas por Médico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #16a34a;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }
        .estado-pendiente { color: #ca8a04; }
        .estado-confirmada { color: #2563eb; }
        .estado-cancelada { color: #dc2626; }
        .estado-reprogramada { color: #9333ea; }
        .estado-asistida { color: #16a34a; }
        .estado-no_asistida { color: #6b7280; }
        .resumen {
            margin-top: 20px;
            padding: 10px;
            background-color: #f2f2f2;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Citas por Médico</h1>
        <p>Clínica Ricardo Palma</p>
    </div>

    <div class="info">
        @if(isset($citas[0]) && $citas[0]->medico)
            <p><strong>Médico:</strong> {{ $citas[0]->medico->nombre }}</p>
            <p><strong>Especialidad:</strong> {{ $citas[0]->medico->especialidad }}</p>
        @endif
        <p><strong>Fecha de generación:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        @if(isset($desde) && isset($hasta))
            <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Observaciones</th>
                <th>Diagnóstico</th>
                <th>Calificación</th>
            </tr>
        </thead>
        <tbody>
            @forelse($citas as $cita)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</td>
                    <td>{{ $cita->paciente->name }}</td>
                    <td>{{ $cita->motivo }}</td>
                    <td class="estado-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</td>
                    <td>{{ $cita->observaciones ?? 'N/A' }}</td>
                    <td>{{ $cita->diagnostico ?? 'N/A' }}</td>
                    <td>
                        @if($cita->calificacion)
                            {{ str_repeat('★', $cita->calificacion) . str_repeat('☆', 5 - $cita->calificacion) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay citas registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="resumen">
        <h3>Resumen</h3>
        <p><strong>Total de citas:</strong> {{ $citas->count() }}</p>
        <p><strong>Citas asistidas:</strong> {{ $citas->where('estado', 'asistida')->count() }}</p>
        <p><strong>Citas canceladas:</strong> {{ $citas->where('estado', 'cancelada')->count() }}</p>
        <p><strong>Citas pendientes:</strong> {{ $citas->where('estado', 'pendiente')->count() }}</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Clínica Ricardo Palma - Reporte generado automáticamente</p>
    </div>
</body>
</html>