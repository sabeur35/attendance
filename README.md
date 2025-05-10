# Attendance System

## Overview

The Attendance System is a comprehensive web application designed to manage course attendance for educational institutions. It provides a platform for administrators, teachers, and students to efficiently track and manage attendance records.

## Features

### User Management
- **Multiple User Roles**: Admin, Teacher, and Student roles with different permissions
- **Dual Registration System**: Separate registration paths for administrators and students
- **User Authentication**: Secure login and authentication system

### Course Management
- **Course Creation**: Admins and teachers can create new courses
- **Course Editing**: Update course details including name, code, and description
- **Course Deletion**: Admins can delete courses and all associated data
- **Student Enrollment**: Add or remove students from courses

### Attendance Tracking
- **Class Sessions**: Create and manage class sessions for each course
- **Attendance Recording**: Record student attendance for each session
- **Attendance Reports**: View and export attendance reports
- **QR Code Integration**: Students can mark attendance via QR codes

### Device Management
- **Device Registration**: Students can register their devices for attendance
- **Device Verification**: Ensure attendance is marked from registered devices

## Technology Stack

- **Backend**: Laravel 12.10.0
- **Frontend**: Blade templates with Bootstrap
- **Database**: MySQL
- **PHP Version**: 8.2.12

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js and NPM (for asset compilation)

### Setup Instructions

1. **Clone the repository**
   ```
   git clone https://github.com/yourusername/attendance-system.git
   cd attendance-system
   ```

2. **Install PHP dependencies**
   ```
   composer install
   ```

3. **Install JavaScript dependencies**
   ```
   npm install
   ```

4. **Configure environment**
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   - Edit the `.env` file to set your database credentials
   - Run the setup script: `./setup_database.bat` (Windows) or manually set up the database

6. **Run migrations**
   ```
   php artisan migrate --seed
   ```

7. **Compile assets**
   ```
   npm run dev
   ```

8. **Start the server**
   ```
   php artisan serve
   ```

## User Roles and Permissions

### Admin
- Create, edit, and delete courses
- Manage all users
- Access all system features
- Register with admin code (default: admin123)

### Teacher
- Create and edit their own courses
- Manage students in their courses
- Create class sessions
- View attendance reports

### Student
- View enrolled courses
- Mark attendance for sessions
- Register devices for attendance

## Database Structure

The system uses the following main tables:
- `users`: Stores user information and roles
- `courses`: Contains course details
- `class_sessions`: Records individual class sessions
- `student_course`: Junction table for student-course relationships
- `attendances`: Stores attendance records
- `user_devices`: Tracks registered student devices

## Deployment

For deployment to a production server, follow these steps:

1. Set up your production environment variables in `.env.production`
2. Run the deployment script: `./prepare_for_hostinger.bat` (Windows) or `./prepare_for_hostinger.sh` (Linux/Mac)
3. Follow the instructions in `HOSTINGER_DEPLOYMENT.md` for specific hosting provider instructions

## Additional Documentation

- `MYSQL_SETUP.md`: Detailed MySQL setup instructions
- `DEPLOYMENT_CHECKLIST.md`: Pre-deployment verification steps
- `phpmyadmin_setup_guide.md`: Guide for setting up phpMyAdmin

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author

Sabeur - [GitHub Profile](https://github.com/sabeur35)

