@echo off
echo Migrating to phpMyAdmin for Attendance System
echo =============================================
echo.

echo This script will help you migrate your database to phpMyAdmin.
echo.

echo Step 1: Checking if XAMPP or WampServer is installed...
echo.

set xampp_found=0
set wamp_found=0

if exist "C:\xampp" (
    echo XAMPP found at C:\xampp
    set xampp_found=1
) else if exist "C:\Program Files\xampp" (
    echo XAMPP found at C:\Program Files\xampp
    set xampp_found=1
) else (
    echo XAMPP not found in common locations.
)

if exist "C:\wamp" (
    echo WampServer found at C:\wamp
    set wamp_found=1
) else if exist "C:\wamp64" (
    echo WampServer found at C:\wamp64
    set wamp_found=1
) else (
    echo WampServer not found in common locations.
)

echo.
if %xampp_found%==0 if %wamp_found%==0 (
    echo Neither XAMPP nor WampServer was found on your system.
    echo Please install one of them by following the instructions in phpmyadmin_setup_guide.md
    echo.
    echo After installation, run this script again.
    goto end
)

echo Step 2: Checking database configuration...
echo.

set /p check_env=Do you want to check your .env file for correct MySQL settings? (Y/N): 

if /i "%check_env%"=="Y" (
    echo.
    echo Current .env database settings:
    echo.
    findstr /C:"DB_" .env
    echo.
    set /p update_env=Do you want to update these settings? (Y/N): 
    
    if /i "%update_env%"=="Y" (
        echo.
        set /p db_host=Enter database host (default: 127.0.0.1): 
        set /p db_port=Enter database port (default: 3306): 
        set /p db_name=Enter database name (default: attendance_system): 
        set /p db_user=Enter database username (default: root): 
        set /p db_pass=Enter database password (leave blank if none): 
        
        if "%db_host%"=="" set db_host=127.0.0.1
        if "%db_port%"=="" set db_port=3306
        if "%db_name%"=="" set db_name=attendance_system
        if "%db_user%"=="" set db_user=root
        
        echo.
        echo Updating .env file...
        powershell -Command "(Get-Content .env) -replace 'DB_HOST=.*', 'DB_HOST=%db_host%' | Set-Content .env"
        powershell -Command "(Get-Content .env) -replace 'DB_PORT=.*', 'DB_PORT=%db_port%' | Set-Content .env"
        powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=%db_name%' | Set-Content .env"
        powershell -Command "(Get-Content .env) -replace 'DB_USERNAME=.*', 'DB_USERNAME=%db_user%' | Set-Content .env"
        powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=%db_pass%' | Set-Content .env"
        echo .env file updated successfully.
    )
)

echo.
echo Step 3: Opening phpMyAdmin...
echo.

if %xampp_found%==1 (
    echo Attempting to open phpMyAdmin via XAMPP...
    start http://localhost/phpmyadmin/
) else if %wamp_found%==1 (
    echo Attempting to open phpMyAdmin via WampServer...
    start http://localhost/phpmyadmin/
)

echo.
echo Step 4: Creating database in phpMyAdmin...
echo.
echo Please follow these steps in phpMyAdmin:
echo 1. Log in (username: root, password: blank or as set during installation)
echo 2. Click on "Databases" in the top menu
echo 3. Create a new database named "attendance_system"
echo 4. Select "utf8mb4_unicode_ci" as the collation
echo 5. Click "Create"
echo.

set /p db_created=Have you created the database in phpMyAdmin? (Y/N): 

if /i "%db_created%"=="Y" (
    echo.
    echo Step 5: Running Laravel migrations...
    echo.
    
    set /p run_migrations=Do you want to run migrations now? (Y/N): 
    
    if /i "%run_migrations%"=="Y" (
        echo Running migrations...
        php artisan migrate:fresh --seed
        
        if %ERRORLEVEL% NEQ 0 (
            echo.
            echo Error: Migration failed. Please check your database configuration.
        ) else (
            echo.
            echo Success! Your database has been migrated to phpMyAdmin.
            echo You can now manage your database through the phpMyAdmin interface.
        )
    )
)

:end
echo.
echo For more information, please refer to phpmyadmin_setup_guide.md
echo.
echo Press any key to exit...
pause > nul
