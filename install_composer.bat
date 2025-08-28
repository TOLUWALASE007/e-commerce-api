@echo off
echo Installing Composer for Windows...
echo.

REM Download Composer installer
echo Downloading Composer installer...
powershell -Command "Invoke-WebRequest -Uri 'https://getcomposer.org/Composer-Setup.exe' -OutFile 'Composer-Setup.exe'"

REM Run installer
echo.
echo Running Composer installer...
echo Please follow the installation prompts...
Composer-Setup.exe

REM Clean up
echo.
echo Cleaning up installer file...
del Composer-Setup.exe

echo.
echo Composer installation completed!
echo Please restart your command prompt and run 'composer install' in your project directory.
pause
