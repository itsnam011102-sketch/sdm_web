<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

// Ambil daftar unit kerja dari database
$query_unit_kerja = "SELECT DISTINCT unit_kerja FROM employe WHERE unit_kerja IS NOT NULL AND unit_kerja != '' ORDER BY unit_kerja";
$result_unit_kerja = mysqli_query($conn, $query_unit_kerja);
$list_unit_kerja = [];

if ($result_unit_kerja) {
    while ($row = mysqli_fetch_assoc($result_unit_kerja)) {
        $list_unit_kerja[] = $row['unit_kerja'];
    }
}

$error = '';
$success = '';
$pegawai = [];

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: data.php');
    exit;
}

// Ambil data pegawai berdasarkan ID
$query = "SELECT * FROM employe WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: data.php');
    exit;
}

$pegawai = mysqli_fetch_assoc($result);

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_pegawai  = trim($_POST['kode_pegawai'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $nik           = trim($_POST['nik'] ?? '');
    $tempat_lahir  = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $alamat        = trim($_POST['alamat'] ?? '');
    $jabatan       = trim($_POST['jabatan'] ?? '');
    $unit_kerja    = trim($_POST['unit_kerja'] ?? '');
    $bagian        = trim($_POST['bagian'] ?? '');

    if (empty($nama) || empty($email) || empty($nik)) {
        $error = 'Nama, Email, dan NIK tidak boleh kosong!';
    } else {
        $query = "UPDATE employe SET 
                  kode_pegawai='$kode_pegawai',
                  nama='$nama',
                  email='$email',
                  no_hp='$no_hp',
                  jenis_kelamin='$jenis_kelamin',
                  nik='$nik',
                  tempat_lahir='$tempat_lahir',
                  tanggal_lahir='$tanggal_lahir',
                  alamat='$alamat',
                  jabatan='$jabatan',
                  unit_kerja='$unit_kerja',
                  bagian='$bagian'
                  WHERE id=$id";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data pegawai berhasil diperbarui!';
            // Refresh data terbaru untuk form
            $result_refresh = mysqli_query($conn, "SELECT * FROM employe WHERE id = $id");
            if ($result_refresh) {
                $pegawai = mysqli_fetch_assoc($result_refresh);
            }
            header("refresh:2;url=data.php");
        } else {
            $error = 'Gagal memperbarui data: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pegawai</title>
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
        .form-card h2 { color: #1e293b; margin-top: 0; font-size: 24px; }
        
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #dc2626; }
        .alert-success { background: #dcfce7; color: #15803d; border-left: 4px solid #22c55e; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-grid.full { grid-template-columns: 1fr; }
        
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-weight: 600; color: #334155; margin-bottom: 8px; font-size: 14px; }
        .form-group input,
        .form-group select { 
            padding: 12px; 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus { 
            outline: none; 
            border-color: #2563eb; 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .form-actions { display: flex; gap: 10px; margin-top: 30px; }
        .btn-submit { 
            background: #2563eb; 
            color: white; 
            padding: 12px 30px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-back { 
            background: #6b7280; 
            color: white; 
            padding: 12px 30px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            transition: background 0.2s;
        }
        .btn-back:hover { background: #4b5563; }
        
        @media (max-width: 768px) {
            .dashboard-shell { flex-direction: column; }
            .sidebar { width: 100%; }
            .main-panel { padding: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .form-card { padding: 20px; }
            .page-header h1 { font-size: 22px; }
        }
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
            <a class="nav-item" href="kpi.php"></a>
            <a href="tambah_data.php">➕ Tambah Data</a>
            <a href="users.php">👨‍💼 Kelola User</a>
            <a href="logout.php" style="margin-top: auto; border-top: 1px solid #475569; padding-top: 20px; margin-top: 20px;">🚪 Keluar</a>
        </aside>

        <main class="main-panel">
            <div class="page-header">
                <h1>Edit Data Pegawai</h1>
                <p>Perbarui informasi pegawai</p>
            </div>

            <div class="form-card">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <strong>⚠️ Error:</strong> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <strong>✅ Sukses:</strong> <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Kode Pegawai *</label>
                            <input type="text" name="kode_pegawai" value="<?php echo htmlspecialchars($pegawai['kode_pegawai'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>NIK *</label>
                            <input type="text" name="nik" value="<?php echo htmlspecialchars($pegawai['nik'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($pegawai['nama'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($pegawai['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="no_hp" value="<?php echo htmlspecialchars($pegawai['no_hp'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" <?php echo (($pegawai['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo (($pegawai['jenis_kelamin'] ?? '') === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" value="<?php echo htmlspecialchars($pegawai['jabatan'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?php echo htmlspecialchars($pegawai['tempat_lahir'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($pegawai['tanggal_lahir'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" value="<?php echo htmlspecialchars($pegawai['alamat'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Unit Kerja</label>
                            <select name="unit_kerja">
                                <option value="">-- Pilih Unit Kerja --</option>
                                <?php foreach ($list_unit_kerja as $u): ?>
                                    <option value="<?php echo htmlspecialchars($u); ?>" <?php echo (($pegawai['unit_kerja'] ?? '') === $u) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($u); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Bagian</label>
                            <input type="text" name="bagian" value="<?php echo htmlspecialchars($pegawai['bagian'] ?? ''); ?>">
                        </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">✅ Perbarui Data</button>
                        <a href="data.php" class="btn-back">← Kembali</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
