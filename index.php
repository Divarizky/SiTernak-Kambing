<?php
// Mendapatkan nama file halaman saat ini
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'SiTernak Kambing'; ?></title>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>SiTernak Kambing</h3>
                <p>Dashboard Monitoring</p>
            </div>
            <nav>
                <a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                    <img class="sidebar-icon" src="assets/images/icons/dashboard.png" alt="dashboard">
                    <span>Dashboard</span>
                </a>
                <a href="views/data_kambing.php" class="<?php echo $currentPage == 'data_kambing.php' ? 'active' : ''; ?>">
                    <img class="sidebar-icon" src="assets/images/icons/goat.png" alt="goat">
                    <span>Data Kambing</span>
                </a>
                <button class="btn-logout" type="button">Logout</button>
            </nav>
        </aside>

        <!-- Konten -->
        <main id="main-content" class="main-content">
            <div class="main-header">
                <h2>Dashboard</h2>
            </div>
            <!-- Card -->

        </main>
    </div>
</body>

</html>