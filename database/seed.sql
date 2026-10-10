INSERT INTO `umkm`
  (`id`, `name`, `description`, `latitude`, `longitude`, `image_path`, `status`, `hours`, `price`)
VALUES
  (1, 'Es Kelapa Mang Bewok', 'Kedai minuman segar yang menghadirkan es kelapa muda khas dengan rasa manis alami, cocok untuk melepas haus dan menemani santai di sekitar Toang Panguragan.', -6.64755750, 108.45589270, 'img/eskelapa.jpg', 'active', '08.00 - 22.00', 'Mulai Rp 5.000'),
  (2, 'Cimset', 'Cimset (cimol setan) adalah jajanan kaki lima khas Indramayu, Jawa Barat. Berbeda dari cimol bulat, cimset berbentuk pipih dengan pinggiran renyah dan bagian tengah kenyal. Camilan aci ini disajikan dengan racikan bumbu pedas gurih khas pesisir, dengan tingkat kepedasan yang dapat disesuaikan.', -6.64702230, 108.45553950, 'img/cimset.jpg', 'active', '09.00 - 21.30', 'Mulai Rp 8.000'),
  (3, 'Es Tebu', 'Pilihan minuman segar berbahan tebu asli dengan rasa manis alami, dingin, dan menyegarkan untuk menemani aktivitas harian.', -6.64634830, 108.45507670, 'img/tebu1.jpeg', 'active', '08.30 - 22.30', 'Mulai Rp 4.000'),
  (4, 'Rujak Bongko Legend', 'Kuliner rujak khas dengan kombinasi rasa pedas, asam, dan segar yang kuat, menjadikannya favorit di area Toang Panguragan.', -6.64612500, 108.45492710, 'img/rujak1.jpeg', 'active', '10.00 - 21.00', 'Mulai Rp 7.000')
ON DUPLICATE KEY UPDATE
  `name` = VALUES(`name`),
  `description` = VALUES(`description`),
  `latitude` = VALUES(`latitude`),
  `longitude` = VALUES(`longitude`),
  `image_path` = VALUES(`image_path`),
  `status` = VALUES(`status`),
  `hours` = VALUES(`hours`),
  `price` = VALUES(`price`);
