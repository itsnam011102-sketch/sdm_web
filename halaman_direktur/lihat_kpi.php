<?php
// ============================================
// LIHAT KPI - masukkan STB & Tahun, tampil data pegawai + nilai KPI
// File ini ada di dalam folder halaman_direktur/, sejajar dengan direktur.php
// ============================================

require_once '../config.php';

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

$stbCari     = trim($_GET['stb'] ?? '');
$periodeCari = trim($_GET['periode'] ?? '');

$pegawai = null;
$dataPerPerspektif = [];
$sudahCari = ($stbCari !== '' && $periodeCari !== '');

if ($sudahCari) {

   // 1. Ambil data pegawai dari tabel employe berdasarkan STB (kode_pegawai)
    $stmtPegawai = mysqli_prepare($koneksi, "SELECT kode_pegawai, nama, jabatan, unit_kerja, bagian, email, no_hp FROM employe WHERE kode_pegawai = ?");
    mysqli_stmt_bind_param($stmtPegawai, "s", $stbCari);
    mysqli_stmt_execute($stmtPegawai);
    $resultPegawai = mysqli_stmt_get_result($stmtPegawai);
    $pegawai = mysqli_fetch_assoc($resultPegawai);
    mysqli_stmt_close($stmtPegawai);

    // Jika tidak ditemukan di employe, cari di tabel pkwt
    if (!$pegawai) {
        $stmtPkwt = mysqli_prepare($koneksi, "SELECT kode_pegawai, nama, jabatan, unit_kerja, bagian, email, no_hp FROM pkwt WHERE kode_pegawai = ?");
        mysqli_stmt_bind_param($stmtPkwt, "s", $stbCari);
        mysqli_stmt_execute($stmtPkwt);
        $resultPkwt = mysqli_stmt_get_result($stmtPkwt);
        $pegawai = mysqli_fetch_assoc($resultPkwt);
        mysqli_stmt_close($stmtPkwt);
    }

    if ($pegawai) {
        // 2. Cari kpi_penilaian sesuai STB + Tahun
        $stmt = mysqli_prepare($koneksi, "SELECT id FROM kpi_penilaian WHERE kode_pegawai = ? AND periode = ?");
        mysqli_stmt_bind_param($stmt, "ss", $stbCari, $periodeCari);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $penilaian = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($penilaian) {
            $kpiPenilaianId = $penilaian['id'];

            $stmtDetail = mysqli_prepare($koneksi, "
                SELECT ps.nama_perspektif, ps.urutan,
                       d.no_urut, d.sasaran_strategis, d.program_inisiatif, d.indikator_kpi,
                       d.formula, d.satuan, d.polaritas, d.bobot, d.target, d.realisasi,
                       d.capaian, d.rating, d.nilai_kpi
                FROM kpi_detail d
                JOIN perspektif ps ON ps.id = d.perspektif_id
                WHERE d.kpi_penilaian_id = ?
                ORDER BY ps.urutan, d.no_urut
            ");
            mysqli_stmt_bind_param($stmtDetail, "i", $kpiPenilaianId);
            mysqli_stmt_execute($stmtDetail);
            $resultDetail = mysqli_stmt_get_result($stmtDetail);

            while ($baris = mysqli_fetch_assoc($resultDetail)) {
                $nama = $baris['nama_perspektif'];
                if (!isset($dataPerPerspektif[$nama])) {
                    $dataPerPerspektif[$nama] = ['rows' => [], 'total_bobot' => 0, 'total_nilai' => 0];
                }
                $dataPerPerspektif[$nama]['rows'][] = $baris;
                $dataPerPerspektif[$nama]['total_bobot'] += floatval($baris['bobot']);
                $dataPerPerspektif[$nama]['total_nilai'] += floatval($baris['nilai_kpi']);
            }
            mysqli_stmt_close($stmtDetail);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat KPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f7fb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .main-panel { padding: 28px; max-width: 1400px; margin: 0 auto; }
        .content-card { background: white; border-radius: 18px; padding: 30px 32px; box-shadow: 0 14px 35px rgba(15,23,42,0.08); }
    </style>
</head>
<body>
<main class="main-panel">

    <div class="content-card mb-4">
        <a href="/sdm_web/kpi.php" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
        <h4 class="fw-bold mb-3"><i class="bi bi-eye-fill me-2 text-primary"></i>Lihat Penilaian KPI</h4>

        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Masukkan STB</label>
                <input type="text" name="stb" class="form-control" placeholder="Contoh: 6381" value="<?= htmlspecialchars($stbCari) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun</label>
                <input type="number" name="periode" class="form-control" placeholder="Contoh: 2025" value="<?= htmlspecialchars($periodeCari) ?>" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    <?php if ($sudahCari): ?>

        <?php if (!$pegawai): ?>
            <div class="alert alert-danger">
                STB "<?= htmlspecialchars($stbCari) ?>" tidak ditemukan di data pegawai.
            </div>
        <?php else: ?>

            <!-- Kartu Data Pegawai -->
            <div class="content-card mb-4">
                <h5 class="fw-bold text-primary mb-3"><i class="bi bi-person-badge-fill me-2"></i>Data Pegawai</h5>
                <div class="row">
                    <div class="col-md"><strong>STB</strong><br><?= htmlspecialchars($pegawai['kode_pegawai']) ?></div>
                    <div class="col-md"><strong>Nama</strong><br><?= htmlspecialchars($pegawai['nama']) ?></div>
                    <div class="col-md"><strong>Jabatan</strong><br><?= htmlspecialchars($pegawai['jabatan'] ?? '-') ?></div>
                    <div class="col-md"><strong>Unit Kerja</strong><br><?= htmlspecialchars($pegawai['unit_kerja'] ?? '-') ?></div>
                    <div class="col-md"><strong>Bagian</strong><br><?= htmlspecialchars($pegawai['bagian'] ?? '-') ?></div>
                </div>
                <div class="mt-2 text-muted">Tahun Penilaian: <?= htmlspecialchars($periodeCari) ?></div>
            </div>

            <?php if (empty($dataPerPerspektif)): ?>
                <div class="alert alert-warning">
                    Belum ada data KPI untuk STB "<?= htmlspecialchars($stbCari) ?>" tahun "<?= htmlspecialchars($periodeCari) ?>".
                </div>
            <?php else: ?>

                <?php foreach ($dataPerPerspektif as $namaPerspektif => $kelompok): ?>
                    <div class="content-card mb-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-primary mb-0"><?= htmlspecialchars($namaPerspektif) ?></h5>
                            <span class="badge bg-secondary px-3 py-2">
                                Total Bobot: <?= number_format($kelompok['total_bobot'], 2) ?>%
                            </span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>NO</th>
                                        <th>SASARAN STRATEGIS</th>
                                        <th>PROGRAM / INISIATIF</th>
                                        <th>INDIKATOR KPI</th>
                                        <th>FORMULA</th>
                                        <th>SATUAN</th>
                                        <th>POLARITAS</th>
                                        <th>BOBOT (%)</th>
                                        <th>TARGET</th>
                                        <th>REALISASI</th>
                                        <th>CAPAIAN</th>
                                        <th>RATING</th>
                                        <th>NILAI KPI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kelompok['rows'] as $baris): ?>
                                        <tr>
                                            <td class="text-center"><?= $baris['no_urut'] ?></td>
                                            <td><?= htmlspecialchars($baris['sasaran_strategis']) ?></td>
                                            <td><?= htmlspecialchars($baris['program_inisiatif']) ?></td>
                                            <td><?= htmlspecialchars($baris['indikator_kpi']) ?></td>
                                            <td><?= htmlspecialchars($baris['formula']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($baris['satuan']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($baris['polaritas']) ?></td>
                                            <td class="text-end"><?= number_format($baris['bobot'], 2) ?></td>
                                            <td class="text-end"><?= number_format($baris['target'], 2) ?></td>
                                            <td class="text-end"><?= number_format($baris['realisasi'], 2) ?></td>
                                            <td class="text-end"><?= number_format($baris['capaian'], 2) ?>%</td>
                                            <td class="text-end"><?= number_format($baris['rating'], 2) ?></td>
                                            <td class="text-end"><?= number_format($baris['nilai_kpi'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

</main>
</body>
</html>