<!-- Modal untuk Form Sensor Suhu dan Kelembapan -->
<div id="sensor-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Input Data Sensor</h2>
            <span class="close-button">&times;</span>
        </div>
        <div class="modal-body">
            <form id="sensor-form">
                <input type="hidden" id="modal-kandang-id" name="id_kandang">
                <!-- Input Suhu -->
                <div class="form-group">
                    <label for="suhu">Suhu (°C)</label>
                    <input type="number" name="suhu" id="suhu" step="0.1" required>
                </div>
                <!-- Input Kelembapan -->
                <div class="form-group">
                    <label for="kelembapan">Kelembapan (%)</label>
                    <input type="number" name="kelembapan" id="kelembapan" min="0" max="100" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-simpan">Simpan</button>
                    <button type="button" class="btn-batal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>