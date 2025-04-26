# Setting Up MySQL for Attendance System

This guide will help you set up MySQL as the database for the Attendance System project.

## Prerequisites

- MySQL Server installed on your system
- PHP and Composer installed
- Laravel project set up

## Step 1: Install MySQL

If you haven't installed MySQL yet, follow the instructions in the `mysql_installation_guide.md` file.

## Step 2: Set Up the Database

### Option 1: Using the Setup Script (Recommended)

1. Run the `setup_database.bat` file by double-clicking it
2. Enter your MySQL root password when prompted
3. The script will:
   - Create the `attendance_system` database
   - Update your `.env` file with the password
   - Run migrations to create all tables
   - Seed the database with initial data

### Option 2: Manual Setup

1. Log in to MySQL:
   ```
   mysql -u root -p
   ```

2. Create the database:
   ```sql
   CREATE DATABASE attendance_system;
   EXIT;
   ```

3. Update your `.env` file with your MySQL credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=attendance_system
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```

4. Run migrations:
   ```
   php artisan migrate:fresh --seed
   ```

## Step 3: Verify Setup

1. Start the Laravel development server:
   ```
   php artisan serve
   ```

2. Visit http://localhost:8000 in your browser
3. Log in to the application
4. The application should now be using MySQL as its database

## Troubleshooting

### Connection Refused

If you get a "Connection refused" error:

1. Check if MySQL service is running
2. Verify your MySQL credentials in the `.env` file
3. Make sure the MySQL port (default: 3306) is not blocked by a firewall

### Migration Failed

If migrations fail:

1. Check if the database exists: `SHOW DATABASES;` in MySQL
2. Verify your database credentials
3. Make sure you have the necessary permissions

### PDO Extension Missing

If you get a PDO extension error:

1. Make sure the PHP PDO MySQL extension is enabled
2. On Windows, uncomment `extension=pdo_mysql` in your php.ini file
3. Restart your web server

## Need Help?

If you encounter any issues, please refer to the Laravel documentation or seek help from the development team.
