<?php
session_start();
require_once '../config/paths.php';
require_once APP_CONFIG . '/config.php';
require_once APP_INCLUDES . '/auth.php';
require_once APP_ROOT . '/security.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM umkm WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $umkm = $result->fetch_assoc();
    $stmt->close();
}

if (!$umkm) {
    header('Location: index.php');
    exit;
}

$v_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($v_user_id !== null && $v_user_id !== '') {
    $visit_stmt = $conn->prepare("INSERT INTO umkm_visits (umkm_id, user_id, visited_at) VALUES (?, ?, NOW())");
    if ($visit_stmt) {
        $visit_stmt->bind_param("ii", $id, $v_user_id);
        $visit_stmt->execute();
        $visit_stmt->close();
    }
}

$successMsg = '';
$errorMsg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_rating'])) {
    if (!function_exists('isLoggedIn') || !isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errorMsg = "Request tidak valid.";
    } else {
        $rating = (int)$_POST['rating'];
        $review = sanitizeInput($_POST['review']);
        $user_id = $_SESSION['user_id'];

        if ($rating < 1 || $rating > 5) {
            $errorMsg = "Rating tidak valid.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM umkm_ratings WHERE umkm_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $id, $user_id);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $stmt->close();
                $stmt = $conn->prepare("UPDATE umkm_ratings SET rating = ?, review = ? WHERE umkm_id = ? AND user_id = ?");
                $stmt->bind_param("isii", $rating, $review, $id, $user_id);
            } else {
                $stmt->close();
                $stmt = $conn->prepare("INSERT INTO umkm_ratings (umkm_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiis", $id, $user_id, $rating, $review);
            }
            if ($stmt->execute()) {
                $successMsg = "Terima kasih atas ulasan Anda!";
            } else {
                $errorMsg = "Gagal menyimpan ulasan.";
            }
            $stmt->close();
        }
    }
}

$avgRating = 0;
$totalRatings = 0;
$stmt = $conn->prepare("SELECT AVG(rating) as avg, COUNT(*) as count FROM umkm_ratings WHERE umkm_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if ($res) {
    $avgRating = round($res['avg'] ?? 0, 1);
    $totalRatings = $res['count'] ?? 0;
}
$stmt->close();

$title = $umkm['name'] . " - Toang Panguragan";
generateCSRFToken();
$img_src = !empty($umkm['image_path'])
    ? (preg_match('/^https?:\/\//i', $umkm['image_path']) ? $umkm['image_path'] : APP_URL . '/' . ltrim($umkm['image_path'], '/'))
    : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=80';

$menuMap = [
    1 => [
        ['name' => 'Es Kelapa Muda', 'desc' => 'Kelapa muda segar dengan rasa manis alami dan sensasi dingin yang sangat cocok untuk melepas dahaga di siang hari.'],
        ['name' => 'Cincau Susu', 'desc' => 'Campuran cincau kenyal dan susu manis yang lembut, terasa ringan namun tetap memanjakan lidah.'],
        ['name' => 'Es Campur', 'desc' => 'Potongan buah dan sirup segar yang creamy, menyegarkan, dan cocok jadi teman santai.']
    ],
    2 => [
        ['name' => 'Cimset Original', 'desc' => 'Cimol pipih khas Indramayu dengan bagian pinggir yang renyah dan tengah yang tetap kenyal.'],
        ['name' => 'Cimset Bumbu Pedas', 'desc' => 'Racikan bumbu pedas gurih khas pesisir melengkapi tekstur cimset yang renyah sekaligus kenyal.'],
        ['name' => 'Cimset Level Pedas', 'desc' => 'Pilih tingkat kepedasan sesuai selera, dari pedas ringan hingga sensasi pedas yang lebih berani.']
    ],
    3 => [
        ['name' => 'Es Tebu Segar', 'desc' => 'Rasa manis alami tebu yang khas, disajikan dingin dan menyegarkan untuk melepas rasa lelah.'],
        ['name' => 'Es Cendol', 'desc' => 'Campuran cendol, santan, dan sirup yang lembut, cocok untuk penggemar rasa manis yang lebih hangat.'],
        ['name' => 'Es Jeruk', 'desc' => 'Jeruk segar dengan rasa asam manis yang ringan, cocok dijadikan pelepas dahaga.']
    ],
    4 => [
        ['name' => 'Rujak Bongko', 'desc' => 'Bumbu rujak khas dengan rasa pedas, asam, dan segar yang seimbang, disajikan dengan potongan buah yang nikmat.'],
        ['name' => 'Rujak Ulek', 'desc' => 'Varian rujak dengan aroma rempah yang lebih kuat, cocok bagi pecinta rasa yang berani dan kaya.'],
        ['name' => 'Buah Segar', 'desc' => 'Campuran buah musiman yang segar, memberi rasa manis, asam, dan sensasi menyegarkan yang khas.']
    ]
];
$featuredMenus = $menuMap[$id] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="<?php echo APP_URL; ?>/css/main.css" rel="stylesheet">
    <style>
        .rating-stars { color: #ffc107; }
        .detail-page .review-card { background: rgba(255,255,255,0.9); }
    </style>
</head>
<body class="d-flex flex-column detail-page" style="min-height: 100vh;">

<?php include APP_INCLUDES . '/header.php'; ?>

<main class="flex-grow-1 py-5">
    <div class="container">
        <?php if($successMsg): ?>
            <div class="alert alert-success"><?php echo $successMsg; ?></div>
        <?php endif; ?>
        <?php if($errorMsg): ?>
            <div class="alert alert-danger"><?php echo $errorMsg; ?></div>
        <?php endif; ?>

        <div class="detail-hero card border-0 shadow-sm overflow-hidden rounded-4 mb-5">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-7">
                    <img src="<?php echo $img_src; ?>" class="detail-image" alt="<?php echo htmlspecialchars($umkm['name']); ?>">
                </div>
                <div class="col-lg-5">
                    <div class="detail-content">
                        <span class="section-badge">Lokasi Populer</span>
                        <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($umkm['name']); ?></h1>
                        <div class="meta-row">
                            <span class="badge-soft"><?php echo htmlspecialchars($umkm['category'] ?? 'UMKM'); ?></span>
                            <span class="rating-stars">
                                <?php
                                    $fullStars = floor($avgRating);
                                    for ($i = 0; $i < $fullStars; $i++) echo '<i class="fas fa-star"></i>';
                                    if ($avgRating - $fullStars >= 0.5) echo '<i class="fas fa-star-half-alt"></i>';
                                ?>
                                <span class="text-dark fw-bold ms-1"><?php echo $avgRating; ?></span>
                                <span class="text-muted small">(<?php echo $totalRatings; ?> ulasan)</span>
                            </span>
                        </div>
                        <p class="detail-description"><?php echo nl2br(htmlspecialchars($umkm['description'])); ?></p>

                        <div class="detail-stats">
                            <div>
                                <strong>Jam Buka</strong>
                                <span><?php echo htmlspecialchars($umkm['hours'] ?? '08.00 - 22.00'); ?></span>
                            </div>
                            <div>
                                <strong>Harga</strong>
                                <span><?php echo htmlspecialchars($umkm['price'] ?? 'Mulai Rp 5.000'); ?></span>
                            </div>
                        </div>

                        <div class="detail-actions">
                            <a href="maps.php?lat=<?php echo $umkm['latitude']; ?>&lng=<?php echo $umkm['longitude']; ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-route"></i> Tampilkan Rute
                            </a>
                            <?php if (!empty($umkm['whatsapp'])): ?>
                                <a href="<?php echo htmlspecialchars($umkm['whatsapp']); ?>" target="_blank" rel="noopener" class="btn btn-success btn-lg">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            <?php endif; ?>
                            <a href="index.php" class="btn btn-outline-primary btn-lg">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="panel-box">
                    <h3 class="fw-bold mb-4">Menu andalan</h3>
                    <div class="menu-detail-grid">
                        <?php foreach ($featuredMenus as $menu): ?>
                            <div class="menu-detail-card">
                                <span class="menu-kicker">Favorit</span>
                                <h5><?php echo htmlspecialchars($menu['name']); ?></h5>
                                <p><?php echo htmlspecialchars($menu['desc']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="panel-box review-panel">
                    <h3 class="fw-bold mb-3">Kesan pelanggan</h3>
                    <div class="mini-review">
                        <div class="mini-review-item">
                            <strong>Rasa khas</strong>
                            <p>Setiap hidangan punya karakter tersendiri, mulai dari segar, manis, hingga pedas yang pas untuk berbagai selera.</p>
                        </div>
                        <div class="mini-review-item">
                            <strong>Suasana</strong>
                            <p>Lokasi yang santai dan dekat dengan aktivitas warga membuat pengalaman kuliner terasa lebih nyaman dan akrab.</p>
                        </div>
                        <div class="mini-review-item">
                            <strong>Rekomendasi</strong>
                            <p>Untuk pengalaman terbaik, pilih menu andalan yang paling sesuai dengan selera Anda lalu lanjutkan ke rute paling cepat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include APP_INCLUDES . '/footer.php'; ?>

</body>
</html>
