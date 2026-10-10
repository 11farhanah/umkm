<?php

define('APP_ROOT', dirname(__DIR__));
define('APP_PUBLIC', APP_ROOT . '/public');
define('APP_CONFIG', APP_ROOT . '/config');
define('APP_INCLUDES', APP_ROOT . '/includes');

$appUrl = getenv('APP_URL');
if (!$appUrl && isset($_SERVER['HTTP_HOST'])) {
    $scheme = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $basePath = $scriptDirectory === '/' || $scriptDirectory === '.'
        ? ''
        : '/' . trim($scriptDirectory, '/');
    $appUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $basePath;
}

define('APP_URL', rtrim($appUrl ?: 'http://127.0.0.1:8000', '/'));
