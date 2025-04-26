<?php
/**
 * phpMyAdmin Accessibility Check
 * 
 * This script checks if phpMyAdmin is accessible on your system.
 */

echo "Checking phpMyAdmin Accessibility\n";
echo "================================\n\n";

// Common phpMyAdmin URLs
$urls = [
    'http://localhost/phpmyadmin/',
    'http://127.0.0.1/phpmyadmin/',
    'http://localhost:8080/phpmyadmin/',
    'http://127.0.0.1:8080/phpmyadmin/'
];

echo "Checking common phpMyAdmin URLs:\n";

foreach ($urls as $url) {
    echo "Testing $url... ";
    
    // Create a stream context with a short timeout
    $context = stream_context_create([
        'http' => [
            'timeout' => 2 // 2 seconds timeout
        ]
    ]);
    
    // Try to get the headers
    $headers = @get_headers($url, 1, $context);
    
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "ACCESSIBLE\n";
        echo "\nphpMyAdmin is accessible at: $url\n";
        echo "You can open this URL in your browser to access phpMyAdmin.\n";
        exit(0);
    } else {
        echo "NOT ACCESSIBLE\n";
    }
}

echo "\nCould not access phpMyAdmin at common URLs.\n\n";

// Check if XAMPP or WampServer is installed
echo "Checking for XAMPP or WampServer installation:\n";

$xamppPaths = [
    'C:\\xampp',
    'C:\\Program Files\\xampp'
];

$wampPaths = [
    'C:\\wamp',
    'C:\\wamp64'
];

$found = false;

foreach ($xamppPaths as $path) {
    if (file_exists($path)) {
        echo "XAMPP found at: $path\n";
        echo "You should start Apache and MySQL services using XAMPP Control Panel.\n";
        $found = true;
        break;
    }
}

if (!$found) {
    foreach ($wampPaths as $path) {
        if (file_exists($path)) {
            echo "WampServer found at: $path\n";
            echo "You should start WampServer services using its system tray icon.\n";
            $found = true;
            break;
        }
    }
}

if (!$found) {
    echo "Neither XAMPP nor WampServer was found in common locations.\n";
    echo "Please install one of them by following the instructions in phpmyadmin_setup_guide.md\n";
}

echo "\nTroubleshooting tips:\n";
echo "1. Make sure Apache and MySQL services are running\n";
echo "2. Check if the ports (80, 3306) are not being used by other applications\n";
echo "3. Try accessing phpMyAdmin with a different URL (e.g., with a different port)\n";
echo "4. Refer to phpmyadmin_setup_guide.md for detailed installation instructions\n";
