<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: data.php');
    exit;
}

// Query untuk hapus data
$query = "DELETE FROM employe WHERE id = $id";

if (mysqli_query($conn, $query)) {
    // Sukses hapus, redirect ke data.php
    header('Location: data.php?pesan=Data berhasil dihapus');
    exit;
} else {
    // Error hapus, redirect dengan pesan error
    header('Location: data.php?error=Gagal menghapus data');
    exit;
}
?>
