#!/bin/bash

echo "==================================================="
echo "Preparing Attendance System for Hostinger Deployment"
echo "==================================================="
echo

echo "Step 1: Installing production dependencies..."
composer install --optimize-autoloader --no-dev
if [ $? -ne 0 ]; then
    echo "Error: Failed to install dependencies."
    exit 1
fi
echo

echo "Step 2: Generating production environment file..."
if [ -f .env.production ]; then
    echo ".env.production already exists. Skipping..."
else
    if [ -f .env.production.example ]; then
        cp .env.production.example .env.production
        echo "Created .env.production from example file."
        echo "IMPORTANT: Please edit .env.production with your actual Hostinger credentials."
    else
        echo "Warning: .env.production.example not found. Please create .env.production manually."
    fi
fi
echo

echo "Step 3: Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
echo

echo "Step 4: Creating storage link..."
php artisan storage:link
echo

echo "Step 5: Checking for required files..."
if [ ! -f .htaccess ]; then
    echo "Creating root .htaccess file..."
    cat > .htaccess << 'EOL'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
EOL
fi
echo

echo "==================================================="
echo "Preparation complete!"
echo "==================================================="
echo
echo "Your project is now ready for deployment to Hostinger."
echo "Please refer to HOSTINGER_DEPLOYMENT.md for detailed upload instructions."
echo

# Make the script executable
chmod +x prepare_for_hostinger.sh
