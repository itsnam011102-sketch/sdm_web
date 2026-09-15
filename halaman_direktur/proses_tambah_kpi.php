<?php
// ============================================
// PROSES SIMPAN FORM KPI - berbasis STB (kode_pegawai)
// File ini ada di dalam folder halaman_direktur/, sejajar dengan direktur.php
// ============================================

require_once '../config.php';

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Akses tidak valid.');
}

// ---------- 1. Ambil STB & Periode ----------
$stb     = trim($_POST['stb'] ?? '');
$periode = trim($_POST['periode'] ?? '');

if ($stb === '' || $periode === '') {
    die('STB dan Tahun wajib diisi.');
}

// Pastikan STB itu benar-benar ada di tabel employe
// 1. Cek STB di tabel employe terlebih dahulu
$stmtCekPegawai = mysqli_prepare($koneksi, "SELECT kode_pegawai, nama FROM employe WHERE kode_pegawai = ?");
mysqli_stmt_bind_param($stmtCekPegawai, "s", $stb);
mysqli_stmt_execute($stmtCekPegawai);
$resultCek = mysqli_stmt_get_result($stmtCekPegawai);
$pegawai = mysqli_fetch_assoc($resultCek);
mysqli_stmt_close($stmtCekPegawai);

// 2. Jika tidak ada di employe, cari di tabel pkwt
if (!$pegawai) {
    $stmtCekPkwt = mysqli_prepare($koneksi, "SELECT kode_pegawai, nama FROM pkwt WHERE kode_pegawai = ?");
    mysqli_stmt_bind_param($stmtCekPkwt, "s", $stb);
    mysqli_stmt_execute($stmtCekPkwt);
    $resultPkwt = mysqli_stmt_get_result($stmtCekPkwt);
    $pegawai = mysqli_fetch_assoc($resultPkwt);
    mysqli_stmt_close($stmtCekPkwt);
}

// 3. Jika di kedua tabel tidak ditemukan, berikan pesan error
if (!$pegawai) {
    die('STB "' . htmlspecialchars($stb) . '" tidak ditemukan di data pegawai (Employe & PKWT). Cek kembali nomornya.');
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

$mapPerspektif = [
    'perspektif_keuangan'                  => 1,
    'perspektif_pelanggan'                 => 2,
    'perspektif_proses_bisnis_internal'    => 3,
    'perspektif_pembelajaran_pertumbuhan'  => 4,
];

mysqli_begin_transaction($koneksi);

try {
    // ---------- 3. Simpan / update header kpi_penilaian (kunci: kode_pegawai + periode) ----------
    $stmtHeader = mysqli_prepare(
        $koneksi,
        "INSERT INTO kpi_penilaian (kode_pegawai, periode) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE updated_at = NOW()"
    );
    mysqli_stmt_bind_param($stmtHeader, "ss", $stb, $periode);
    mysqli_stmt_execute($stmtHeader);
    mysqli_stmt_close($stmtHeader);

    $stmtGetId = mysqli_prepare(
        $koneksi,
        "SELECT id FROM kpi_penilaian WHERE kode_pegawai = ? AND periode = ?"
    );
    mysqli_stmt_bind_param($stmtGetId, "ss", $stb, $periode);
    mysqli_stmt_execute($stmtGetId);
    $resultId = mysqli_stmt_get_result($stmtGetId);
    $rowId = mysqli_fetch_assoc($resultId);
    mysqli_stmt_close($stmtGetId);

    if (!$rowId) {
        throw new Exception("Gagal mengambil ID penilaian.");
    }
    $kpiPenilaianId = $rowId['id'];

    // Hapus detail lama supaya tidak dobel (replace total)
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

    $counterPerspektif = [];

    for ($i = 0; $i < $totalBaris; $i++) {

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