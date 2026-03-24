<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mis Citas del Día</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 10px; font-size: 12px;}
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Mis Citas Programadas</h2>
        <p><strong>Dr(a).</strong> {{ $doctor->user->name ?? 'N/A' }}</p>
        <p><strong>Fecha:</strong> {{ $date->format('d/m/Y') }}</p>
    </div>

    @if($appointments->isEmpty())
        <p>No tienes citas programadas para el día de hoy.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $app)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($app->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($app->end_time)->format('H:i') }}</td>
                    <td>{{ $app->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $app->reason ?? 'N/A' }}</td>
                    <td>{{ $app->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
