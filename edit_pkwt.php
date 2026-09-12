<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

// Ambil daftar kantor dari database pkwt
$query_kantor = "SELECT DISTINCT kantor FROM pkwt WHERE kantor IS NOT NULL AND kantor != '' ORDER BY kantor";
$result_kantor = mysqli_query($conn, $query_kantor);
$list_kantor = [];

if ($result_kantor) {
    while ($row = mysqli_fetch_assoc($result_kantor)) {
        $list_kantor[] = $row['kantor'];
    }
}

$error = '';
$success = '';
$pkwt = [];

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id === 0) {
    header('Location: data_pkwt.php');
    exit;
}

// Ambil data PKWT berdasarkan ID
$query = "SELECT * FROM pkwt WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: data_pkwt.php');
    exit;
}

$pkwt = mysqli_fetch_assoc($result);

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_pegawai           = trim($_POST['kode_pegawai'] ?? '');
    $nama                   = trim($_POST['nama'] ?? '');
    $email                  = trim($_POST['email'] ?? '');
    $no_hp                  = trim($_POST['no_hp'] ?? '');
    $jenis_kelamin          = trim($_POST['jenis_kelamin'] ?? '');
    $nik                    = trim($_POST['nik'] ?? '');
    $tempat_lahir           = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir          = trim($_POST['tanggal_lahir'] ?? '');
    $alamat                 = trim($_POST['alamat'] ?? '');
    $jabatan                = trim($_POST['jabatan'] ?? '');
    $kantor                 = trim($_POST['kantor'] ?? '');
    $tanggal_mulai_bekerja  = trim($_POST['tanggal_mulai_bekerja'] ?? '');
    

    $tgl_mulai   = !empty($tanggal_mulai_bekerja)  ? "'$tanggal_mulai_bekerja'"  : 'NULL';
    

    if (empty($nama) || empty($email) || empty($nik)) {
        $error = 'Nama, Email, dan NIK tidak boleh kosong!';
    } else {
        $query = "UPDATE pkwt SET 
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
                  kantor='$kantor',
                  tanggal_mulai_bekerja='$tgl_mulai_bekerja'
                  WHERE id=$id";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Data PKWT berhasil diperbarui!';
            header("refresh:2;url=data_pkwt.php");
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
    <title>Edit Data PKWT</title>
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
            <a href="data_pkwt.php">📜 Data PKWT</a>
            <a href="tambah_pkwt.php">➕ Tambah PKWT</a>
            <a href="users.php">👨‍💼 Kelola User</a>
            <a href="logout.php" style="margin-top: auto; border-top: 1px solid #475569; padding-top: 20px; margin-top: 20px;">🚪 Keluar</a>
        </aside>

        <main class="main-panel">
            <div class="page-header">
                <h1>Edit Data PKWT</h1>
                <p>Perbarui informasi kontrak PKWT</p>
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
                            <input type="text" name="kode_pegawai" value="<?php echo htmlspecialchars($pkwt['kode_pegawai'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>NIK *</label>
                            <input type="text" name="nik" value="<?php echo htmlspecialchars($pkwt['nik'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($pkwt['nama'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($pkwt['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="no_hp" value="<?php echo htmlspecialchars($pkwt['no_hp'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" <?php echo (($pkwt['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo (($pkwt['jenis_kelamin'] ?? '') === 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <input type="text" name="jabatan" value="<?php echo htmlspecialchars($pkwt['jabatan'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?php echo htmlspecialchars($pkwt['tempat_lahir'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($pkwt['tanggal_lahir'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" value="<?php echo htmlspecialchars($pkwt['alamat'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid full">
                        <div class="form-group">
                            <label>Kantor</label>
                            <select name="kantor">
                                <option value="">-- Pilih Kantor --</option>
                                <?php foreach ($list_kantor as $k): ?>
                                    <option value="<?php echo htmlspecialchars($k); ?>" <?php echo (($pkwt['kantor'] ?? '') === $k) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($k); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                            <label>Tanggal Mulai Bekerja</label>
                            <input type="date" name="tanggal_mulai_bekerja" value="<?php echo htmlspecialchars($pkwt['tanggal_mulai_bekerja'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">✅ Perbarui PKWT</button>
                        <a href="data_pkwt.php" class="btn-back">← Kembali</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

