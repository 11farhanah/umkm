<?php
session_start();
require_once '../config/paths.php';
require_once APP_CONFIG . '/config.php';
require_once APP_INCLUDES . '/auth.php';
require_once APP_ROOT . '/security.php';

$message = '';
$message_type = ''; // 'success' atau 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF Token Verification
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = "Request tidak valid! Silakan coba lagi.";
        $message_type = 'error';
    } else {
        try {
            // Sanitize & Validate Input
            $username_raw = isset($_POST['username']) ? $_POST['username'] : '';
            $password_raw = isset($_POST['password']) ? $_POST['password'] : '';

            // Validate Username
            $username = sanitizeUsername($username_raw);
            if ($username === false) {
                throw new Exception("Username hanya boleh mengandung huruf, angka, underscore, dan dash (3-50 karakter)!");
            }

            // Validate Password
            if (!validatePassword($password_raw)) {
                throw new Exception("Password harus minimal 8 karakter dan mengandung campuran huruf serta angka!");
            }

            // Check if username already exists
            if (usernameExists($conn, $username)) {
                throw new Exception("Username sudah dipakai!");
            }

            // Create user
            if (createUser($conn, $username, $password_raw)) {
                $message = "✅ Berhasil daftar! Silakan login.";
                $message_type = 'success';
                // Redirect after 2 seconds
                echo "<script>
                    setTimeout(function() {
                        window.location='login.php';
                    }, 2000);
                </script>";
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
            $message_type = 'error';
        }
    }
}

$title = "Daftar - Jajan Toang";
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
                <h4>Daftar Akun Jajan Toang</h4>
                <p>Buat akun baru untuk mulai menjelajahi</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo ($message_type === 'success') ? 'success' : 'danger'; ?> p-3 small">
                    <i class="fas fa-<?php echo ($message_type === 'success') ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <?php echo csrfField(); ?>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control"
                        placeholder="3-50 karakter" required>
                    <small class="text-muted">Hanya huruf, angka, underscore, dan dash</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" class="form-control pe-5"
                            placeholder="Min 8 karakter" required>
                        <span class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <small class="text-muted">Minimal 8 karakter, harus ada huruf + angka</small>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="auth-links">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
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