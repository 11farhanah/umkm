<?php

function isLoggedIn(): bool
{
    return !empty($_SESSION['loggedin']) && !empty($_SESSION['user_id']);
}

function sanitizeUsername($username)
{
    if (!is_string($username)) {
        return false;
    }

    $username = trim($username);

    if (strlen($username) < 3 || strlen($username) > 50) {
        return false;
    }

    if (!preg_match('/^[A-Za-z0-9_-]+$/', $username)) {
        return false;
    }

    return $username;
}

function sanitizeInput($value)
{
    if ($value === null) {
        return '';
    }

    if (is_array($value)) {
        return array_map('sanitizeInput', $value);
    }

    $value = trim((string) $value);
    $value = strip_tags($value);

    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function validatePassword(string $password): bool
{
    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
}

function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyUserPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

function usernameExists($conn, string $username): bool
{
    if (!$conn) {
        return false;
    }

    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result && $result->num_rows > 0;
    $stmt->close();

    return $exists;
}

function getUserByUsername($conn, string $username)
{
    if (!$conn) {
        return null;
    }

    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result ? $result->fetch_assoc() : null;
    $stmt->close();

    return $user;
}

function createUser($conn, string $username, string $password): bool
{
    if (!$conn) {
        throw new Exception('Database tidak tersedia.');
    }

    $passwordHash = hashPassword($password);
    $stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, "user")');
    if (!$stmt) {
        throw new Exception('Gagal menyiapkan query pendaftaran.');
    }

    $stmt->bind_param('ss', $username, $passwordHash);
    $success = $stmt->execute();
    $stmt->close();

    return $success;
}
