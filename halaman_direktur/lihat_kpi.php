<?php
// ============================================
// LIHAT KPI - pilih Divisi & Periode dulu, baru data muncul
// File ini ada di dalam folder halaman_direktur/, sejajar dengan direktur.php
// ============================================

require_once '../config.php';

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

// ---------- Ambil daftar Divisi & Periode yang sudah pernah disimpan (untuk dropdown) ----------
$daftarDivisi  = [];
$resDivisi = mysqli_query($koneksi, "SELECT DISTINCT divisi FROM kpi_penilaian ORDER BY divisi");
while ($row = mysqli_fetch_assoc($resDivisi)) {
    $daftarDivisi[] = $row['divisi'];
}

$daftarPeriode = [];
$resPeriode = mysqli_query($koneksi, "SELECT DISTINCT periode FROM kpi_penilaian ORDER BY periode DESC");
while ($row = mysqli_fetch_assoc($resPeriode)) {
    $daftarPeriode[] = $row['periode'];
}

// ---------- Ambil pilihan user dari form (GET) ----------
$divisiPilih  = $_GET['divisi'] ?? '';
$periodePilih = $_GET['periode'] ?? '';
$dataPerPerspektif = []; // hasil akhir yang mau ditampilkan
$kpiPenilaianId = null;

if ($divisiPilih !== '' && $periodePilih !== '') {

    // Cari id kpi_penilaian sesuai divisi + periode yang dipilih
    $stmt = mysqli_prepare($koneksi, "SELECT id, created_at, updated_at FROM kpi_penilaian WHERE divisi = ? AND periode = ?");
    mysqli_stmt_bind_param($stmt, "ss", $divisiPilih, $periodePilih);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $penilaian = mysqli_fetch_assoc($result);

    if ($penilaian) {
        $kpiPenilaianId = $penilaian['id'];

        // Ambil semua detail, join ke perspektif, urutkan per perspektif lalu per no_urut
        $stmtDetail = mysqli_prepare($koneksi, "
            SELECT ps.id AS perspektif_id, ps.nama_perspektif, ps.urutan,
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
            $namaPerspektif = $baris['nama_perspektif'];
            if (!isset($dataPerPerspektif[$namaPerspektif])) {
                $dataPerPerspektif[$namaPerspektif] = [
                    'rows' => [],
                    'total_bobot' => 0
                ];
            }
            $dataPerPerspektif[$namaPerspektif]['rows'][] = $baris;
            $dataPerPerspektif[$namaPerspektif]['total_bobot'] += floatval($baris['bobot']);
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
        <h4 class="fw-bold mb-3"><i class="bi bi-eye-fill me-2 text-primary"></i>Lihat Penilaian KPI</h4>

        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Divisi</label>
                <select name="divisi" class="form-select" required>
                    <option value="">-- Pilih Divisi --</option>
                    <?php foreach ($daftarDivisi as $d): ?>
                        <option value="<?= htmlspecialchars($d) ?>" <?= ($d === $divisiPilih) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Periode</label>
                <select name="periode" class="form-select" required>
                    <option value="">-- Pilih Periode --</option>
                    <?php foreach ($daftarPeriode as $p): ?>
                        <option value="<?= htmlspecialchars($p) ?>" <?= ($p == $periodePilih) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>

    <?php if ($divisiPilih !== '' && $periodePilih !== ''): ?>

        <?php if (empty($dataPerPerspektif)): ?>
            <div class="alert alert-warning">
                Belum ada data KPI untuk Divisi "<?= htmlspecialchars($divisiPilih) ?>" periode "<?= htmlspecialchars($periodePilih) ?>".
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

</main>
</body>
</html>
