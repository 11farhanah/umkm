<?php
session_start();
require_once '../config/paths.php';
require_once APP_CONFIG . '/config.php';
require_once APP_INCLUDES . '/auth.php';
require_once APP_ROOT . '/security.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: ' . APP_URL . '/admin/');
    } else {
        header('Location: index.php');
    }
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF Token Verification
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = "Request tidak valid! Silakan coba lagi.";
    } else {
        try {
            // Sanitize & Validate Input
            $username_raw = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            // Basic validation
            if (empty($username_raw) || empty($password)) {
                throw new Exception("Username dan password harus diisi!");
            }

            // Sanitize username
            $username = sanitizeUsername($username_raw);
            if ($username === false) {
                throw new Exception("Format username tidak valid!");
            }

            // Get user from database using prepared statement
            $user = getUserByUsername($conn, $username);

            if ($user === null) {
                throw new Exception("Username atau password salah!");
            }

            // Verify password with bcrypt
            if (verifyUserPassword($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'] ?? 'user';

                // Redirect to appropriate dashboard
                if ($_SESSION['role'] === 'admin') {
                    header('Location: ' . APP_URL . '/admin/');
                } else {
                    header('Location: index.php');
                }
                exit;
            } else {
                throw new Exception("Username atau password salah!");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$title = "Login - Jajan Toang";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Auth CSS -->
    <link href="<?php echo APP_URL; ?>/css/auth.css" rel="stylesheet">
</head>

<body class="auth-container">
    <div class="auth-card">
        <div class="card-body">
            <div class="auth-title">
                <h4>Login Jajan Toang</h4>
                <p>Masuk untuk menjelajahi kuliner UMKM lokal</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger p-3 small text-center">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <?php echo csrfField(); ?>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control"
                        placeholder="Masukkan username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" class="form-control pe-5"
                            placeholder="Masukkan password" required autocomplete="current-password">
                        <span class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </button>
            </form>

            <div class="auth-links">
                <p>Belum punya akun? <a href="signup.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>

    <!-- Auth JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>/js/main.js"></script>
    <script src="<?php echo APP_URL; ?>/js/auth.js"></script>
</body>

</html>