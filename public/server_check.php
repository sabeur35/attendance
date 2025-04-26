<?php
/**
 * Server Requirements Checker for Attendance System
 * 
 * This script checks if the server meets the requirements for running the application.
 * Upload this file to your Hostinger server and access it via a web browser to check
 * if your hosting environment is compatible with the application.
 */

$requirements = [
    'PHP Version' => [
        'required' => '8.2.0',
        'current' => phpversion(),
        'result' => version_compare(phpversion(), '8.2.0', '>=')
    ],
    'BCMath Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('bcmath') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('bcmath')
    ],
    'Ctype Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('ctype') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('ctype')
    ],
    'Fileinfo Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('fileinfo')
    ],
    'JSON Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('json')
    ],
    'Mbstring Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('mbstring')
    ],
    'OpenSSL Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('openssl')
    ],
    'PDO Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('pdo')
    ],
    'PDO MySQL Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('pdo_mysql')
    ],
    'Tokenizer Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('tokenizer')
    ],
    'XML Extension' => [
        'required' => 'Enabled',
        'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
        'result' => extension_loaded('xml')
    ],
    'Storage Directory Writable' => [
        'required' => 'Writable',
        'current' => is_writable('../storage') ? 'Writable' : 'Not Writable',
        'result' => is_writable('../storage')
    ],
    'Bootstrap/Cache Directory Writable' => [
        'required' => 'Writable',
        'current' => is_writable('../bootstrap/cache') ? 'Writable' : 'Not Writable',
        'result' => is_writable('../bootstrap/cache')
    ],
    'Mod Rewrite' => [
        'required' => 'Enabled',
        'current' => in_array('mod_rewrite', apache_get_modules()) ? 'Enabled' : 'Disabled',
        'result' => in_array('mod_rewrite', apache_get_modules())
    ]
];

// Check if .env file exists
$envFileExists = file_exists('../.env');

// Count failed requirements
$failedCount = 0;
foreach ($requirements as $requirement) {
    if (!$requirement['result']) {
        $failedCount++;
    }
}

// Determine overall status
$overallStatus = $failedCount === 0 && $envFileExists ? 'success' : 'failed';

// HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Requirements Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .success {
            color: #28a745;
        }
        .failed {
            color: #dc3545;
        }
        .warning {
            color: #ffc107;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
        .summary.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .summary.failed {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Server Requirements Check</h1>
    
    <table>
        <thead>
            <tr>
                <th>Requirement</th>
                <th>Required</th>
                <th>Current</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requirements as $name => $requirement): ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td><?php echo $requirement['required']; ?></td>
                <td><?php echo $requirement['current']; ?></td>
                <td class="<?php echo $requirement['result'] ? 'success' : 'failed'; ?>">
                    <?php echo $requirement['result'] ? 'Pass' : 'Fail'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td>.env File</td>
                <td>Exists</td>
                <td><?php echo $envFileExists ? 'Exists' : 'Missing'; ?></td>
                <td class="<?php echo $envFileExists ? 'success' : 'failed'; ?>">
                    <?php echo $envFileExists ? 'Pass' : 'Fail'; ?>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="summary <?php echo $overallStatus; ?>">
        <?php if ($overallStatus === 'success'): ?>
            <strong>Congratulations!</strong> Your server meets all requirements to run the Attendance System.
        <?php else: ?>
            <strong>Warning!</strong> Your server does not meet all requirements to run the Attendance System.
            <?php if ($failedCount > 0): ?>
                Please fix the <?php echo $failedCount; ?> failed requirement(s) highlighted above.
            <?php endif; ?>
            <?php if (!$envFileExists): ?>
                Make sure the .env file exists in the root directory.
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <p><small>Generated at: <?php echo date('Y-m-d H:i:s'); ?></small></p>
</body>
</html>
