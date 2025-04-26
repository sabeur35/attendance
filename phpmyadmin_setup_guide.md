# Setting Up phpMyAdmin for Your Attendance System

This guide will help you install and configure phpMyAdmin to manage your MySQL database for the Attendance System project.

## Option 1: Install XAMPP (Recommended)

XAMPP is an easy-to-install package that includes Apache, MySQL, PHP, and phpMyAdmin.

### Step 1: Download XAMPP

1. Go to the Apache Friends website: https://www.apachefriends.org/download.html
2. Download the latest version of XAMPP for Windows

### Step 2: Install XAMPP

1. Run the installer
2. You can uncheck components you don't need, but make sure **Apache**, **MySQL**, **PHP**, and **phpMyAdmin** are selected
3. Choose an installation directory (default is C:\xampp)
4. Complete the installation

### Step 3: Start Services

1. Open the XAMPP Control Panel
2. Start the Apache and MySQL services by clicking the "Start" buttons next to them
3. If you get port conflicts:
   - Click "Config" for the service
   - Change the ports (e.g., Apache from 80 to 8080)
   - Restart the service

### Step 4: Access phpMyAdmin

1. Open your web browser
2. Go to http://localhost/phpmyadmin/ (or http://localhost:8080/phpmyadmin/ if you changed the Apache port)
3. You should see the phpMyAdmin login screen
4. Default login credentials:
   - Username: root
   - Password: (leave blank, unless you set one during installation)

## Option 2: Install WampServer

WampServer is another package that includes Apache, MySQL, PHP, and phpMyAdmin.

### Step 1: Download WampServer

1. Go to the WampServer website: https://www.wampserver.com/en/download-wampserver-64bits/
2. Download the latest version for Windows

### Step 2: Install WampServer

1. Run the installer
2. Follow the installation wizard
3. Choose an installation directory (default is C:\wamp64)
4. Complete the installation

### Step 3: Start Services

1. Start WampServer by clicking its icon in the Start menu
2. The WampServer icon should appear in your system tray (bottom right of screen)
3. Left-click the icon and make sure all services are running (icon should be green)

### Step 4: Access phpMyAdmin

1. Left-click the WampServer icon in the system tray
2. Select "phpMyAdmin" from the menu
3. Or go to http://localhost/phpmyadmin/ in your browser
4. Default login credentials:
   - Username: root
   - Password: (leave blank, unless you set one during installation)

## Setting Up Your Database in phpMyAdmin

### Step 1: Create the Database

1. Log in to phpMyAdmin
2. Click on "Databases" in the top menu
3. Under "Create database", enter `attendance_system`
4. Select "utf8mb4_unicode_ci" as the collation
5. Click "Create"

### Step 2: Update Your Laravel .env File

Make sure your `.env` file has the correct MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=root
DB_PASSWORD=
```

If you set a password for the MySQL root user during installation, update the `DB_PASSWORD` line accordingly.

### Step 3: Run Laravel Migrations

1. Open a command prompt in your Laravel project directory
2. Run the migrations:
   ```
   php artisan migrate:fresh --seed
   ```

### Step 4: Verify Database Setup

1. Refresh phpMyAdmin in your browser
2. Click on the `attendance_system` database in the left sidebar
3. You should see all the tables created by the migrations

## Using phpMyAdmin

### Basic Operations

- **Browse Data**: Click on a table name to view its contents
- **Insert Data**: Click on "Insert" to add new records
- **Edit Data**: Click on the edit icon (pencil) next to a record
- **Delete Data**: Click on the delete icon (X) next to a record
- **Run SQL Queries**: Click on the "SQL" tab to execute custom SQL queries

### Exporting and Importing

- **Export Database**: 
  1. Select your database in the left sidebar
  2. Click on "Export" in the top menu
  3. Choose your export format (SQL is recommended)
  4. Click "Go"

- **Import Database**:
  1. Select your database in the left sidebar
  2. Click on "Import" in the top menu
  3. Choose the file to import
  4. Click "Go"

## Troubleshooting

### Cannot Access phpMyAdmin

- Make sure Apache and MySQL services are running
- Check if the ports are not being used by other applications
- Try accessing with "localhost" instead of "127.0.0.1" or vice versa

### Cannot Log In

- Verify your MySQL username and password
- Try with username "root" and an empty password (default)
- If you've set a password, make sure you're using the correct one

### Database Connection Failed

- Ensure MySQL service is running
- Check your `.env` file for correct database credentials
- Verify the database exists in phpMyAdmin

## Need Help?

If you encounter any issues, please refer to the official documentation:

- XAMPP: https://www.apachefriends.org/faq_windows.html
- WampServer: https://www.wampserver.com/en/support/
- phpMyAdmin: https://www.phpmyadmin.net/docs/
