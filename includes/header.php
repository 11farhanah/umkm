<?php
$homeUrl = APP_URL . '/index.php';
$aboutUrl = APP_URL . '/about.php';
$mapsUrl = APP_URL . '/maps.php';
?>
<nav class="navbar navbar-light sticky-top topbar">
    <div class="container flex-nowrap">
        <a class="navbar-brand brand-mark" href="<?php echo $homeUrl; ?>">
            <span class="brand-icon"><i class="fas fa-utensils"></i></span>
            Jajan Toang
        </a>
        <div class="ms-auto">
            <ul class="navbar-nav flex-row align-items-center gap-1 gap-sm-2">
                <li class="nav-item"><a class="nav-link" href="<?php echo $homeUrl; ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $mapsUrl; ?>">Maps</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo $aboutUrl; ?>">About</a></li>
            </ul>
        </div>
    </div>
</nav>
