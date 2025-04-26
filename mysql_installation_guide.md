# MySQL Installation Guide for Windows

## Step 1: Download MySQL Installer
1. Go to the official MySQL download page: https://dev.mysql.com/downloads/installer/
2. Download the MySQL Installer for Windows (mysql-installer-web-community)

## Step 2: Run the Installer
1. Run the downloaded installer file
2. Choose "Custom" installation type to have more control over what gets installed

## Step 3: Select Products
1. From the "Available Products" list, select:
   - MySQL Server (latest version)
   - MySQL Workbench (optional but recommended for database management)
   - MySQL Shell (optional)
   - MySQL Router (optional)
   - Connector/J (if you need Java connectivity)
   - Connector/NET (if you need .NET connectivity)
2. Click "Next" to proceed

## Step 4: Installation
1. Click "Execute" to begin the installation
2. Wait for the installation to complete
3. Click "Next" when finished

## Step 5: Configuration
1. **High Availability**: Choose "Standalone MySQL Server" for a basic installation
2. **Type and Networking**: 
   - Choose "Development Computer" for a development environment
   - Leave the default port (3306)
   - Ensure "Open Windows Firewall ports for network access" is checked
3. **Authentication Method**: 
   - Choose "Use Strong Password Encryption" (recommended)
4. **Accounts and Roles**:
   - Set a root password (IMPORTANT: Remember this password!)
   - You can add MySQL user accounts if needed
5. **Windows Service**:
   - Configure MySQL as a Windows Service (recommended)
   - Start the service automatically (recommended)
6. **Apply Configuration**:
   - Click "Execute" to apply the configuration
   - Wait for the process to complete
   - Click "Finish" when done

## Step 6: Verify Installation
1. Open Command Prompt
2. Run: `mysql -u root -p`
3. Enter the password you set during installation
4. If you see the MySQL prompt (`mysql>`), the installation was successful

## Step 7: Create Database for Laravel Project
1. In the MySQL prompt, run:
   ```sql
   CREATE DATABASE attendance_system;
   ```
2. Verify the database was created:
   ```sql
   SHOW DATABASES;
   ```
3. Exit MySQL:
   ```sql
   EXIT;
   ```

## Step 8: Update Laravel .env File
1. Make sure your .env file has the following settings:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=attendance_system
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```
2. Replace `your_password_here` with the password you set for the MySQL root user

## Step 9: Run Laravel Migrations
1. In your Laravel project directory, run:
   ```
   php artisan migrate:fresh --seed
   ```
2. This will create all the necessary tables and seed them with initial data

## Troubleshooting
- If you can't connect to MySQL, make sure the MySQL service is running
- To check/start the service:
  1. Press Win+R, type `services.msc` and press Enter
  2. Find "MySQL" in the list
  3. Make sure its status is "Running"
  4. If not, right-click and select "Start"
