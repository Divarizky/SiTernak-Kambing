<?php
// Konfigurasi database untuk deploy
// $host = "sql311.infinityfree.com";
// $user = "if0_39487263";
// $pass = "XX7NGiAkS9";
// $db   = "if0_39487263_peternak_kambing";

// Local
$host = "localhost"; // default: localhost
$user = "root"; // default: root
$pass = ""; // default: ""
$db   = "peternakan_kambing"; // default: peternak_kambing

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
