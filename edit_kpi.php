<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

$error = '';
$success = '';
$kpi_data = [];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) {
    header('Location: kpi.php');
    exit;
}

$query = "SELECT * FROM kpi WHERE id = $id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: kpi.php');
    exit;
}

$kpi_data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_pegawai = trim($_POST['kode_pegawai'] ?? '');
    $nama         = trim($_POST['nama'] ?? '');
    $periode      = trim($_POST['periode'] ?? '');
    $target_kpi   = (float)($_POST['target_kpi'] ?? 100);
    $capaian_kpi  = (float)($_POST['capaian_kpi'] ?? 0);
    
    $skor = ($target_kpi > 0) ? round(($capaian_kpi / $target_kpi) * 100, 2) : 0;
    
    $status = 'Sangat Baik';
    if ($skor < 70) {
        $status = 'Kurang';
    } elseif ($skor < 85) {
        $status = 'Cukup';
    } elseif ($skor < 100) {
        $status = 'Baik';
    }

    if (empty($nama) || empty($periode)) {
        $error = 'Nama Pegawai dan Periode wajib diisi!';
    } else {
        $query = "UPDATE kpi SET 
                  kode_pegawai='$kode_pegawai',
                  nama='$nama',
                  periode='$periode',
                  target_kpi='$target_kpi',
                  capaian_kpi='$capaian_kpi',
                  skor='$skor',
                  status='$status'
                  WHERE id=$id";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data KPI berhasil diperbarui!';
            header("refresh:2;url=kpi.php");
        } else {
            $error = 'Gagal memperbarui data KPI: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data KPI</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        * { box-sizing: border-box; }
        body { background: #f8fafc; }
        .dashboard-shell { display: flex; min-height: 100vh; }
        .sidebar { width: 200px; background: #1e293b; padding: 20px; color: white; }
        .sidebar h2 { margin: 10px 0; font-size: 18px; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: white; margin: 5px 0; border-radius: 5px; }
        .sidebar a:hover { background: #334155; }
        .main-panel { flex: 1; padding: 30px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 28px; margin: 0 0 5px 0; color: #1e293b; }
        .page-header p { color: #64748b; margin: 0; }
        .form-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); width: 100%; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #dc2626; }
        .alert-success { background: #dcfce7; color: #15803d; border-left: 4px solid #22c55e; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-grid.full { grid-template-columns: 1fr; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: 600; color: #334155; margin-bottom: 8px; font-size: 14px; }
        .form-group input, .form-group select { padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; }
        .form-actions { display: flex; gap: 10px; margin-top: 30px; }
        .btn-submit { background: #2563eb; color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px; }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-back { background: #6b7280; color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; }
        .btn-back:hover { background: #4b5563; }
    </style>
</head>
<body>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="background: #3b82f6; width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: bold; font-size: 20px;">SD</div>
                <h2>SDM App</h2>
            </div>
            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="data.php">👥 Data Pegawai</a>
            <a href="data_pkwt.php">📜 Data PKWT</a>
            <a href="kpi.php" style="background: #3b82f6;">📊 Data KPI</a>
            <a href="users.php">👨‍💼 Kelola User</a>
            <a href="logout.php" style="margin-top: auto; border-top: 1px solid #475569; padding-top: 20px; margin-top: 20px;">🚪 Keluar</a>
        </aside>

        <main class="main-panel">
            <div class="page-header">
                <h1>Edit Data KPI</h1>
                <p>Perbarui capaian KPI pegawai</p>
            </div>

            <div class="form-card">
                <?php if ($error): ?>
                    <div class="alert alert-error"><strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><strong>✅ Sukses:</strong> <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Kode Pegawai</label>
                            <input type="text" name="kode_pegawai" value="<?php echo htmlspecialchars($kpi_data['kode_pegawai'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nama Pegawai *</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($kpi_data['nama'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Periode Penilaian *</label>
                            <input type="text" name="periode" value="<?php echo htmlspecialchars($kpi_data['periode'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Target KPI</label>
                            <input type="number" step="0.01" name="target_kpi" value="<?php echo htmlspecialchars($kpi_data['target_kpi'] ?? 100); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Capaian Realisasi</label>
                            <input type="number" step="0.01" name="capaian_kpi" value="<?php echo htmlspecialchars($kpi_data['capaian_kpi'] ?? 0); ?>" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">✅ Perbarui Data KPI</button>
                        <a href="kpi.php" class="btn-back">← Kembali</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

