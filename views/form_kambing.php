<!-- Modal untuk Form Data Kambing -->
<?php
require_once '../config/database.php';

// Mengambil nama kandang berdasarkan ID dari database
$kandangList = getListKandang();
?>

<div id="kambingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Tambah Data Kambing</h2>
            <span class="close-button">&times;</span>
        </div>
        <div class="modal-body">
            <!-- menghapus action="aksi_kambing.php" -->
            <form id="formKambing" method="POST">
                <!-- Hidden input untuk menyimpan ID dan Aksi -->
                <input type="hidden" id="id_kambing" name="id_kambing">
                <input type="hidden" id="action" name="action" value="create">
                <!-- Form Nama Kambing -->
                <div class="form-group">
                    <label for="name">Nama Kambing</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <!-- Form Kandang -->
                <div class="form-group">
                    <label for="id_kandang">Kandang</label>
                    <select name="id_kandang" id="id_kandang">
                        <option value="">-- Pilih Kandang --</option>
                        <?php foreach ($kandangList as $k): ?>
                            <option value="<?= $k['id_kandang'] ?>"><?= $k['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Form Usia -->
                <div class="form-group">
                    <label for="age">Usia (Bulan)</label>
                    <input type="number" name="age" id="age">
                </div>
                <!-- Form Berat -->
                <div class="form-group">
                    <label for="berat">Berat (kg)</label>
                    <input type="number" step="0.1" name="berat" id="berat">
                </div>
                <!-- Form Tgl Lahir -->
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
                <!-- Form Jenis Kelamin -->
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Jantan">Jantan</option>
                        <option value="Betina">Betina</option>
                    </select>
                </div>
                <!-- Form Tanggal Masuk -->
                <div class="form-group">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" required>
                </div>
                <!-- Form Asal -->
                <div class="form-group">
                    <label for="asal">Asal</label>
                    <select name="asal" id="asal">
                        <option value="Lahir">Lahir</option>
                        <option value="Adopsi">Adopsi</option>
                    </select>
                </div>
                <!-- Form Ras -->
                <div class="form-group">
                    <label for="ras">Ras</label>
                    <input type="text" name="ras" id="ras">
                </div>
                <!-- Form Status Kesehatan -->
                <div class="form-group">
                    <label for="status_kesehatan">Status Kesehatan</label>
                    <select name="status_kesehatan" id="status_kesehatan">
                        <option value="Sehat">Sehat</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Perlu Perhatian">Perlu Perhatian</option>
                    </select>
                </div>
                <!-- Form Suhu -->
                <div class="form-group">
                    <label for="suhu">Suhu (°C)</label>
                    <input type="number" step="0.1" name="suhu" class="form-control" required>
                </div>
                <!-- Form Kelembapan -->
                <div class="form-group">
                    <label for="kelembapan">Kelembapan (%)</label>
                    <input type="number" name="kelembapan" min="0" max="100" class="form-control" required>
                </div>
                <!-- Form Button -->
                <div class="form-actions">
                    <button type="submit" id="btnSimpan" class="btn-simpan">Simpan</button>
                    <button type="button" class="btn-batal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>