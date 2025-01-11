]<?php
session_start();
require_once "../includes/db.php"; // File koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Variabel untuk menyimpan pesan error
    $error = null;

    try {
        // Koneksi ke database
        $pdo = connectDatabase();

        // Query untuk mengambil data admin berdasarkan username
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging sementara: Cek apakah username ditemukan
        if (!$admin) {
            $error = "Username tidak ditemukan.";
        } else {
            // Debugging sementara: Cek hash password
            if (password_verify($password, $admin['password'])) {
                // Jika login berhasil, simpan data ke session
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password salah.";
            }
        }
    } catch (PDOException $e) {
        // Penanganan error database
        $error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
        error_log("Error: " . $e->getMessage()); // Log error untuk debugging
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
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
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
            width: 300px; /* Adjust width as needed */
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Ensure padding is included in width */
        }
        button {
            background: #007BFF; /* Green color */
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        }
        button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login Admin</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
