<?php
session_start();
require_once '../config/paths.php';
require_once APP_CONFIG . '/config.php';
require_once APP_INCLUDES . '/auth.php';
require_once APP_INCLUDES . '/images.php';
require_once APP_ROOT . '/security.php';

// Generate CSRF token
generateCSRFToken();

$title = "Peta Kuliner - Jajan Toang";
$page = 'maps';

// Fetch UMKM data from database
$umkms = [];
$stmt = $conn->query("SELECT * FROM umkm WHERE status = 'active'");
if ($stmt) {
    while($row = $stmt->fetch_assoc()) {
        $mappedImage = resolveImageUrl($row['image_path'] ?? '', APP_URL . '/img/eskelapa.jpg');

        $umkms[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['name']),
            'lat' => (float)$row['latitude'],
            'lng' => (float)$row['longitude'],
            'description' => htmlspecialchars(substr($row['description'], 0, 80)) . '...',
            'image' => $mappedImage
        ];
    }
}
$locations_json = json_encode($umkms);
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
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Global CSS -->
    <link href="<?php echo APP_URL; ?>/css/main.css" rel="stylesheet">

    <style>
        #map {
            height: calc(100vh - 180px);
            min-height: 400px;
            width: 100%;
            border-radius: 10px;
            margin: 0 auto;
            max-width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .map-container {
            padding: 1rem 0;
            position: relative;
        }

        /* Custom Navigation Overaly style */
        .nav-panel {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: white;
            padding: 15px 30px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            display: none; /* Hidden until route found */
            align-items: center;
            gap: 25px;
            min-width: 320px;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .nav-panel .nav-dist {
            font-weight: 700;
            font-size: 1.4rem;
            color: #0d6efd;
        }
        .nav-panel .nav-time {
            font-weight: 700;
            font-size: 1.4rem;
            color: #198754;
        }

        /* Recenter button */
        .recenter-btn {
            position: absolute;
            bottom: 120px;
            right: 20px;
            z-index: 1000;
            background: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            cursor: pointer;
            display: none; /* hidden until location is active */
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #0d6efd;
            transition: all 0.3s;
        }
        .recenter-btn:hover {
            background: #f8f9fa;
            transform: scale(1.05);
        }
    </style>
</head>

<body class="d-flex flex-column" style="min-height: 100vh;">
    <?php include APP_INCLUDES . '/header.php'; ?>

    <main class="flex-grow-1">
        <div class="container">
            <div class="map-container">
                <div id="map"></div>
                
                <!-- Recenter Button -->
                <button class="recenter-btn" id="recenterBtn" title="Fokus ke lokasi saya">
                    <i class="fas fa-crosshairs"></i>
                </button>

                <!-- Google Maps style Live Info Panel -->
                <div id="navPanel" class="nav-panel shadow">
                    <div class="text-center">
                        <div style="font-size: 0.85rem; color: #6c757d; font-weight:600; line-height: 1; margin-bottom:4px;">Jarak</div>
                        <div class="nav-dist" id="navDist">-- km</div>
                    </div>
                    <div style="width: 2px; height: 35px; background: #e9ecef; border-radius: 2px;"></div>
                    <div class="text-center">
                        <div style="font-size: 0.85rem; color: #6c757d; font-weight:600; line-height: 1; margin-bottom:4px;">Waktu Tempuh</div>
                        <div class="nav-time" id="navTime">-- mnt</div>
                    </div>
                </div>

                <!-- Controls info -->
                <div class="text-center mt-3 text-muted">
                    <small>📍 Bergeraklah dengan perangkat Anda. Peta akan otomatis memperbarui jarak dan navigasi rute secara real-time.</small>
                </div>
            </div>
        </div>
    </main>

    <?php include APP_INCLUDES . '/footer.php'; ?>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <!-- Global JS -->
    <script src="<?php echo APP_URL; ?>/js/main.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Default center (Cirebon)
            const defaultLat = -6.734842;
            const defaultLng = 108.49833;

            const map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Fetch dynamic UMKM Data from database via PHP
            const locations = <?php echo $locations_json; ?>;

            // Custom icon for UMKM (optional visual improvement)
            const umkmIcon = L.icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            // Add markers
            locations.forEach(loc => {
                L.marker([loc.lat, loc.lng], {icon: umkmIcon}).addTo(map)
                    .bindPopup(`
                    <div style="text-align:center;">
                        <b>${loc.name}</b><br>
                        <img src="${loc.image}" width="150" style="border-radius: 5px; margin: 8px 0; max-height:100px; object-fit:cover;"><br>
                        <p style="margin-bottom:8px; font-size:12px;">${loc.description}</p>
                        <a href="umkm_detail.php?id=${loc.id}" style="color:white; text-decoration:none;" class="btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                `);
            });

            // Get destination from URL
            const urlParams = new URLSearchParams(window.location.search);
            const destLat = parseFloat(urlParams.get('lat'));
            const destLng = parseFloat(urlParams.get('lng'));

            // Focus on destination if present
            if (destLat && destLng) {
                map.setView([destLat, destLng], 14);
            }

            // Get user location & Live Tracking
            let userMarker = null;
            let routingControl = null;
            let hasCentered = false;
            let lastUpdateLatLng = null;
            
            const recenterBtn = document.getElementById('recenterBtn');

            if (navigator.geolocation) {
                // Change to watchPosition for live tracking!
                navigator.geolocation.watchPosition(position => {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    const userLatLng = L.latLng(userLat, userLng);
                    
                    // Activate recenter button
                    if (recenterBtn.style.display !== 'flex') {
                        recenterBtn.style.display = 'flex';
                    }
                    
                    // Always update onclick to jump to freshest location
                    recenterBtn.onclick = function() {
                        map.flyTo(userLatLng, 16, {animate: true, duration: 1.0});
                    };

                    // Add or Move user marker
                    if (!userMarker) {
                        userMarker = L.marker([userLat, userLng]).addTo(map).bindPopup('Lokasi Anda Terkini');
                    } else {
                        userMarker.setLatLng(userLatLng);
                    }

                    // If destination in URL, create routing
                    if (destLat && destLng) {
                        const destLatLng = L.latLng(destLat, destLng);

                        if (!routingControl) {
                            try {
                                lastUpdateLatLng = userLatLng;
                                routingControl = L.Routing.control({
                                    waypoints: [
                                        userLatLng,
                                        destLatLng
                                    ],
                                    routeWhileDragging: false,
                                    addWaypoints: false,
                                    show: false, // Turn off default unstyled side-box
                                    createMarker: function() { return null; } // Don't duplicate markers
                                }).addTo(map);

                                // Sync data with our custom UI Board Overlay
                                routingControl.on('routesfound', function(e) {
                                    const routes = e.routes;
                                    const summary = routes[0].summary;
                                    
                                    // Distance in km
                                    let distanceKm = (summary.totalDistance / 1000).toFixed(1);
                                    if(distanceKm < 1) {
                                        // If less than 1km, show meters
                                        distanceKm = Math.round(summary.totalDistance) + ' m';
                                    } else {
                                        distanceKm = distanceKm + ' km';
                                    }
                                    
                                    // Time in minutes
                                    const timeMin = Math.round(summary.totalTime / 60);
                                    
                                    document.getElementById('navDist').innerText = distanceKm;
                                    if(timeMin > 60) {
                                        const hrs = Math.floor(timeMin / 60);
                                        const mins = timeMin % 60;
                                        document.getElementById('navTime').innerText = hrs + 'j ' + mins + 'm';
                                    } else {
                                        document.getElementById('navTime').innerText = timeMin + ' mnt';
                                    }
                                    
                                    // Make board visible
                                    document.getElementById('navPanel').style.display = 'flex';
                                });
                                
                                // Zoom straight to User's location first, matching Google Maps behavior
                                map.flyTo([userLat, userLng], 16, {animate: true, duration: 1.5});
                                hasCentered = true;

                            } catch (e) {
                                console.error("Routing error:", e);
                            }
                        } else {
                            // The user is moving! Update autopilot route.
                            // Buffer: Only recalculate if they moved more than 5 meters to prevent server ban.
                            if (lastUpdateLatLng.distanceTo(userLatLng) > 5) {
                                routingControl.spliceWaypoints(0, 1, userLatLng);
                                lastUpdateLatLng = userLatLng;
                            }
                        }

                    } else {
                        // User opened /maps.php without destination. Automatically fly to user.
                        if (!hasCentered) {
                            map.flyTo([userLat, userLng], 14, {animate: true, duration: 1.5});
                            hasCentered = true;
                        }
                    }
                }, error => {
                    console.warn("Geolocation Error or Permission Denied: ", error);
                    if (destLat && destLng && !routingControl) {
                        alert("Tidak dapat menampilkan jalur otomatis. Mohon izinkan akses lokasi (GPS) pada browser Anda.");
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 3000
                });
            }
        });
    </script>
</body>

</html>