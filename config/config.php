<?php

$localConfig = [];
$localConfigPath = APP_CONFIG . '/local.php';
if (is_file($localConfigPath)) {
    $localConfig = require $localConfigPath;
    if (!is_array($localConfig)) {
        throw new RuntimeException('config/local.php must return an array.');
    }
}

$host = getenv('DB_HOST') ?: ($localConfig['DB_HOST'] ?? '127.0.0.1');
$dbName = getenv('DB_NAME') ?: ($localConfig['DB_NAME'] ?? 'tenmu');
$dbUser = getenv('DB_USER') ?: ($localConfig['DB_USER'] ?? 'root');
$dbPass = getenv('DB_PASS') ?: ($localConfig['DB_PASS'] ?? '');

$conn = @new mysqli($host, $dbUser, $dbPass, $dbName);

if ($conn && $conn->connect_error) {
    error_log('Database connection failed: ' . $conn->connect_error);
    $conn = null;
}
