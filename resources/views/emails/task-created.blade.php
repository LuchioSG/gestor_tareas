<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #333; padding: 20px;">
    <h2 style="color: #6366f1;">Nueva tarea creada</h2>
    
    <p>Se ha creado una nueva tarea en el sistema:</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Título</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $task->title }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Prioridad</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $task->priority }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Estado</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $task->status }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Fecha límite</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee;">
                {{ $task->due_date?->format('d/m/Y') ?? 'Sin fecha' }}
            </td>
        </tr>
    </table>

    <p>Ingresa al sistema para ver más detalles.</p>
</body>
</html>