@echo off
echo Demarrage du serveur de developpement Job Finder...
echo Serveur disponible sur: http://localhost:8000
echo Appuyez sur Ctrl+C pour arreter le serveur
echo.
php -S localhost:8000 server.php
pause