<?php

$host = getenv('DB_HOST') ?: '127.0.0.1';
$dbName = getenv('DB_NAME') ?: 'tenmu';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';

$conn = @new mysqli($host, $dbUser, $dbPass, $dbName);

if ($conn && $conn->connect_error) {
    error_log('Database connection failed: ' . $conn->connect_error);
    $conn = null;
}
