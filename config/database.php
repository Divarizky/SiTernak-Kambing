<?php
require_once 'db_connect.php';

// Membersihkan input data 
function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

// Ambil semua data kambing
function getDataKambing()
{
    global $koneksi;
    $sql = "
        SELECT 
            k.id_kambing, k.name, k.age, k.id_kandang, k.tanggal_lahir, k.jenis_kelamin,
            k.tanggal_masuk, k.asal, k.ras, k.berat, k.status_kesehatan,
            (SELECT berat_kg FROM berat_kambing WHERE id_kambing = k.id_kambing ORDER BY tanggal DESC LIMIT 1) AS berat_terakhir,
            (SELECT suhu FROM data_sensor WHERE id_kandang = k.id_kandang ORDER BY timestamp DESC LIMIT 1) AS suhu,
            (SELECT kelembapan FROM data_sensor WHERE id_kandang = k.id_kandang ORDER BY timestamp DESC LIMIT 1) AS kelembapan

        FROM kambing k
        ORDER BY k.id_kambing DESC
    ";
    $result = $koneksi->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getKambingById($id)
{
    global $koneksi;
    $id = sanitize($id);
    $sql = "SELECT * FROM kambing WHERE id_kambing = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getListKandang()
{
    global $koneksi;
    $data = [];
    $sql = "
        SELECT 
            k.id_kandang, k.nama,
            (SELECT suhu FROM data_sensor WHERE id_kandang = k.id_kandang ORDER BY timestamp DESC LIMIT 1) AS suhu_terakhir,
            (SELECT kelembapan FROM data_sensor WHERE id_kandang = k.id_kandang ORDER BY timestamp DESC LIMIT 1) AS kelembapan_terakhir
        FROM kandang k
        ORDER BY k.nama ASC
    ";
    $result = $koneksi->query($sql);
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function createDataKambing($data)
{
    global $koneksi;
    $name = sanitize($data['name']);
    $id_kandang = sanitize($data['id_kandang']);
    $age = sanitize($data['age']);
    $berat = sanitize($data['berat']);
    $tanggal_lahir = sanitize($data['tanggal_lahir']);
    $jenis_kelamin = sanitize($data['jenis_kelamin']);
    $tanggal_masuk = sanitize($data['tanggal_masuk']);
    $asal = sanitize($data['asal']);
    $ras = sanitize($data['ras']);
    $status_kesehatan = sanitize($data['status_kesehatan']);
    $suhu = sanitize($data['suhu']);
    $kelembapan = sanitize($data['kelembapan']);

    $sql = "INSERT INTO kambing (name, id_kandang, age, berat, tanggal_lahir, jenis_kelamin, tanggal_masuk, asal, ras, status_kesehatan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("siidssssss", $name, $id_kandang, $age, $berat, $tanggal_lahir, $jenis_kelamin, $tanggal_masuk, $asal, $ras, $status_kesehatan);
    $success = $stmt->execute();

    if ($success) {
        $stmt_sensor = $koneksi->prepare("INSERT INTO data_sensor (id_kandang, timestamp, suhu, kelembapan) VALUES (?, NOW(), ?, ?)");
        $stmt_sensor->bind_param("idd", $id_kandang, $suhu, $kelembapan);
        $stmt_sensor->execute();
    }

    return $success;
}

function updateDataKambing($id, $data)
{
    global $koneksi;
    $id = sanitize($id);
    $name = sanitize($data['name']);
    $id_kandang = sanitize($data['id_kandang']);
    $age = sanitize($data['age']);
    $berat = sanitize($data['berat']);
    $tanggal_lahir = sanitize($data['tanggal_lahir']);
    $jenis_kelamin = sanitize($data['jenis_kelamin']);
    $tanggal_masuk = sanitize($data['tanggal_masuk']);
    $asal = sanitize($data['asal']);
    $ras = sanitize($data['ras']);
    $status_kesehatan = sanitize($data['status_kesehatan']);
    $suhu = floatval(sanitize($data['suhu']));
    $kelembapan = intval(sanitize($data['kelembapan']));

    $sql = "UPDATE kambing SET 
                name = ?, 
                id_kandang = ?, 
                age = ?, 
                berat = ?, 
                tanggal_lahir = ?, 
                jenis_kelamin = ?, 
                tanggal_masuk = ?, 
                asal = ?, 
                ras = ?, 
                status_kesehatan = ?
            WHERE id_kambing = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("siidssssssi", $name, $id_kandang, $age, $berat, $tanggal_lahir, $jenis_kelamin, $tanggal_masuk, $asal, $ras, $status_kesehatan, $id);
    $success = $stmt->execute();

    if ($success) {
        $stmtCek = $koneksi->prepare("SELECT suhu, kelembapan FROM data_sensor WHERE id_kandang = ? ORDER BY timestamp DESC LIMIT 1");
        $stmtCek->bind_param("i", $id_kandang);
        $stmtCek->execute();
        $resultCek = $stmtCek->get_result();
        $latest = $resultCek->fetch_assoc();

        if (
            $latest && (
                round($suhu, 1) !== round(floatval($latest['suhu']), 1) ||
                $kelembapan !== intval($latest['kelembapan'])
            )
        ) {
            $stmt_sensor = $koneksi->prepare("INSERT INTO data_sensor (id_kandang, timestamp, suhu, kelembapan) VALUES (?, NOW(), ?, ?)");
            $stmt_sensor->bind_param("idd", $id_kandang, $suhu, $kelembapan);
            $stmt_sensor->execute();
        }
    }

    return $success;
}

function deleteDataKambing($id)
{
    global $koneksi;
    $id = sanitize($id);

    // ambil id_kandang dari kambing
    $stmt0 = $koneksi->prepare("SELECT id_kandang FROM kambing WHERE id_kambing = ?");
    $stmt0->bind_param("i", $id);
    $stmt0->execute();
    $result = $stmt0->get_result();
    $kambing = $result->fetch_assoc();
    $id_kandang = $kambing['id_kandang'] ?? null;

    // hapus data berat
    $stmt1 = $koneksi->prepare("DELETE FROM berat_kambing WHERE id_kambing = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    // hapus data riwayat kesehatan
    $stmt2 = $koneksi->prepare("DELETE FROM riwayat_kesehatan WHERE id_kambing = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    // hapus data kambing
    $stmt3 = $koneksi->prepare("DELETE FROM kambing WHERE id_kambing = ?");
    $stmt3->bind_param("i", $id);
    $success = $stmt3->execute();

    // Fungsi untuk menghapus data sensor hanya jika tidak ada kambing lain yang pakai kandang itu
    // if ($success && $id_kandang !== null) {
    //     $stmtCek = $koneksi->prepare("SELECT COUNT(*) as jumlah FROM kambing WHERE id_kandang = ?");
    //     $stmtCek->bind_param("i", $id_kandang);
    //     $stmtCek->execute();
    //     $resultCek = $stmtCek->get_result();
    //     $jumlah = $resultCek->fetch_assoc()['jumlah'];

    //     if ($jumlah == 0) {
    //         $stmt4 = $koneksi->prepare("DELETE FROM data_sensor WHERE id_kandang = ?");
    //         $stmt4->bind_param("i", $id_kandang);
    //         $stmt4->execute();
    //     }
    // }

    return $success;
}

function getDataSensor($id_kandang = null)
{
    global $koneksi;
    $data = [];

    if ($id_kandang !== null) {
        $id_kandang = sanitize($id_kandang);
        $sql = "SELECT * FROM data_sensor WHERE id_kandang = ? ORDER BY timestamp DESC";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id_kandang);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = "SELECT * FROM data_sensor ORDER BY timestamp DESC";
        $result = $koneksi->query($sql);
    }

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

function getDataSensorByKandang($id_kandang)
{
    global $koneksi;
    $id_kandang = sanitize($id_kandang);
    $sql = "SELECT suhu, kelembapan FROM data_sensor WHERE id_kandang = ? ORDER BY timestamp DESC LIMIT 1";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_kandang);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getTotalKambing()
{
    global $koneksi;
    $result = $koneksi->query("SELECT COUNT(*) as total FROM kambing");
    return $result ? $result->fetch_assoc()['total'] : 0;
}

function getKesehatanKambing()
{
    global $koneksi;
    $result = $koneksi->query("SELECT COUNT(*) as jumlah FROM kambing WHERE status_kesehatan != 'Sehat'");
    return $result ? $result->fetch_assoc()['jumlah'] : 0;
}


// === Handler request POST ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $action = $_POST['action'] ?? '';
    $id = $_POST['id_kambing'] ?? null;
    $response = ['success' => false, 'message' => 'Aksi tidak valid atau tidak ada data yang dikirim.'];

    try {
        if ($action === 'create') {
            $success = createDataKambing($_POST);
            $response = [
                'success' => $success,
                'message' => $success ? 'Data kambing berhasil ditambahkan.' : 'Gagal menambah data kambing.'
            ];
        } elseif ($action === 'update' && $id) {
            $success = updateDataKambing($id, $_POST);
            $response = [
                'success' => $success,
                'message' => $success ? 'Data kambing berhasil diperbarui.' : 'Gagal memperbarui data kambing.'
            ];
        } elseif ($action === 'delete' && $id) {
            $success = deleteDataKambing($id);
            $response = [
                'success' => $success,
                'message' => $success ? 'Data kambing berhasil dihapus.' : 'Gagal menghapus data kambing.'
            ];
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }

    echo json_encode($response);
    exit;
}
