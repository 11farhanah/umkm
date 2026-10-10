<?php
session_start();
require_once '../config/paths.php';
require_once APP_CONFIG . '/config.php';
require_once APP_INCLUDES . '/auth.php';
require_once APP_ROOT . '/security.php';

// Check authentication
// requireLogin();

// Generate CSRF token
generateCSRFToken();

$title = "Tentang Jajan Toang";
$page = 'about';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Global CSS -->
    <link href="<?php echo APP_URL; ?>/css/main.css" rel="stylesheet">

    <style>
        .about-header {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('<?php echo APP_URL; ?>/img/background.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: #0d6efd;
        }

        .feature-box {
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="d-flex flex-column">
    <?php include APP_INCLUDES . '/header.php'; ?>

    <main class="flex-grow-1">
        <header class="about-header">
            <div class="container">
                <div class="hero-copy text-center">
                    <span class="section-badge section-badge-light">Tentang Jajan Toang</span>
                    <h1 class="display-4 fw-bold mb-3">Temukan kuliner lokal favorit di Toang.</h1>
                    <p class="lead mb-0">Jelajahi jajanan dan minuman khas sambil mendukung UMKM di sekitar Panguragan.</p>
                </div>
            </div>
        </header>

        <div class="container my-5">
            <div class="row align-items-center mb-5 g-4">
                <div class="col-lg-6">
                    <span class="section-badge">Visi Kami</span>
                    <h2 class="section-title mt-3">Rasa lokal, dekat dari rumah.</h2>
                    <p><strong>Jajan Toang</strong> membantu Anda menemukan informasi kuliner UMKM di sekitar Toang, Panguragan. Kami ingin jajanan lokal dan minuman segar dari para pelaku usaha setempat lebih mudah ditemukan dan dinikmati.</p>
                    <p>Dari Cimset khas Indramayu hingga es kelapa dan es tebu, Jajan Toang menyajikan informasi lokasi, menu, dan rute agar Anda bisa menjelajah kuliner lokal dengan mudah.</p>
                </div>
                <div class="col-lg-6">
                    <div class="showcase-card">
                        <img src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=1200&q=80" class="img-fluid rounded-4 shadow" alt="UMKM Lokal">
                    </div>
                </div>
            </div>

            <div class="row text-center mb-5 g-4">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="fas fa-map-location-dot"></i>
                        </div>
                        <h5 class="fw-bold">Petakan Lokasi</h5>
                        <p class="text-muted">Temukan UMKM favorit dengan peta interaktif yang akurat dan mudah diikuti.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="fas fa-route"></i>
                        </div>
                        <h5 class="fw-bold">Rute Navigasi</h5>
                        <p class="text-muted">Dapatkan arah yang jelas menuju lokasi tujuan dari posisi Anda saat ini.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="icon-box">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h5 class="fw-bold">Dukung UMKM</h5>
                        <p class="text-muted">Bersama-sama menjaga ekonomi lokal dan memperkuat bisnis yang memberi warna kota.</p>
                    </div>
                </div>
            </div>

            <div class="story-panel">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5">
                        <div class="story-image-wrap">
                            <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80" alt="Kegiatan komunitas" class="img-fluid rounded-4 shadow-sm">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <span class="section-badge">Misi Kami</span>
                        <h2 class="section-title mt-3">Membuat kuliner lokal semakin mudah dijelajahi.</h2>
                        <p>Jajan Toang menjadi jembatan antara pelanggan dan pelaku UMKM lokal dengan informasi kuliner yang praktis dan mudah diakses.</p>
                        <p>Melalui peta dan petunjuk rute, kami membantu Anda menemukan jajanan serta minuman yang sesuai selera di sekitar Toang, Panguragan.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include APP_INCLUDES . '/footer.php'; ?>

    <!-- Global JS -->
    <script src="<?php echo APP_URL; ?>/js/main.js"></script>
</body>

</html>