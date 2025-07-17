document.addEventListener('DOMContentLoaded', function () {
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
            const response = await fetch(`config/api_sensor.php?id_kandang=${kandangId}`);
            if (!response.ok) throw new Error('Gagal mengambil data sensor');

            const sensorData = await response.json();

            const labels = sensorData.map(d => new Date(d.timestamp).toLocaleString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }));
            const suhuData = {
                labels: labels,
                values: sensorData.map(d => d.suhu)
            };
            const kelembapanData = {
                labels: labels,
                values: sensorData.map(d => d.kelembapan)
            };

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

    // Tombol Tambah Data Sensor
    addButtons.forEach(button => {
        button.addEventListener('click', function () {
            if (typeof isLoggedIn !== "undefined" && !isLoggedIn) {
                window.location.href = "views/auth/login.php";
                return;
            }

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
    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            closeModal();
        }
    });

    // Handle form submission
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const formData = new FormData(form);
        const kandangId = formData.get('id_kandang');

        try {
            const response = await fetch('config/proses_sensor.php', {
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