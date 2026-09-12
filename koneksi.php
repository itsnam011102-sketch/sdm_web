<?php
// 1. Satpam ambil catatan alamatnya dulu
require_once 'config.php';

// 2. Satpam coba buka pintu ke phpMyAdmin
$conn = mysqli_connect($host, $user, $pass, $db);

// 3. Cek pintu berhasil dibuka atau tidak
if (!$conn) {
    die("Gagal terhubung ke database phpMyAdmin: " . mysqli_connect_error());
}
?>