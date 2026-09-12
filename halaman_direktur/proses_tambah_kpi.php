<?php
// ============================================
// PROSES SIMPAN FORM KPI DIREKTUR
// Menyimpan header (kpi_penilaian) + detail per perspektif (kpi_detail)
// File ini ada di dalam folder halaman_direktur/, sejajar dengan direktur.php
// ============================================

require_once '../config.php'; // ambil $host, $user, $pass, $db

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Akses tidak valid.');
}

// ---------- 1. Ambil data header ----------
$divisi  = trim($_POST['divisi'] ?? '');
$periode = trim($_POST['periode'] ?? '');

if ($divisi === '' || $periode === '') {
    die('Divisi dan Periode wajib diisi.');
}

// ---------- 2. Ambil semua array baris ----------
$sasaran    = $_POST['sasaran_strategis'] ?? [];
$program    = $_POST['program_inisiatif'] ?? [];
$kpiNama    = $_POST['kpi'] ?? [];
$formula    = $_POST['formula'] ?? [];
$satuan     = $_POST['satuan'] ?? [];
$polaritas  = $_POST['polaritas'] ?? [];
$bobot      = $_POST['bobot'] ?? [];
$target     = $_POST['target'] ?? [];
$realisasi  = $_POST['realisasi'] ?? [];
$capaian    = $_POST['capaian'] ?? [];
$rating     = $_POST['rating'] ?? [];
$nilaiKpi   = $_POST['nilai_kpi'] ?? [];
$perspektif = $_POST['perspektif'] ?? []; // WAJIB ada di setiap baris (hidden input di HTML)

$totalBaris = count($sasaran);

// Mapping nama perspektif (dari form) -> id di tabel master `perspektif`
$mapPerspektif = [
    'perspektif_keuangan'                  => 1,
    'perspektif_pelanggan'                 => 2,
    'perspektif_proses_bisnis_internal'    => 3,
    'perspektif_pembelajaran_pertumbuhan'  => 4,
];

mysqli_begin_transaction($koneksi);

try {
    // ---------- 3. Simpan / update header kpi_penilaian ----------
    $stmtHeader = mysqli_prepare(
        $koneksi,
        "INSERT INTO kpi_penilaian (divisi, periode) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE updated_at = NOW()"
    );
    mysqli_stmt_bind_param($stmtHeader, "ss", $divisi, $periode);
    mysqli_stmt_execute($stmtHeader);
    mysqli_stmt_close($stmtHeader);

    // Ambil id kpi_penilaian (baik baru dibuat maupun sudah ada sebelumnya)
    $stmtGetId = mysqli_prepare(
        $koneksi,
        "SELECT id FROM kpi_penilaian WHERE divisi = ? AND periode = ?"
    );
    mysqli_stmt_bind_param($stmtGetId, "ss", $divisi, $periode);
    mysqli_stmt_execute($stmtGetId);
    $resultId = mysqli_stmt_get_result($stmtGetId);
    $rowId = mysqli_fetch_assoc($resultId);
    mysqli_stmt_close($stmtGetId);

    if (!$rowId) {
        throw new Exception("Gagal mengambil ID penilaian.");
    }
    $kpiPenilaianId = $rowId['id'];

    // Kalau divisi+periode ini sudah pernah disimpan sebelumnya,
    // hapus dulu detail lama supaya tidak dobel (replace total).
    $stmtHapus = mysqli_prepare($koneksi, "DELETE FROM kpi_detail WHERE kpi_penilaian_id = ?");
    mysqli_stmt_bind_param($stmtHapus, "i", $kpiPenilaianId);
    mysqli_stmt_execute($stmtHapus);
    mysqli_stmt_close($stmtHapus);

    // ---------- 4. Insert semua baris ke kpi_detail ----------
    $stmtDetail = mysqli_prepare(
        $koneksi,
        "INSERT INTO kpi_detail
            (kpi_penilaian_id, perspektif_id, no_urut, sasaran_strategis, program_inisiatif,
             indikator_kpi, formula, satuan, polaritas, bobot, target, realisasi, capaian, rating, nilai_kpi)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
    );

    $counterPerspektif = []; // penomoran ulang (no_urut) per perspektif

    for ($i = 0; $i < $totalBaris; $i++) {

        // Lewati baris yang benar-benar kosong
        $sasaranBaris = trim($sasaran[$i] ?? '');
        $kpiBaris     = trim($kpiNama[$i] ?? '');
        if ($sasaranBaris === '' && $kpiBaris === '') {
            continue;
        }

        $perspektifKey = $perspektif[$i] ?? 'perspektif_keuangan';
        $perspektifId  = $mapPerspektif[$perspektifKey] ?? 1;

        if (!isset($counterPerspektif[$perspektifId])) {
            $counterPerspektif[$perspektifId] = 0;
        }
        $counterPerspektif[$perspektifId]++;
        $noUrut = $counterPerspektif[$perspektifId];

        $programBaris   = $program[$i] ?? '';
        $formulaBaris   = $formula[$i] ?? '';
        $satuanBaris    = $satuan[$i] ?? 'Rp';
        $polaritasBaris = $polaritas[$i] ?? 'Max';

        $bobotVal     = floatval($bobot[$i] ?? 0);
        $targetVal    = floatval($target[$i] ?? 0);
        $realisasiVal = floatval($realisasi[$i] ?? 0);
        $capaianVal   = floatval(str_replace('%', '', $capaian[$i] ?? '0'));
        $ratingVal    = floatval($rating[$i] ?? 0);
        $nilaiKpiVal  = floatval($nilaiKpi[$i] ?? 0);

        mysqli_stmt_bind_param(
            $stmtDetail,
            "iiissssssdddddd",
            $kpiPenilaianId,
            $perspektifId,
            $noUrut,
            $sasaranBaris,
            $programBaris,
            $kpiBaris,
            $formulaBaris,
            $satuanBaris,
            $polaritasBaris,
            $bobotVal,
            $targetVal,
            $realisasiVal,
            $capaianVal,
            $ratingVal,
            $nilaiKpiVal
        );
        mysqli_stmt_execute($stmtDetail);
    }

    mysqli_stmt_close($stmtDetail);
    mysqli_commit($koneksi);

    header("Location: direktur.php?status=sukses");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    die("Terjadi kesalahan saat menyimpan data: " . $e->getMessage());
}