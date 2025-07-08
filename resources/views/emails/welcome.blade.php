<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a OptionRocket!</title>

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
            background: #475569;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            margin: 16px 0;
        }

        .email-button:hover {
            background: #334155;
        }
    </style>
</head>
<body style="background-color: #f1f5f9; margin: 0; padding: 20px;">

    <div class="email-template">
        <div class="email-header">
            <h1 style="margin: 0; font-size: 28px; font-weight: 700;">¡Bienvenido a OptionRocket!</h1>
            <p style="margin: 8px 0 0 0; opacity: 0.9;">Estamos emocionados de tenerte con nosotros</p>
        </div>

        <div class="email-content">
            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Hola <strong style="color: #1e293b;">{{ $user->name }}</strong>,
            </p>

            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                ¡Gracias por unirte a OptionRocket! Tu cuenta ha sido creada exitosamente y ya puedes empezar a explorar nuestras herramientas de trading.
            </p>

            <p style="margin: 0 0 24px 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Para comenzar, te recomendamos:
            </p>

            <ul style="margin: 0 0 24px 0; color: #64748b; font-size: 16px; line-height: 1.5; padding-left: 20px;">
                <li style="margin-bottom: 8px;">Completar tu perfil de usuario</li>
                <li style="margin-bottom: 8px;">Configurar tus preferencias de trading</li>
                <li style="margin-bottom: 8px;">Explorar nuestras herramientas de análisis</li>
            </ul>

            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $dashboardUrl }}" style="background: #475569; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: inline-block; font-weight: 600;">
                    Empezar Ahora
                </a>
            </div>

            <p style="margin: 0; color: #64748b; font-size: 16px; line-height: 1.5;">
                Si tienes alguna pregunta, no dudes en contactarnos.
            </p>
        </div>

        <div class="email-footer">
            <p style="margin: 0; color: #64748b; font-size: 14px;">
                © {{ date('Y') }} OptionRocket. Todos los derechos reservados.
            </p>
        </div>
    </div>

</body>
</html>
