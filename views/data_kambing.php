<?php
require '../config/database.php';

$kambingList = getDataKambing();

// Mendapatkan nama file halaman saat ini
$pageTitle = 'Data Kambing';

// Mendapatkan nama file halaman saat ini
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
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
                <a href="../index.php" class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>">
                    <img class="sidebar-icon" src="../assets/images/icons/dashboard.png" alt="dashboard">
                    <span>Dashboard</span>
                </a>
                <a href="data_kambing.php" class="<?php echo $currentPage == 'data_kambing.php' ? 'active' : ''; ?>">
                    <img class="sidebar-icon" src="../assets/images/icons/goat.png" alt="goat">
                    <span>Data Kambing</span>
                </a>
                <button class="btn-logout" type="button">Logout</button>
            </nav>
        </aside>

        <!-- Konten -->
        <main id="main-content" class="main-content">
            <div class="kambing-header">
                <h2>Data Kambing</h2>
                <!-- Button Tambah Kambing -->
                <button class="btn-add-kambing" type="button">
                    <img class="plus-icon" src="../assets/images/icons/plus-white.png" alt="goat">
                    Tambah Data Kambing
                </button>
            </div>
            <div class="kambing-container">
                <p><img class="kambing-list-icon" src="../assets/images/icons/goat.png" alt="goat">Daftar Kambing (<?= count($kambingList) ?>)</p>
                <div class="kambing-list">
                    <?php if (empty($kambingList)): ?>
                        <p>Belum ada data kambing</p>
                    <?php else: ?>
                        <?php foreach ($kambingList as $row): ?>
                            <div class="kambing-card <?= strtolower($row['status_kesehatan']) == 'perlu perhatian' ? 'warning' : '' ?>">
                                <div class="kambing-info">
                                    <div class="kambing-icon">🐐</div>
                                    <div>
                                        <strong><?= str_pad($row['name'], 3, '0', STR_PAD_LEFT) ?></strong><br>
                                        <?= $row['age'] ?> bulan • <?= $row['jenis_kelamin'] ?> • <?= $row['berat'] ?: '0' ?> kg • <?= $row['ras'] ?>
                                    </div>
                                </div>
                                <div class="kambing-actions">
                                    <span class="status <?= $row['status_kesehatan'] == 'Sehat' ? 'green' : 'orange' ?>">
                                        <?= $row['status_kesehatan'] ?? 'Tidak Diketahui' ?>
                                    </span>
                                    <!-- Button Lihat, Edit, dan Hapus Kambing -->
                                    <button class="btn-lihat" data-kambing='<?= json_encode($row) ?>'>Lihat</button>
                                    <button class="btn-edit" data-kambing='<?= json_encode($row) ?>'>Edit</button>
                                    <button class="btn-hapus" data-kambing='<?= json_encode($row) ?>'>Hapus</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <?php include 'form_kambing.php'; ?>
    <script src="../assets/js/kambing.js"></script>
</body>

</html>