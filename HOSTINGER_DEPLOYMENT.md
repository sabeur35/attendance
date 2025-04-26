# Deploying to Hostinger

This guide will help you deploy the Attendance System to Hostinger shared hosting.

## Pre-Deployment Steps

1. **Prepare Your Local Project**

   ```bash
   # Generate optimized autoload files
   composer install --optimize-autoloader --no-dev

   # Optimize configuration loading
   php artisan config:cache

   # Optimize route loading
   php artisan route:cache

   # Compile all Blade templates
   php artisan view:cache
   ```

2. **Create Production Environment File**

   Copy the `.env.production.example` file to `.env.production` and update it with your Hostinger database credentials and other production settings.

   ```bash
   cp .env.production.example .env.production
   # Edit the file with your actual Hostinger credentials
   ```

## Uploading to Hostinger

### Option 1: Using FTP

1. Connect to your Hostinger account using FTP (FileZilla, etc.)
2. Upload all files to the root directory of your hosting account
3. Make sure to set proper permissions:
   - Directories: 755
   - Files: 644
   - Storage directory and its subdirectories: 775

### Option 2: Using Git (if available on your Hostinger plan)

1. SSH into your Hostinger account
2. Navigate to your web directory
3. Clone your repository
4. Run the deployment commands

## Post-Upload Steps

1. **Set Up the Database**

   - Create a new database in your Hostinger control panel
   - Import your database or run migrations:
     ```bash
     php artisan migrate --force
     ```

2. **Environment Setup**

   - Rename your `.env.production` file to `.env` on the server
   - Make sure the APP_KEY is set correctly
   - Set APP_ENV to 'production'
   - Set APP_DEBUG to 'false'

3. **Directory Permissions**

   Ensure these directories are writable by the web server:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

4. **Create Symbolic Link for Storage**

   ```bash
   php artisan storage:link
   ```

5. **Clear Caches**

   ```bash
   php artisan optimize:clear
   php artisan optimize
   ```

## Troubleshooting

### Common Issues

1. **500 Server Error**
   - Check the Laravel logs in `storage/logs/laravel.log`
   - Ensure `.htaccess` files are uploaded correctly
   - Verify file permissions

2. **Database Connection Issues**
   - Double-check your database credentials in the `.env` file
   - Ensure your Hostinger database user has proper permissions

3. **White Screen / Blank Page**
   - Enable error reporting in `public/index.php` temporarily:
     ```php
     ini_set('display_errors', 1);
     ini_set('display_startup_errors', 1);
     error_reporting(E_ALL);
     ```

## Maintenance

To put your application in maintenance mode during updates:

```bash
php artisan down
# Perform your updates
php artisan up
```

## Security Considerations

1. Ensure your `.env` file is not publicly accessible
2. Keep APP_DEBUG set to false in production
3. Regularly update your Laravel application and dependencies
4. Consider setting up HTTPS for your domain in the Hostinger control panel
