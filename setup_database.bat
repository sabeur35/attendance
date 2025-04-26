@echo off
echo Setting up MySQL database for Attendance System
echo =============================================
echo.

echo This script will:
echo 1. Create the attendance_system database
echo 2. Run Laravel migrations
echo.

set /p password=Enter your MySQL root password (leave blank if none): 

echo.
echo Creating database...
if "%password%"=="" (
    mysql -u root < setup_mysql_database.sql
) else (
    mysql -u root -p%password% < setup_mysql_database.sql
)

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo Error: Could not create database. Please check if MySQL is installed and running.
    echo See mysql_installation_guide.md for installation instructions.
    goto end
)

echo.
echo Database created successfully!
echo.

echo Updating .env file with your password...
powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=', 'DB_PASSWORD=%password%' | Set-Content .env"

echo.
echo Running Laravel migrations...
php artisan migrate:fresh --seed

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo Error: Migration failed. Please check your database configuration.
) else (
    echo.
    echo Success! Your database has been set up with all required tables.
)

:end
echo.
echo Press any key to exit...
pause > nul
