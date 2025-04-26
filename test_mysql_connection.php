<?php
/**
 * MySQL Connection Test Script
 * 
 * This script tests the connection to MySQL using the credentials from the .env file.
 * Run this script after setting up MySQL to verify your connection.
 */

// Load .env file variables
$envFile = file_get_contents('.env');
$lines = explode("\n", $envFile);
$env = [];

foreach ($lines as $line) {
    if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }
}

// Get database credentials from .env
$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$database = $env['DB_DATABASE'] ?? 'attendance_system';
$username = $env['DB_USERNAME'] ?? 'root';
$password = $env['DB_PASSWORD'] ?? '';

echo "Testing MySQL Connection\n";
echo "=======================\n\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $database\n";
echo "Username: $username\n";
echo "Password: " . (empty($password) ? "(empty)" : "(set)") . "\n\n";

try {
    // Attempt to connect to MySQL
    $dsn = "mysql:host=$host;port=$port;dbname=$database";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    echo "Connecting to MySQL...\n";
    $pdo = new PDO($dsn, $username, $password, $options);
    
    echo "Connection successful!\n\n";
    
    // Test query
    echo "Testing query...\n";
    $stmt = $pdo->query("SELECT VERSION() as version");
    $row = $stmt->fetch();
    echo "MySQL Version: " . $row['version'] . "\n\n";
    
    // Check if database exists
    echo "Checking if database '$database' exists...\n";
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
    if ($stmt->rowCount() > 0) {
        echo "Database '$database' exists.\n\n";
        
        // Check tables
        echo "Checking tables...\n";
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "Found " . count($tables) . " tables:\n";
            foreach ($tables as $table) {
                echo "- $table\n";
            }
        } else {
            echo "No tables found. You may need to run migrations.\n";
            echo "Run: php artisan migrate:fresh --seed\n";
        }
    } else {
        echo "Database '$database' does not exist. You need to create it.\n";
        echo "Run: CREATE DATABASE $database;\n";
    }
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n\n";
    
    echo "Troubleshooting tips:\n";
    echo "1. Make sure MySQL is installed and running\n";
    echo "2. Check your credentials in the .env file\n";
    echo "3. Make sure the database '$database' exists\n";
    echo "4. Verify that the MySQL port ($port) is not blocked by a firewall\n";
    echo "5. Check if the PHP PDO MySQL extension is enabled\n";
}

echo "\nTest completed.\n";
