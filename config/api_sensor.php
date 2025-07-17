<?php
include 'db_connect.php';

header('Content-Type: application/json');

$id_kandang = isset($_GET['id_kandang']) ? (int)$_GET['id_kandang'] : 0;

if ($id_kandang > 0) {
    // Ambil 50 data terakhir, diurutkan dari yang paling lama ke terbaru untuk plotting yang benar
    $query = "SELECT timestamp, suhu, kelembapan FROM (
                SELECT timestamp, suhu, kelembapan 
                FROM data_sensor 
                WHERE id_kandang = ? 
                ORDER BY timestamp DESC 
                LIMIT 10
              ) AS sub ORDER BY timestamp ASC";
              
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_kandang);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
    echo json_encode($data);
    exit;
} else {
    echo json_encode([]);
    exit;
}

$koneksi->close();
