<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-bottom: 20px; }
        .content { font-size: 14px; line-height: 1.6; }
        .info-box { border: 1px solid #eee; padding: 15px; margin-top: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Comprobante de Cita Médica</h2>
        <p>Folio: #{{ $appointment->id }}</p>
    </div>
    
    <div class="content">
        <p>Estimado(a) <strong>{{ $appointment->patient->user->name ?? 'Paciente' }}</strong>,</p>
        <p>Detalles de su cita programada:</p>
        
        <div class="info-box">
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</p>
            <p><strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</p>
            <p><strong>Médico Asignado:</strong> {{ $appointment->doctor->user->name ?? 'Médico General' }}</p>
            <p><strong>Especialidad:</strong> {{ $appointment->doctor->specialty->name ?? 'N/A' }}</p>
            <p><strong>Motivo:</strong> {{ $appointment->reason ?? 'Consulta general' }}</p>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
            Documento generado el {{ now()->format('d/m/Y H:i') }}. Por favor presentarse 10 minutos antes de la hora indicada.
        </p>
    </div>
</body>
</html>
