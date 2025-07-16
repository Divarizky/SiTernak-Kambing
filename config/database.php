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
        SELECT k.id_kambing, k.name, k.age, k.id_kandang, k.tanggal_lahir, k.jenis_kelamin,
        k.tanggal_masuk, k.asal, k.ras, k.berat, k.status_kesehatan,
        (SELECT berat_kg FROM berat_kambing WHERE id_kambing = k.id_kambing ORDER BY tanggal DESC LIMIT 1) AS berat_terakhir
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
    $result = $koneksi->query("SELECT id_kandang, nama FROM kandang ORDER BY nama ASC");
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

    $sql = "INSERT INTO kambing (name, id_kandang, age, berat, tanggal_lahir, jenis_kelamin, tanggal_masuk, asal, ras, status_kesehatan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("siidssssss", $name, $id_kandang, $age, $berat, $tanggal_lahir, $jenis_kelamin, $tanggal_masuk, $asal, $ras, $status_kesehatan);
    return $stmt->execute();
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
    return $stmt->execute();
}

function deleteDataKambing($id)
{
    global $koneksi;
    $id = sanitize($id);

    $stmt1 = $koneksi->prepare("DELETE FROM berat_kambing WHERE id_kambing = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    $stmt2 = $koneksi->prepare("DELETE FROM riwayat_kesehatan WHERE id_kambing = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    $stmt3 = $koneksi->prepare("DELETE FROM kambing WHERE id_kambing = ?");
    $stmt3->bind_param("i", $id);
    return $stmt3->execute();
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
