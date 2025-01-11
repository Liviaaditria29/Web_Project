<?php
// File: manage_restaurants.php

// Pastikan admin sudah login
require_once(__DIR__ . '/../includes/auth.php'); // Perbaiki path 
require_once(__DIR__ . '/../includes/db.php');  // Pastikan path benar

// Dapatkan koneksi PDO
$pdo = connectDatabase();

// Fungsi untuk mendapatkan semua restoran
function getRestaurants($pdo) {
    $stmt = $pdo->query("SELECT * FROM restaurants");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hapus restoran
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM restaurants WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        header('Location: manage_restaurants.php?success=deleted');
        exit();
    } catch (PDOException $e) {
        $error = "Gagal menghapus restoran: " . $e->getMessage();
    }
}

// Dapatkan semua data restoran
$restaurants = getRestaurants($pdo);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Restoran</title>
    <link rel="stylesheet" href="../admin/assets/css/admin-styles.css">
</head>
<body>
    <?php include '../admin/includes/header.php'; ?>

    <main>
        <h1>Kelola Restoran</h1>

        <!-- Pesan Error atau Sukses -->
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <?= htmlspecialchars(ucfirst($_GET['success'])) . ' berhasil.'; ?>
            </div>
        <?php endif; ?>

        <!-- Tombol Tambah Restoran -->
        <section class="form-actions">
            <a href="add_restaurant.php" class="btn btn-primary">Tambah Restoran Baru</a>
        </section>

        <!-- Tabel Daftar Restoran -->
        <section>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Rating</th>
                        <th>Promo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($restaurants as $restaurant): ?>
                        <tr>
                            <td><?= htmlspecialchars($restaurant['id']); ?></td>
                            <td><?= htmlspecialchars($restaurant['name']); ?></td>
                            <td><?= htmlspecialchars($restaurant['lat']); ?></td>
                            <td><?= htmlspecialchars($restaurant['lng']); ?></td>
                            <td><?= htmlspecialchars($restaurant['rating']); ?></td>
                            <td><?= htmlspecialchars($restaurant['promo']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_restaurant.php?id=<?= $restaurant['id']; ?>" class="btn btn-primary">Edit</a>
                                    <a href="manage_restaurants.php?delete=<?= $restaurant['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-secondary">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
