<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #647bff, #a57bff); /* Gradient background */
            margin: 0;

        }

        .form-container{
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
            width: 400px; /* Adjust width as needed */  
        }
    </style>
    <link rel="stylesheet" href="../admin/assets/css/admin-styles.css">
</head>
<body>
    <main>
        <div class="form-container">
            <h1>Dashboard Admin</h1>
            <div class="welcome-message">
                <p>Selamat Datang, <strong><?= htmlspecialchars($_SESSION['admin_username']); ?></strong>!</p>
                <p>Silakan pilih menu untuk melanjutkan.</p>
            </div>
            <div class="form-actions">
                <a href="manage_restaurants.php" class="btn btn-primary">Kelola Restoran</a>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </main>
</body>
</html>
