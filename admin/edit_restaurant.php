<?php
// File: edit_restaurant.php

require_once(__DIR__ . '/../includes/auth.php'); // Pastikan admin sudah login
require_once(__DIR__ . '/../includes/db.php');  // Pastikan path benar

$pdo = connectDatabase();  // Mendapatkan koneksi ke database

// Jika ID restoran tidak ada dalam URL, redirect ke halaman daftar restoran
if (!isset($_GET['id'])) {
    header('Location: manage_restaurants.php');
    exit();
}

$id = $_GET['id'];

// Ambil data restoran yang akan diedit
$stmt = $pdo->prepare("SELECT * FROM restaurants WHERE id = ?");
$stmt->execute([$id]);
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika restoran tidak ditemukan, redirect
if (!$restaurant) {
    header('Location: manage_restaurants.php');
    exit();
}

// Proses edit restoran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_restaurant'])) {
    try {
        $stmt = $pdo->prepare("UPDATE restaurants SET name = ?, lat = ?, lng = ?, rating = ?, promo = ? WHERE id = ?");
        $stmt->execute([
            $_POST['name'],
            $_POST['lat'],
            $_POST['lng'],
            $_POST['rating'],
            $_POST['promo'],
            $id
        ]);
        header('Location: manage_restaurants.php?success=updated');
        exit();
    } catch (PDOException $e) {
        $error = "Gagal mengedit restoran: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restoran</title>
    <link rel="stylesheet" href="../admin/assets/css/admin-styles.css">
    <style>
        /* Styling Global */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        main {
            max-width: 800px;
            width: 90%;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Heading */
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Form Container */
        .form-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Grup Form */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="number"] {
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Tombol Aksi */
        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        button,
        a.btn {
            display: inline-block;
            padding: 12px 15px;
            font-size: 16px;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Tombol Simpan */
        button.btn-primary {
            background-color: #007BFF;
            color: white;
            border: none;
            font-weight: bold;
        }

        button.btn-primary:hover {
            background-color: #0056b3;
        }

        /* Tombol Batal */
        a.btn-secondary {
            background-color: #007BFF;
            color: #fff;
            border: 1px solid #ddd;
            text-decoration: none;
            font-weight: bold;
        }

        a.btn-secondary:hover {
            background-color: #0056b3;
            color: #000;
        }

        /* Pesan Error */
        .error-message {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        /* Responsif */
        @media (max-width: 600px) {
            main {
                width: 95%;
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            input[type="text"],
            input[type="number"] {
                font-size: 14px;
                padding: 10px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions button,
            .form-actions a {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include '../admin/includes/header.php'; ?>
    
    <main>
        <div class="form-container">
            <h1>Edit Restoran</h1>

            <?php if (isset($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="edit_restaurant.php?id=<?= $id; ?>" method="POST">
                <div class="form-group">
                    <label for="name">Nama Restoran</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($restaurant['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lat">Latitude</label>
                    <input type="text" id="lat" name="lat" value="<?= htmlspecialchars($restaurant['lat']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lng">Longitude</label>
                    <input type="text" id="lng" name="lng" value="<?= htmlspecialchars($restaurant['lng']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <input type="number" id="rating" name="rating" value="<?= htmlspecialchars($restaurant['rating']); ?>" step="0.1">
                </div>
                <div class="form-group">
                    <label for="promo">Promo</label>
                    <input type="text" id="promo" name="promo" value="<?= htmlspecialchars($restaurant['promo']); ?>">
                </div>
                <div class="form-actions">
                    <button type="submit" name="edit_restaurant" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="manage_restaurants.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

