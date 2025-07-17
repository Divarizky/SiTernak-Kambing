<?php
session_start();
include 'config/db_connect.php'; // Langsung ke db_connect

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kandang = $_POST['id_kandang'];
    $suhu = $_POST['suhu'];
    $kelembapan = $_POST['kelembapan'];

    if (empty($id_kandang) || !isset($suhu) || !isset($kelembapan)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi.']);
        exit;
    }

    $query = "INSERT INTO data_sensor (id_kandang, suhu, kelembapan, timestamp) VALUES (?, ?, ?, NOW())";
    $stmt = $koneksi->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("idd", $id_kandang, $suhu, $kelembapan);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mempersiapkan statement: ' . $koneksi->error]);
    }

    $koneksi->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
}
?>
