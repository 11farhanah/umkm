<?php
session_start();
require_once '../config/paths.php';
require_once APP_CONFIG . '/config.php';
require_once APP_INCLUDES . '/auth.php';
require_once APP_ROOT . '/security.php';

// Remove requireLogin to make page public
// requireLogin();
generateCSRFToken();

$title = "Jajan Toang - Kuliner Lokal Panguragan";
$page = 'index';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <!-- Dashboard CSS -->
    <link href="<?php echo APP_URL; ?>/css/dashboard.css" rel="stylesheet">
</head>

<body class="d-flex flex-column">
    <?php include APP_INCLUDES . '/header.php'; ?>

    <main class="dashboard-container flex-grow-1">
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <span class="section-badge">Kuliner Toang Panguragan</span>
                        <h1>Jajan enak, cerita lokal. Semua ada di <span>Jajan Toang.</span></h1>
                        <p class="hero-subtitle">Temukan jajanan khas dan minuman segar dari UMKM sekitar Toang, Panguragan. Pilih favoritmu, lalu langsung temukan lokasinya.</p>
                        <div class="hero-actions">
                            <a href="#featured" class="btn btn-primary btn-lg"><i class="fas fa-utensils me-2"></i>Jelajahi Kuliner</a>
                            <a href="maps.php" class="btn btn-light btn-lg"><i class="fas fa-map-location-dot me-2"></i>Lihat Peta</a>
                        </div>

                        <div class="hero-stats row g-3">
                            <div class="col-4">
                                <div class="stat-card">
                                    <strong>4</strong>
                                    <span>UMKM Lokal</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-card">
                                    <strong>Khas</strong>
                                    <span>Rasa Toang</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-card">
                                    <strong>Dekat</strong>
                                    <span>Mudah dicari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-visual">
                            <div class="mini-card map-card">
                                <i class="fas fa-map-marked-alt"></i>
                                <span>Jelajahi sekitar Toang</span>
                            </div>
                            <div class="mini-card rating-card">
                                <i class="fas fa-pepper-hot"></i>
                                <span>Cimset khas Indramayu</span>
                            </div>
                            <img src="<?php echo APP_URL; ?>/img/cimset.jpg" alt="Penjual Cimset di Toang, Panguragan" class="hero-image">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="feature-strip">
            <div class="container">
                <div class="row g-4 text-center">
                    <div class="col-md-4">
                        <div class="feature-strip-item">
                            <i class="fas fa-location-dot"></i>
                            <h5>Lokasi Terdekat</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-strip-item">
                            <i class="fas fa-route"></i>
                            <h5>Rute Cepat</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-strip-item">
                            <i class="fas fa-heart"></i>
                            <h5>Didukung Lokal</h5>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="promo-banner">
            <div class="container">
                <div class="promo-wrap">
                    <div class="promo-copy">
                        <span class="section-badge section-badge-light">Kuliner Pilihan</span>
                        <h3>Jalan santai seputar Toang Panguragan, nikmati rasa lokal yang bikin balik lagi.</h3>
                    </div>
                    <div class="promo-actions">
                        <a href="#featured" class="btn btn-light btn-lg">Jelajah Sekarang</a>
                        <a href="maps.php" class="btn btn-primary btn-lg">Lihat Rute</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container dashboard-content" id="featured">
            <div class="section-heading">
                <span class="section-badge">Featured Spots</span>
                <h2>4 kuliner favorit sekitar Toang Panguragan</h2>
            </div>

            <div class="row g-4">
                <aside class="col-lg-3">
                    <div class="sidebar-filter">
                        <div class="sidebar-header">
                            <h4>Filter & Kategori</h4>
                        </div>

                        <div class="filter-group">
                            <p>Kategori</p>
                            <button class="side-filter active" data-filter="all" type="button">Semua</button>
                            <button class="side-filter" data-filter="kuliner" type="button">Kuliner Lokal</button>
                            <button class="side-filter" data-filter="minuman" type="button">Minuman Segar</button>
                        </div>

                        <div class="filter-group">
                            <p>Fitur</p>
                            <label class="check-option"><input type="checkbox" class="tag-filter" value="popular"> Populer</label>
                            <label class="check-option"><input type="checkbox" class="tag-filter" value="cozy"> Nyaman</label>
                            <label class="check-option"><input type="checkbox" class="tag-filter" value="family"> Ramah Keluarga</label>
                            <label class="check-option"><input type="checkbox" class="tag-filter" value="night"> Late Night</label>
                        </div>
                    </div>
                </aside>

                <div class="col-lg-9">
                    <div class="category-panel mb-4">
                        <div class="search-wrap">
                            <i class="fas fa-search"></i>
                            <input id="umkmSearch" type="text" placeholder="Cari nama tempat atau menu favorit...">
                        </div>

                        <div class="filter-chips" aria-label="Filter kategori UMKM">
                            <button class="filter-btn active" data-filter="all" type="button">Semua</button>
                            <button class="filter-btn" data-filter="kuliner" type="button">Kuliner</button>
                            <button class="filter-btn" data-filter="minuman" type="button">Minuman</button>
                        </div>
                    </div>

                    <div class="row category-showcase g-3 mb-4">
                        <div class="col-md-6">
                            <div class="mini-category-card" data-category="kuliner">
                                <i class="fas fa-bowl-food"></i>
                                <span>Kuliner Lokal</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mini-category-card" data-category="minuman">
                                <i class="fas fa-glass-water"></i>
                                <span>Minuman Segar</span>
                            </div>
                        </div>
                    </div>

                    <div class="umkm-grid" id="umkm-container">
                        <?php
                        try {
                            $stmt = $conn->query("SELECT * FROM umkm WHERE status = 'active' AND id IN (1,2,3,4) ORDER BY FIELD(id, 1, 2, 3, 4)");
                            if ($stmt && $stmt->num_rows > 0) {
                                $menuMap = [
                                    1 => [
                                        ['name' => 'Es Kelapa Muda', 'desc' => 'Kelapa muda segar dengan rasa manis alami dan sensasi dingin yang cocok untuk hari panas.'],
                                        ['name' => 'Cincau Susu', 'desc' => 'Cincau kenyal dengan susu manis yang lembut dan gurih, cocok untuk pelengkap santai.']
                                    ],
                                    2 => [
                                        ['name' => 'Cimset Original', 'desc' => 'Cimol pipih khas Indramayu dengan bagian pinggir yang renyah dan tengah yang tetap kenyal.'],
                                        ['name' => 'Cimset Pedas', 'desc' => 'Cimset dengan racikan bumbu pedas gurih khas pesisir, tingkat pedasnya bisa disesuaikan.']
                                    ],
                                    3 => [
                                        ['name' => 'Es Tebu Segar', 'desc' => 'Minuman tebu yang manis alami, dingin, dan bikin nagih saat cuaca panas.'],
                                        ['name' => 'Es Campur', 'desc' => 'Campuran buah dan sirup segar yang terasa ringan, manis, dan menyegarkan.']
                                    ],
                                    4 => [
                                        ['name' => 'Rujak Bongko', 'desc' => 'Rujak dengan bumbu khas yang pedas, asam, dan segar, menggoda selera.'],
                                        ['name' => 'Rujak Ulek', 'desc' => 'Varian rujak yang khas dengan potongan buah dan bumbu rempah yang kuat dan nikmat.']
                                    ]
                                ];
                                $localImages = [
                                    1 => 'img/kelapa2.jpeg',
                                    2 => 'img/kelapa1.jpeg',
                                    3 => 'img/tebu1.jpeg',
                                    4 => 'img/rujak1.jpeg'
                                ];

                                while ($row = $stmt->fetch_assoc()) {
                                    $image_path = $row['image_path'] ?? '';
                                    $localImage = $localImages[(int) $row['id']] ?? '';
                                    $storedImage = parse_url($image_path);
                                    $appUrl = parse_url(APP_URL);
                                    $isAppImage = is_array($storedImage)
                                        && ($storedImage['host'] ?? '') === ($appUrl['host'] ?? '')
                                        && ($storedImage['port'] ?? null) === ($appUrl['port'] ?? null);
                                    $missingImage = empty($image_path)
                                        || ($isAppImage && !is_file(APP_PUBLIC . '/' . ltrim($storedImage['path'] ?? '', '/')))
                                        || (!preg_match('/^https?:\/\//i', $image_path) && !is_file(APP_PUBLIC . '/' . ltrim($image_path, '/')));
                                    if ($missingImage) {
                                        $image_path = $localImage;
                                    }
                                    $img_src = !empty($image_path)
                                        ? (preg_match('/^https?:\/\//i', $image_path) ? $image_path : APP_URL . '/' . ltrim($image_path, '/'))
                                        : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80';

                                    $name = strtolower(trim($row['name'] ?? ''));
                                    $description = strtolower(trim($row['description'] ?? ''));
                                    $category = 'kuliner';
                                    $tags = ['popular', 'cozy'];

                                    if (strpos($name, 'es') !== false || strpos($name, 'minuman') !== false || strpos($name, 'tea') !== false || strpos($name, 'juice') !== false) {
                                        $category = 'minuman';
                                        $tags = ['popular', 'fresh'];
                                    } elseif (strpos($name, 'rujak') !== false || strpos($name, 'bakso') !== false || strpos($name, 'mie') !== false || strpos($name, 'bakar') !== false || strpos($name, 'roti') !== false) {
                                        $category = 'kuliner';
                                        $tags = ['family', 'popular', 'cozy'];
                                    }

                                    if (strpos($description, 'malam') !== false || strpos($description, 'night') !== false) {
                                        $tags[] = 'night';
                                    }

                                    if (strpos($description, 'keluarga') !== false || strpos($description, 'family') !== false) {
                                        $tags[] = 'family';
                                    }

                                    $tagString = implode(',', array_unique($tags));
                                    $menus = $menuMap[$row['id']] ?? [];
                                    ?>
                                    <div class="umkm-card" data-name="<?php echo htmlspecialchars(strtolower($row['name'])); ?>" data-category="<?php echo $category; ?>" data-tags="<?php echo htmlspecialchars($tagString); ?>" data-description="<?php echo htmlspecialchars(strtolower($row['description'] ?? '')); ?>">
                                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="umkm-image">
                                        <div class="umkm-card-body">
                                            <div class="card-topline">
                                                <span class="badge-soft"><?php echo ucfirst($category); ?></span>
                                                <span class="rating-pill"><i class="fas fa-star"></i> 4.8</span>
                                            </div>
                                            <h5 class="umkm-name"><?php echo htmlspecialchars($row['name']); ?></h5>
                                            <p class="umkm-description"><?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?></p>
                                            <div class="menu-preview">
                                                <?php foreach (array_slice($menus, 0, 2) as $menu): ?>
                                                    <div class="menu-item">
                                                        <strong><?php echo htmlspecialchars($menu['name']); ?></strong>
                                                        <span><?php echo htmlspecialchars($menu['desc']); ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="umkm-tags">
                                                <?php foreach (array_slice(array_unique($tags), 0, 3) as $tag): ?>
                                                    <span class="tag-chip"><?php echo htmlspecialchars($tag); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                            
                                            <div class="umkm-buttons d-flex gap-2 mt-3">
                                                <a href="umkm_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm flex-grow-1">
                                                    <i class="fas fa-star"></i> Detail
                                                </a>
                                                <a href="maps.php?lat=<?php echo $row['latitude']; ?>&lng=<?php echo $row['longitude']; ?>" class="btn btn-route btn-sm">
                                                    <i class="fas fa-route"></i> Rute
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<div class="col-12"><p class="text-center text-muted">Belum ada data UMKM yang terdaftar.</p></div>';
                            }
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">Gagal memuat data UMKM.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <section class="info-section">
            <div class="container">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="info-icon"><i class="fas fa-magnifying-glass"></i></div>
                            <h4>Temukan dengan cepat</h4>
                            <p>Filter dan jelajahi lokasi favorit Anda berdasarkan kriteria yang paling sesuai dengan mood dan kebutuhan hari itu.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="info-icon"><i class="fas fa-location-crosshairs"></i></div>
                            <h4>Rute yang akurat</h4>
                            <p>Gunakan navigasi peta untuk menentukan rute terbaik dan menghemat waktu perjalanan secara real-time.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="info-box">
                            <div class="info-icon"><i class="fas fa-store"></i></div>
                            <h4>Jelajah UMKM</h4>
                            <p>Bangun kebiasaan nongkrong yang lebih cair, kreatif, dan berdampak positif bagi ekonomi lokal sekitar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonial-section">
            <div class="container">
                <div class="section-heading center">
                    <span class="section-badge">Kenapa Jajan Toang</span>
                    <h2>Nyaman untuk mencari tempat nongkrong dan mendukung lokal</h2>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="quote-card">
                            <p>“Aplikasinya membantu saya menemukan kuliner favorit dari UMKM lokal dengan suasana yang nyaman.”</p>
                            <strong>— Rina, mahasiswa</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="quote-card">
                            <p>“Saya suka karena peta dan rutenya jelas. Jadi lebih mudah saat mau nongkrong bareng teman tanpa bingung lokasi.”</p>
                            <strong>— Dika, pekerja</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="quote-card">
                            <p>“Banyak pilihan tempat yang unik dan terasa dekat dengan komunitas. Rasanya lebih personal dan dekat dengan kota.”</p>
                            <strong>— Sari, penggemar kuliner</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include APP_INCLUDES . '/footer.php'; ?>

    <!-- Global JS -->
    <script src="<?php echo APP_URL; ?>/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('umkmSearch');
            const filterButtons = document.querySelectorAll('.filter-btn, .side-filter');
            const cards = document.querySelectorAll('.umkm-card');
            const categoryCards = document.querySelectorAll('.mini-category-card');
            const checkboxes = document.querySelectorAll('.tag-filter');

            let currentFilter = 'all';
            let activeTags = [];

            function syncButtons() {
                filterButtons.forEach(function (button) {
                    const isActive = (button.dataset.filter || 'all') === currentFilter;
                    button.classList.toggle('active', isActive);
                });

                categoryCards.forEach(function (item) {
                    item.classList.toggle('active', (item.dataset.category || '') === currentFilter);
                });
            }

            function applyFilters() {
                const keyword = (searchInput?.value || '').trim().toLowerCase();

                cards.forEach(function (card) {
                    const name = (card.dataset.name || '').toLowerCase();
                    const description = (card.dataset.description || '').toLowerCase();
                    const category = card.dataset.category || 'kuliner';
                    const tags = (card.dataset.tags || '').split(',').map(tag => tag.trim()).filter(Boolean);

                    const matchesKeyword = !keyword || name.includes(keyword) || description.includes(keyword);
                    const matchesCategory = currentFilter === 'all' || category === currentFilter;
                    const matchesTags = activeTags.length === 0 || activeTags.every(tag => tags.includes(tag));

                    card.style.display = matchesKeyword && matchesCategory && matchesTags ? '' : 'none';
                });
            }

            searchInput?.addEventListener('input', applyFilters);

            filterButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    currentFilter = button.dataset.filter || 'all';
                    syncButtons();
                    applyFilters();
                });
            });

            categoryCards.forEach(function (card) {
                card.addEventListener('click', function () {
                    currentFilter = card.dataset.category || 'all';
                    syncButtons();
                    applyFilters();
                });
            });

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const value = checkbox.value;
                    if (checkbox.checked) {
                        if (!activeTags.includes(value)) activeTags.push(value);
                    } else {
                        activeTags = activeTags.filter(tag => tag !== value);
                    }
                    applyFilters();
                });
            });

            syncButtons();
            applyFilters();
        });
    </script>
</body>

</html>