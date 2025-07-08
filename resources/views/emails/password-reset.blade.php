<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - OptionRocket</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        .email-template {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-family: 'Inter', Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            overflow: hidden;
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            text-align: center;
        }

        .email-content {
            padding: 24px;
        }

        .email-footer {
            background: #f8fafc;
            padding: 16px 24px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .email-button {
            background: #dc2626;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            margin: 16px 0;
        }

        .email-button:hover {
            background: #b91c1c;
        }
    </style>
</head>
<body style="background-color: #f1f5f9; margin: 0; padding: 20px;">

    <div class="email-template">
        <div class="email-header">
            <h1 style="margin: 0; font-size: 28px; font-weight: 700;">Restablecer Contraseña</h1>
            <p style="margin: 8px 0 0 0; opacity: 0.9;">Solicitud de cambio de contraseña</p>
        </div>

        <div class="email-content">
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 16px; border-radius: 8px; margin: 0 0 24px 0;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="color: #dc2626; font-size: 18px;">⚠️</div>
                    <div style="color: #dc2626; font-weight: 600;">Solicitud de seguridad</div>
                </div>
            </div>

            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Hola,
            </p>

            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Recibimos una solicitud para restablecer la contraseña de tu cuenta en OptionRocket.
            </p>

            <p style="margin: 0 0 24px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Si hiciste esta solicitud, haz clic en el botón de abajo para crear una nueva contraseña:
            </p>

            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $resetUrl }}" style="background: #dc2626; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Restablecer Contraseña
                </a>
            </div>

            <p style="margin: 24px 0 16px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Este enlace expirará en {{ $count }} minutos por seguridad.
            </p>

            <div style="background: #f1f5f9; padding: 16px; border-radius: 8px; margin: 24px 0; border: 1px solid #e2e8f0;">
                <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 1.5;">
                    <strong style="color: #1e293b;">¿No solicitaste este cambio?</strong><br>
                    Si no solicitaste restablecer tu contraseña, puedes ignorar este email. Tu contraseña permanecerá sin cambios.
                </p>
            </div>
        </div>

        <div class="email-footer">
            <p style="margin: 0; color: #64748b; font-size: 14px;">
                © {{ date('Y') }} OptionRocket. Todos los derechos reservados.
            </p>
        </div>
    </div>

</body>
</html>
