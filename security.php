<?php

function generateCSRFToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCSRFToken(?string $token): bool
{
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }

    return is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField(): string
{
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function requireLogin(): void
{
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }
}
