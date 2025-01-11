<header>
    <div class="header-container">
        <div class="logo-container">
            <img src="../assets/images/logo.png" alt="Logo My Wadang" class="logo">
        </div>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_restaurants.php">Kelola Restoran</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>
</header>

<style>
    /* Styling untuk Header */
    header {
        background: linear-gradient(to right, #647bff, #a57bff); /* Gradient background */
        padding: 10px 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo {
        height: 40px;
        width: auto;
    }

    .nav-links {
        display: flex;
        gap: 20px;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .nav-links a:hover {
        color: #d4f0d9;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .header-container {
            flex-direction: column;
            align-items: center;
        }

        .nav-links {
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }
    }
</style>
