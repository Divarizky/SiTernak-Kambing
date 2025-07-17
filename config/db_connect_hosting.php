<?php
$host = "sql313.infinityfree.com";
$user = "if0_39485197";
$pass = "Oj4ZyhnqOA";
$db   = "if0_39485197_peternakan_kambing";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
