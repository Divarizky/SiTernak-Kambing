<?php
// Mengecek sesi role User atau Admin (by Login)
session_start();
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

// Mengambil data dari Database
require_once 'config/database.php';
global $koneksi; // Membuat $koneksi tersedia
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
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'SiTernak Kambing'; ?></title>
    <!-- Sertakan Chart.js dari CDN -->
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

        <!-- Konten -->
        <main id="main-content" class="main-content">
            <div class="main-header">
                <h2>Dashboard</h2>
            </div>
            <!-- Card -->
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
                <div class="section-header">
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
                        <p>Belum ada data kandang. Silakan tambahkan kandang terlebih dahulu.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Popup/Modal untuk Form Input Data -->
    <div id="sensor-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Input Data Sensor</h3>
                <span class="close-button">&times;</span>
            </div>
            <div class="modal-body">
                <form id="sensor-form">
                    <input type="hidden" id="modal-kandang-id" name="id_kandang">
                    <div class="form-group">
                        <label for="suhu">Suhu (°C)</label>
                        <input type="number" id="suhu" name="suhu" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="kelembapan">Kelembapan (%)</label>
                        <input type="number" id="kelembapan" name="kelembapan" step="1" required>
                    </div>
                    <button type="submit" class="btn-submit">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartSuhuInstances = {};
            const chartKelembapanInstances = {};

            // Fungsi untuk membuat konfigurasi dasar grafik
            function createChartConfig(title, data, color) {
                return {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: title,
                            data: data.values,
                            borderColor: color,
                            backgroundColor: `${color}33`, // Transparansi 20%
                            fill: true,
                            tension: 0.4, // Membuat garis melengkung
                            pointBackgroundColor: color,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: title,
                                align: 'start', // Judul rata kiri
                                font: {
                                    size: 15,
                                    family: 'Arial',
                                    weight: '600' // Sedikit tebal
                                },
                                color: '#353535',
                                padding: {
                                    bottom: 15
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    maxRotation: 0,
                                    minRotation: 0
                                }
                            },
                            y: {
                                grid: {
                                    color: '#e0e0e0',
                                    borderDash: [3, 3] // Garis putus-putus
                                },
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                },
                                beginAtZero: true
                            }
                        }
                    }
                };
            }

            // Fungsi untuk merender grafik
            async function renderCharts(kandangId) {
                try {
                    const response = await fetch(`api_sensor.php?id_kandang=${kandangId}`);
                    if (!response.ok) throw new Error('Gagal mengambil data sensor');
                    
                    const sensorData = await response.json();

                    const labels = sensorData.map(d => new Date(d.timestamp).toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit' }));
                    const suhuData = { labels: labels, values: sensorData.map(d => d.suhu) };
                    const kelembapanData = { labels: labels, values: sensorData.map(d => d.kelembapan) };

                    // Hancurkan instance grafik lama jika ada
                    if (chartSuhuInstances[kandangId]) chartSuhuInstances[kandangId].destroy();
                    if (chartKelembapanInstances[kandangId]) chartKelembapanInstances[kandangId].destroy();

                    // Render grafik suhu
                    const ctxSuhu = document.getElementById(`chart-suhu-kandang-${kandangId}`);
                    if (ctxSuhu) {
                        const suhuConfig = createChartConfig('Grafik Suhu (24 Jam)', suhuData, '#2E7d32');
                        chartSuhuInstances[kandangId] = new Chart(ctxSuhu, suhuConfig);
                    }

                    // Render grafik kelembapan
                    const ctxKelembapan = document.getElementById(`chart-kelembapan-kandang-${kandangId}`);
                    if (ctxKelembapan) {
                        const kelembapanConfig = createChartConfig('Grafik Kelembapan (24 Jam)', kelembapanData, '#4A7C59');
                        chartKelembapanInstances[kandangId] = new Chart(ctxKelembapan, kelembapanConfig);
                    }

                } catch (error) {
                    console.error(`Error rendering charts for kandang ${kandangId}:`, error);
                }
            }

            // Render semua grafik saat halaman dimuat
            const kandangElements = document.querySelectorAll('.kandang-chart-card');
            kandangElements.forEach(card => {
                const button = card.querySelector('.btn-add-data');
                if (button) {
                    const kandangId = button.dataset.kandangId;
                    // Sedikit penundaan untuk memastikan DOM siap
                    setTimeout(() => renderCharts(kandangId), 0);
                }
            });

            // Logika untuk Modal (Popup)
            const modal = document.getElementById('sensor-modal');
            const closeButton = document.querySelector('.close-button');
            const addButtons = document.querySelectorAll('.btn-add-data');
            const form = document.getElementById('sensor-form');
            const modalTitle = document.getElementById('modal-title');
            const modalKandangIdInput = document.getElementById('modal-kandang-id');

            addButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const kandangId = this.dataset.kandangId;
                    const kandangNama = this.dataset.kandangNama;
                    
                    modalTitle.textContent = `Input Data Sensor untuk Kandang: ${kandangNama}`;
                    modalKandangIdInput.value = kandangId;
                    
                    modal.style.display = 'block';
                });
            });

            function closeModal() {
                modal.style.display = 'none';
                form.reset();
            }

            closeButton.addEventListener('click', closeModal);
            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            });

            // Handle form submission
            form.addEventListener('submit', async function(event) {
                event.preventDefault();
                
                const formData = new FormData(form);
                const kandangId = formData.get('id_kandang');

                try {
                    const response = await fetch('proses_sensor.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        alert(result.message);
                        closeModal();
                        // Perbarui grafik yang relevan
                        renderCharts(kandangId);
                    } else {
                        alert('Error: ' + result.message);
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        });
    </script>

</body>

</html>
