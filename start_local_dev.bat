@echo off
ECHO Starting local development servers for Option-Rocket...
ECHO.

REM Start Vite for frontend assets
START cmd /k "cd /d "%~dp0" && npm run dev"
ECHO Vite server started in a new window.

REM Start Laravel development server
START cmd /k "cd /d "%~dp0" && php artisan serve"
ECHO Laravel dev server started in a new window.

REM Start Laravel Reverb server
START cmd /k "cd /d "%~dp0" && php artisan reverb:start --port=9090 --debug"
ECHO Laravel Reverb server started in a new window.

REM Start Stripe CLI Webhook Listener
REM IMPORTANT: Ensure Stripe CLI is installed and you've run 'stripe login' once.
START cmd /k "cd /d "%~dp0" && .\docs\stripe.exe listen --forward-to http://localhost:8001/stripe/webhook"
ECHO Stripe webhook listener started in a new window.

ECHO.
ECHO All development servers are attempting to start.
ECHO Please check the individual windows for output and status.
ECHO Close this window to keep them running, or close the spawned windows to stop them.
