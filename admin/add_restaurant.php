<?php
// File: add_restaurant.php

require_once(__DIR__ . '/../includes/auth.php'); // Pastikan admin sudah login
require_once(__DIR__ . '/../includes/db.php');  // Pastikan path benar

$pdo = connectDatabase();  // Mendapatkan koneksi ke database

// Proses tambah restoran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_restaurant'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO restaurants (name, lat, lng, rating, promo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['lat'],
            $_POST['lng'],
            $_POST['rating'],
            $_POST['promo']
        ]);
        header('Location: manage_restaurants.php?success=added');
        exit();
    } catch (PDOException $e) {
        $error = "Gagal menambah restoran: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Restoran</title>
    <link rel="stylesheet" href="../assets/css/admin-styles.css">
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

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        /* Form Container */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        input[type="text"],
        input[type="number"],
        button {
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

        /* Tombol */
        button {
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Tombol Tambah Restoran */
        button[type="submit"] {
            background-color: #007BFF;
            color: white;
            border: none;
            font-weight: bold;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Tombol Kembali */
        .btn-back {
            background-color: #007BFF;
            color: #fff;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .btn-back:hover {
            background-color: #0056b3;
            color: #fff;
        }

        /* Kontainer tombol */
        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        /* Pesan Error */
        .error-message {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
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
            input[type="number"],
            button {
                font-size: 14px;
                padding: 10px;
            }

            .button-container {
                flex-direction: column;
            }

            .button-container button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include '../admin/includes/header.php'; ?>

    <main>
    <h1>Tambah Restoran Baru</h1>

    <?php if (isset($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="add_restaurant.php" method="POST">
        <input type="text" name="name" placeholder="Nama Restoran" required>
        <input type="text" name="lat" placeholder="Latitude" required>
        <input type="text" name="lng" placeholder="Longitude" required>
        <input type="number" name="rating" placeholder="Rating (1-5)" step="0.1">
        <input type="text" name="promo" placeholder="Promo">
        
        <!-- Container untuk tombol -->
        <div class="button-container">
            <button type="submit" name="add_restaurant">Tambah Restoran</button>
            <button type="button" class="btn-back" onclick="window.location.href='manage_restaurants.php';">Kembali</button>
        </div>
    </form>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
