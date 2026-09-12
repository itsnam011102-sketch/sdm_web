<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? 'User');

    if (empty($username) || empty($password)) {
        $error = 'Username dan Password wajib diisi!';
    } else {
        // Cek ketersediaan username
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = 'Username "' . htmlspecialchars($username) . '" sudah terdaftar!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "sss", $username, $hashed_password, $role);

            if (mysqli_stmt_execute($insert_stmt)) {
                header('Location: users.php?msg=added');
                exit;
            } else {
                $error = 'Gagal menyimpan data: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User Baru</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .page-wrap { padding: 22px; }
        .panel { background: white; border-radius: 18px; padding: 28px; box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08); max-width: 600px; margin: 0 auto; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #e2e8f0; padding-bottom: 16px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #334155; margin-bottom: 8px; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; outline: none; background: #fff; }
        .form-group input:focus, .form-group select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .btn-submit { background: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 14px; }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-back { background: #e2e8f0; color: #475569; text-decoration: none; padding: 10px 18px; border-radius: 10px; font-weight: 600; font-size: 14px; }
        .btn-back:hover { background: #cbd5e1; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
        .actions { display: flex; gap: 12px; align-items: center; margin-top: 28px; }
    </style>
</head>
<body>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">SD</div>
                <h2>SDM App</h2>
            </div>
            <div class="nav-group">
                <p class="nav-title">Menu</p>
                <a class="nav-item" href="dashboard.php"><div class="nav-icon">🏠</div><span>Dashboard</span></a>
                <a class="nav-item" href="data.php"><div class="nav-icon">👥</div><span>Data Pegawai</span></a>
                <a class="nav-item" href="#"><div class="nav-icon">📊</div><span>Report</span></a>
                <a class="nav-item active" href="users.php"><div class="nav-icon">👨‍💼</div><span>Kelola User</span></a>
                <a class="nav-item" href="logout.php"><div class="nav-icon">🚪</div><span>Keluar</span></a>
            </div>
        </aside>

        <main class="main-panel">
            <div class="page-wrap">
                <div class="panel">
                    <div class="panel-header">
                        <h2>➕ Tambah User Baru</h2>
                        <a href="users.php" class="btn-back">← Kembali</a>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Masukkan username..." value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Masukkan password..." required>
                        </div>

                        <div class="form-group">
                            <label for="role">Role / Level Akses</label>
                            <select id="role" name="role" required>
                                <option value="User" <?php echo (($_POST['role'] ?? '') === 'User') ? 'selected' : ''; ?>>User / Staf</option>
                                <option value="Administrator" <?php echo (($_POST['role'] ?? '') === 'Administrator') ? 'selected' : ''; ?>>Administrator</option>
                            </select>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn-submit">💾 Simpan User</button>
                            <a href="users.php" class="btn-back">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

