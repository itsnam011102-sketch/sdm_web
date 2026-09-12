<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Proteksi: jangan hapus user yang sedang login
    $logged_in_id = $_SESSION['user']['id'] ?? 0;
    if ($logged_in_id > 0 && $logged_in_id == $id) {
        header('Location: users.php?error=self_delete');
        exit;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header('Location: users.php?msg=deleted');
exit;

