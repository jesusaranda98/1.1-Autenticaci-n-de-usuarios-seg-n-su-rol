<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Código de Verificación</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f6f9; padding: 24px; color: #1e293b;">
    <div style="max-width: 480px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.06);">
        <h2 style="margin-top: 0; color: #0f172a;">Verificación de Administrador</h2>
        <p>Hola <strong>{{ $user->name }}</strong>,</p>
        <p>Se ha solicitado acceso a tu cuenta de administrador. Ingresa el siguiente código de seguridad:</p>
        <div style="text-align: center; margin: 24px 0;">
            <span style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 8px; background: #eef2ff; color: #3730a3; padding: 14px 24px; border-radius: 10px; border: 1px dashed #6366f1;">
                {{ $code }}
            </span>
        </div>
        <p style="font-size: 14px; color: #475569;">
            Este código vence en <strong>{{ $expiresInMinutes }} minutos</strong>. Si tú no iniciaste sesión, ignora este mensaje.
        </p>
    </div>
</body>
</html>