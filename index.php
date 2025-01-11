<?php
// File: index.php

require_once "includes/db.php";

// Dapatkan data dari database
$restaurants = [];
try {
    $pdo = connectDatabase(); // Fungsi koneksi di db.php
    $stmt = $pdo->query("SELECT * FROM restaurants");
    $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wadang</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="lib/leaflet/leaflet.css">
</head>
<body>
    <?php include "includes/header.php"; ?>

    <div id="map"></div>
    <div class="info-box-container">
        <div id="info-box"></div>
    </div>
    <div class="nav-bar">
        <button id="distance-btn" aria-label="Filter berdasarkan jarak"><img src="assets/images/icons/location-icon.svg" alt="Jarak"></button>
        <button id="rating-btn" aria-label="Filter berdasarkan rating"><img src="assets/images/icons/rating-icon.svg" alt="Rating"></button>
        <button id="promo-btn" aria-label="Filter berdasarkan promo"><img src="assets/images/icons/promo-icon.svg" alt="Promo"></button>
    </div>

    <script>
        // Kirim data restoran ke JavaScript
        const restaurantsData = <?php echo json_encode($restaurants); ?>;
    </script>
    <script src="lib/leaflet/leaflet.js"></script>
    <script src="assets/js/main.js"></script>

    <?php include "includes/footer.php"; ?>
</body>
</html>
