<?php
// 1. Koneksi Database
$conn = mysqli_connect("localhost", "root", "", "sdm_web");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$pegawai = null;
$pesan_error = "";
$kode_cari = $_GET['cari_kode'] ?? '';

// --- PROSES 1: AMBIL DATA BIODATA ---
if (!empty(trim($kode_cari))) {
    $kode_cari_clean = mysqli_real_escape_string($conn, trim($kode_cari));
    $query = mysqli_query($conn, "SELECT * FROM pkwt WHERE kode_pegawai = '$kode_cari_clean'");
    
    if (mysqli_num_rows($query) > 0) {
        $pegawai = mysqli_fetch_assoc($query);
    } else {
        $pesan_error = "Data pegawai dengan kode <strong>" . htmlspecialchars($kode_cari) . "</strong> tidak ditemukan.";
    }
}

// --- PROSES 2: TAMBAH KONTRAK ---
if (isset($_POST['tambah_kontrak'])) {
    $kode_p = mysqli_real_escape_string($conn, $_POST['kode_pegawai']);
    $no_k = mysqli_real_escape_string($conn, $_POST['no_kontrak']);
    $durasi = mysqli_real_escape_string($conn, $_POST['durasi']);
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "INSERT INTO tb_pkwt (kode_pegawai, no_kontrak, durasi, tgl_mulai, tgl_selesai, status) 
                         VALUES ('$kode_p', '$no_k', '$durasi', '$tgl_mulai', '$tgl_selesai', '$status')");
    
    header("Location: detail_pkwt.php?cari_kode=" . $kode_p);
    exit;
}

// --- PROSES 3: EDIT KONTRAK ---
if (isset($_POST['edit_kontrak'])) {
    $id_k = mysqli_real_escape_string($conn, $_POST['id_pkwt']);
    $kode_p = mysqli_real_escape_string($conn, $_POST['kode_pegawai']);
    $no_k = mysqli_real_escape_string($conn, $_POST['no_kontrak']);
    $durasi = mysqli_real_escape_string($conn, $_POST['durasi']);
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE tb_pkwt SET 
                            no_kontrak='$no_k', 
                            durasi='$durasi', 
                            tgl_mulai='$tgl_mulai', 
                            tgl_selesai='$tgl_selesai', 
                            status='$status' 
                         WHERE id_pkwt='$id_k'");
    
    header("Location: detail_pkwt.php?cari_kode=" . $kode_p);
    exit;
}

// --- PROSES 4: HAPUS KONTRAK ---
if (isset($_GET['hapus_kontrak'])) {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['hapus_kontrak']);
    mysqli_query($conn, "DELETE FROM tb_pkwt WHERE id_pkwt = '$id_hapus'");
    header("Location: detail_pkwt.php?cari_kode=" . $kode_cari);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail PKWT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

<div class="container-fluid px-4">
    <h2 class="mb-4">Pencarian Data PKWT</h2>

    <!-- FORM PENCARIAN -->
    <form method="GET" action="detail_pkwt.php" class="row mb-4">
        <div class="col-md-5">
            <div class="input-group">
                <input type="text" name="cari_kode" class="form-control" placeholder="Masukkan Kode Pegawai" value="<?= htmlspecialchars($kode_cari); ?>" required>
                <button type="submit" class="btn btn-primary">🔍 Cari</button>
                <?php if (!empty($kode_cari)) : ?>
                    <a href="detail_pkwt.php" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?> 
            </div>
        </div>
    </form>
    <a href="data_pkwt.php" class="btn btn-secondary" style="float: right;">← Kembali</a>
                <?php
// Taruh di baris paling pertama file sebelum tag HTML
if (isset($_GET['action']) && $_GET['action'] == 'back') {
    header("Location: data_pkwt.php");
    exit();
}
?>

    <?php if ($pegawai) : ?>
        <!-- 1. CARD BIODATA PEGAWAI (RESPONSIF/MENYESUAIKAN LAYAR) -->
        <div class="card shadow-sm border-primary mb-4 w-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Biodata Pegawai</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-3" width="25%">Kode Pegawai</th>
                                <td>: <?= htmlspecialchars($pegawai['kode_pegawai'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">Nama Lengkap</th>
                                <td>: <?= htmlspecialchars($pegawai['nama'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">NIK</th>
                                <td>: <?= htmlspecialchars($pegawai['nik'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">Jenis Kelamin</th>
                                <td>: <?= htmlspecialchars($pegawai['jenis_kelamin'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">Tempat, Tanggal Lahir</th>
                                <td>: 
                                    <?= htmlspecialchars($pegawai['tempat_lahir'] ?? '-'); ?><?= (!empty($pegawai['tanggal_lahir']) && $pegawai['tanggal_lahir'] != '0000-00-00' && strtotime($pegawai['tanggal_lahir']) > 0) ? ', ' . date('d/m/Y', strtotime($pegawai['tanggal_lahir'])) : ''; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3">Alamat</th>
                                <td>: <?= htmlspecialchars($pegawai['alamat'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">Email</th>
                                <td>: <?= htmlspecialchars($pegawai['email'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">No. HP</th>
                                <td>: <?= htmlspecialchars($pegawai['no_hp'] ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th class="ps-3">Jabatan</th>
                                <td>: <span class="badge bg-info text-dark"><?= htmlspecialchars($pegawai['jabatan'] ?? '-'); ?></span></td>
                            </tr>
                            <tr>
                                <th class="ps-3">Kantor</th>
                                <td>: <span class="badge bg-success"><?= htmlspecialchars($pegawai['kantor'] ?? '-'); ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. TABEL HISTORY / RIWAYAT KONTRAK PKWT -->
        <div class="card shadow-sm mb-4 w-100">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">History Kontrak PKWT</h5>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#modalTambahKontrak">+ Tambah Kontrak</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>No Kontrak</th>
                                <th>Durasi</th>
                                <th>Tgl Mulai</th>
                                <th>Tgl Selesai</th>
                                <th>Status</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $q_pkwt = mysqli_query($conn, "SELECT * FROM tb_pkwt WHERE kode_pegawai = '{$pegawai['kode_pegawai']}' ORDER BY tgl_mulai DESC");
                            $no = 1;
                            if (mysqli_num_rows($q_pkwt) > 0) :
                                while ($pkwt = mysqli_fetch_assoc($q_pkwt)) : 
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><?= htmlspecialchars($pkwt['no_kontrak']); ?></td>
                                <td><?= htmlspecialchars($pkwt['durasi']); ?></td>
                                <td><?= date('d/m/Y', strtotime($pkwt['tgl_mulai'])); ?></td>
                                <td><?= date('d/m/Y', strtotime($pkwt['tgl_selesai'])); ?></td>
                                <td>
                                    <span class="badge bg-<?= ($pkwt['status'] == 'Aktif') ? 'success' : 'secondary'; ?>">
                                        <?= htmlspecialchars($pkwt['status']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $pkwt['id_pkwt']; ?>">Edit</button>
                                    <a href="detail_pkwt.php?cari_kode=<?= $pegawai['kode_pegawai']; ?>&hapus_kontrak=<?= $pkwt['id_pkwt']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Yakin ingin menghapus riwayat kontrak ini?')">Hapus</a>
                                    <a href="detail_pkwt.php?cari_kode=TA1301&action=back" class="btn btn-secondary">← Kembali</a>
                                </td>
                            </tr>

                            <!-- MODAL EDIT KONTRAK -->
                            <div class="modal fade" id="modalEdit<?= $pkwt['id_pkwt']; ?>" tabindex="-1">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form method="POST">
                                      <input type="hidden" name="id_pkwt" value="<?= $pkwt['id_pkwt']; ?>">
                                      <input type="hidden" name="kode_pegawai" value="<?= $pegawai['kode_pegawai']; ?>">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Edit Kontrak PKWT</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                      </div>
                                      <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">No. Kontrak</label>
                                            <input type="text" name="no_kontrak" class="form-control" value="<?= htmlspecialchars($pkwt['no_kontrak']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Durasi</label>
                                            <input type="text" name="durasi" class="form-control" value="<?= htmlspecialchars($pkwt['durasi']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Mulai</label>
                                            <input type="date" name="tgl_mulai" class="form-control" value="<?= $pkwt['tgl_mulai']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Selesai</label>
                                            <input type="date" name="tgl_selesai" class="form-control" value="<?= $pkwt['tgl_selesai']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="Aktif" <?= ($pkwt['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="Selesai" <?= ($pkwt['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                            </select>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit_kontrak" class="btn btn-warning">Simpan Perubahan</button>
                                      </div>
                                  </form>
                                </div>
                              </div>
                            </div>

                            <?php 
                                endwhile;
                            else : 
                            ?>
                            <tr><td colspan="7" class="text-center text-muted p-3">Belum ada riwayat kontrak PKWT.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH KONTRAK -->
        <div class="modal fade" id="modalTambahKontrak" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                  <input type="hidden" name="kode_pegawai" value="<?= $pegawai['kode_pegawai']; ?>">
                  <div class="modal-header">
                    <h5 class="modal-title">Tambah Kontrak PKWT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">No. Kontrak</label>
                        <input type="text" name="no_kontrak" class="form-control" placeholder="Contoh: KTR/2026/001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi (misal: 1 Tahun / 6 Bulan)</label>
                        <input type="text" name="durasi" class="form-control" placeholder="Contoh: 1 Tahun" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tgl_selesai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Aktif">Aktif</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_kontrak" class="btn btn-primary">Simpan Kontrak</button>
                  </div>
              </form>
            </div>
          </div>
        </div>

    <?php elseif (!empty($pesan_error)) : ?>
        <div class="alert alert-danger w-100" role="alert">
            <?= $pesan_error; ?>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>