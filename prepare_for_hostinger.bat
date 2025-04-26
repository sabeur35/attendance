@echo off
echo ===================================================
echo Preparing Attendance System for Hostinger Deployment
echo ===================================================
echo.

echo Step 1: Installing production dependencies...
call composer install --optimize-autoloader --no-dev
if %ERRORLEVEL% NEQ 0 (
    echo Error: Failed to install dependencies.
    goto error
)
echo.

echo Step 2: Generating production environment file...
if exist .env.production (
    echo .env.production already exists. Skipping...
) else (
    if exist .env.production.example (
        copy .env.production.example .env.production
        echo Created .env.production from example file.
        echo IMPORTANT: Please edit .env.production with your actual Hostinger credentials.
    ) else (
        echo Warning: .env.production.example not found. Please create .env.production manually.
    )
)
echo.

echo Step 3: Optimizing application...
call php artisan config:cache
call php artisan route:cache
call php artisan view:cache
call php artisan optimize
if %ERRORLEVEL% NEQ 0 (
    echo Warning: Some optimization commands failed. This might be expected in development.
)
echo.

echo Step 4: Creating storage link...
call php artisan storage:link
echo.

echo Step 5: Checking for required files...
if not exist .htaccess (
    echo Creating root .htaccess file...
    echo ^<IfModule mod_rewrite.c^> > .htaccess
    echo     RewriteEngine On >> .htaccess
    echo     RewriteRule ^(.*)$ public/$1 [L] >> .htaccess
    echo ^</IfModule^> >> .htaccess
)
echo.

echo ===================================================
echo Preparation complete!
echo ===================================================
echo.
echo Your project is now ready for deployment to Hostinger.
echo Please refer to HOSTINGER_DEPLOYMENT.md for detailed upload instructions.
echo.
goto end

:error
echo.
echo Preparation failed. Please check the errors above.

:end
pause
