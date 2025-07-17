<?php
// Mengecek sesi role User atau Admin (by Login)
session_start();
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Mengambil data dari Database
require_once 'config/database.php';

global $koneksi;

$totalKambing = getTotalKambing();
$perluPerhatian = getKesehatanKambing();

// Mengambil semua data kandang
$query_kandang = "SELECT id_kandang, nama FROM kandang ORDER BY nama ASC";
$result_kandang = $koneksi->query($query_kandang); // Menggunakan $koneksi
$kandangs = [];
if ($result_kandang && $result_kandang->num_rows > 0) {
    while ($row = $result_kandang->fetch_assoc()) {
        $kandangs[] = $row;
    }
}

// Mendapatkan nama file halaman saat ini
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'SiTernak Kambing'; ?></title>
    <!-- Import Chart.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <form action="views/auth/logout.php" method="POST">
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                <?php endif; ?>
            </nav>
        </aside>

        <!-- Konten Utama -->
        <main id="main-content" class="main-content">
            <div class="main-header">
                <h2>Dashboard</h2>
            </div>
            <!-- Overview Data Card -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-title">Total Kambing</div>
                    <div class="card-value"><?= $totalKambing ?></div>
                    <div class="card-desc">Ekor kambing</div>
                </div>
                <div class="card">
                    <div class="card-title">Peringatan</div>
                    <div class="card-value"><?= $perluPerhatian ?></div>
                    <div class="card-desc warning">Perlu perhatian</div>
                </div>
            </div>

            <!-- Bagian Grafik Sensor -->
            <div class="sensor-charts-section">
                <div class="sensor-charts-header">
                    <h3>Monitoring Suhu & Kelembapan Kandang</h3>
                </div>
                <div class="kandang-charts-container">
                    <?php if (!empty($kandangs)): ?>
                        <?php foreach ($kandangs as $kandang): ?>
                            <div class="kandang-chart-card">
                                <div class="kandang-header">
                                    <h3>Kandang: <?= htmlspecialchars($kandang['nama']) ?></h3>
                                    <button class="btn-add-data"
                                        data-kandang-id="<?= $kandang['id_kandang'] ?>"
                                        data-kandang-nama="<?= htmlspecialchars($kandang['nama']) ?>">
                                        <img src="assets/images/icons/plus.png" alt="Tambah Data" class="icon-plus">
                                        <span>Tambah Data</span>
                                    </button>
                                </div>
                                <div class="charts-grid">
                                    <div class="chart-card">
                                        <div class="chart-container">
                                            <canvas id="chart-suhu-kandang-<?= $kandang['id_kandang'] ?>"></canvas>
                                        </div>
                                    </div>
                                    <div class="chart-card">
                                        <div class="chart-container">
                                            <canvas id="chart-kelembapan-kandang-<?= $kandang['id_kandang'] ?>"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada data kandang. Silakan tambahkan data kandang terlebih dahulu.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <?php include 'views/form_sensor.php'; ?>

    <!-- Scripts JS -->
    <script>
        const isLoggedIn = <?= isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'true' : 'false' ?>;
    </script>
    <script src="assets/js/sensor.js"></script>
</body>

</html>