<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form KPI Divisi</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        /* ===== LAYOUT ===== */
        .dashboard-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            min-width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #e5e7eb;
            padding: 24px 18px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            padding: 10px 12px;
        }
        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 0.75rem;
            color: white;
            flex-shrink: 0;
        }
        .brand h2 { font-size: 1.1rem; color: white; margin: 0; }

        .nav-group { margin-bottom: 28px; }
        .nav-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94a3b8;
            margin: 0 12px 12px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: #dbeafe;
            margin-bottom: 4px;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.92rem;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(96, 165, 250, 0.18);
            color: white;
        }
        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            display: grid;
            place-items: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* ===== MAIN PANEL ===== */
        .main-panel {
            flex: 1;
            padding: 28px;
            overflow-x: hidden;
        }

        /* ===== CUSTOM NAVBAR TOP ===== */
        .top-kpi-navbar {
            background: linear-gradient(135deg, #010d35 0%, #3b82f6 100%);
            border-radius: 14px;
            padding: 10px 20px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);
        }

        .kpi-nav-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            position: relative;
        }

        .kpi-nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0 auto;
        }

        .kpi-nav-item {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
            padding: 8px 18px;
            border-radius: 10px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
        }

        .kpi-nav-item:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
        }

        .kpi-nav-item.active {
            color: #1e3a8a;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .kpi-btn-back {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 7px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .kpi-btn-back:hover {
            background: #ffffff;
            color: #1e40af;
        }

        .content-card {
            background: white;
            border-radius: 18px;
            padding: 30px 32px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
            width: 100%;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; min-width: 80px; padding: 18px 8px; }
            .brand h2, .nav-title, .nav-item span { display: none; }
            .nav-item { justify-content: center; padding: 12px 8px; }
            .brand { justify-content: center; padding: 10px 0; }
        }
        @media (max-width: 768px) {
            .kpi-nav-container { flex-direction: column; gap: 12px; }
            .kpi-nav-links { flex-wrap: wrap; justify-content: center; }
            .kpi-btn-back { width: 100%; justify-content: center; }
        }
        @media (max-width: 640px) {
            .dashboard-shell { flex-direction: column; }
            .sidebar { width: 100%; height: auto; min-width: unset; flex-direction: row; flex-wrap: nowrap; overflow-x: auto; padding: 10px 12px; }
            .nav-group { display: flex; flex-direction: row; gap: 4px; margin: 0; }
            .brand { margin-bottom: 0; margin-right: 12px; }
            .nav-item { padding: 8px 10px; min-width: auto; }
            .main-panel { padding: 16px; }
        }
    </style>
</head>
<body>

    <main class="main-panel">

        <!-- NAVBAR -->
        <nav class="top-kpi-navbar mb-4">
            <div class="kpi-nav-container">
                <a class="kpi-nav-item" href="../kpi.php">
                   <i class="bi bi-diagram-3-fill me-1"></i> HOME
                </a>
                <a class="kpi-nav-item" href="halaman_direktur/direktur.php">
                    <i class="bi bi-person-badge-fill me-1"></i> DIREKTUR
                </a>
                <a class="kpi-nav-item" href="divisi.php">
                    <i class="bi bi-diagram-3-fill me-1"></i> DIVISI
                </a>
                <a class="kpi-nav-item" href="halaman_direktur/kanwil.php">
                    <i class="bi bi-building-fill me-1"></i> KANWIL
                </a>
                <a class="kpi-nav-item active" href="halaman_direktur/kacab.php">
                    <i class="bi bi-geo-alt-fill me-1"></i> KACAB
                </a>
                <a class="kpi-nav-item" href="lihat_kpi.php">
                     <i class="bi bi-eye-fill me-1"></i> LIHAT KPI
                </a>
            </div>
        </nav>

        <div class="container-fluid max-width-lg">

            <form action="proses_tambah_kpi.php" method="POST">
                
                <!-- Card Header Informasi Utama -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle-fill me-2"></i>Informasi Umum KPI</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="col-md-4">
                           <label class="form-label fw-semibold">Masukkan STB</label>
                                <input type="text" name="stb" class="form-control" placeholder="Contoh: 6381" required>
                            </div>
                            <div class="col-md-8">
                            <label class="form-label fw-semibold">Tahun KPI</label>
                            <input type="number" name="periode" class="form-control" placeholder="Contoh: 2025" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between my-4">
                    <h4 class="fw-bold text-dark m-0"><i class="bi bi-journal-check me-2 text-primary"></i>Penilaian KPI</h4>
                    <span class="badge bg-primary px-3 py-2 fs-6">Form Entri Data</span>
                </div>

                <!-- ================= PERSPEKTIF KEUANGAN ================= -->
                 
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
                        <div>
                            <h5 class="fw-bold text-primary mb-0">PERSPEKTIF KEUANGAN</h5>
                            <small class="text-muted">Target dan pencapaian finansial organisasi</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 fw-semibold text-nowrap">Total Bobot:</label>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" name="total_bobot_keuangan" class="form-control text-end fw-bold" placeholder="0" onblur="formatPersen(this)" onfocus="unformatPersen(this)">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light text-center align-middle">
                                    <tr>
                                        
                                        <th style="width: 50px;">NO</th>
                                        <th>SASARAN STRATEGIS</th>
                                        <th>PROGRAM / INISIATIF</th>
                                        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
                                        <th>FORMULA</th>
                                        <th style="width: 100px;">SATUAN</th>
                                        <th style="width: 110px;">POLARITAS</th>
                                        <th style="width: 110px;">BOBOT (%)</th>
                                        <th style="width: 130px;">TARGET</th>
                                        <th style="width: 130px;">REALISASI</th>
                                        <th style="width: 100px;">CAPAIAN</th>
                                        <th style="width: 100px;">RATING</th>
                                        <th style="width: 100px;">NILAI KPI</th>
                                        <th style="width: 60px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody id="body-keuangan">
                                    <tr>
                                        <td class="text-center row-no fw-bold text-secondary">
                                            1
                                            <input type="hidden" name="perspektif[]" value="perspektif_keuangan">
                                        </td>
                                        <td><input type="text" name="sasaran_strategis[]" class="form-control form-control-sm" placeholder="Contoh: Pendapatan & Penerimaan"></td>
                                        <td><input type="text" name="program_inisiatif[]" class="form-control form-control-sm" placeholder="Contoh: Pendapatan premi"></td>
                                        <td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Contoh: Pencapaian target premi baru Netto"></td>
                                        <td><input type="text" name="formula[]" class="form-control form-control-sm" placeholder="Contoh: Realisasi / anggaran"></td>
                                        <td>
                                            <select name="satuan[]" class="form-select form-select-sm">
                                                <option value="Rp">Rp</option>
                                                <option value="%">%</option>
                                                <option value="Jumlah">Jumlah</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="polaritas[]" class="form-select form-select-sm input-polaritas">
                                                <option value="Max">Max</option>
                                                <option value="Min">Min</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="bobot[]" class="form-control form-control-sm text-end" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="target[]" class="form-control form-control-sm text-end input-target" step="any" placeholder="0"></td>
                                        <td><input type="number" name="realisasi[]" class="form-control form-control-sm text-end input-realisasi" step="any" placeholder="0"></td>
                                        <td><input type="text" name="capaian[]" class="form-control form-control-sm text-end input-capaian" readonly placeholder="-"></td>
                                        <td><input type="number" name="rating[]" class="form-control form-control-sm text-end input-rating" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="nilai_kpi[]" class="form-control form-control-sm text-end input-nilai-kpi" step="0.01" placeholder="0"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="hapusBarisDirops(this)">✕</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="tambahBarisDirops('body-keuangan')">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                        </button>
                    </div>
                </div>

                <!-- ================= PERSPEKTIF PELANGGAN ================= -->
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
                        <div>
                            <h5 class="fw-bold text-success mb-0">PERSPEKTIF PELANGGAN</h5>
                            <small class="text-muted">Kepuasan dan retensi layanan pelanggan</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 fw-semibold text-nowrap">Total Bobot:</label>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" name="total_bobot_pelanggan" class="form-control text-end fw-bold" placeholder="0" onblur="formatPersen(this)" onfocus="unformatPersen(this)">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light text-center align-middle">
                                    <tr>
                                        
                                        <th style="width: 50px;">NO</th>
                                        <th>SASARAN STRATEGIS</th>
                                        <th>PROGRAM / INISIATIF</th>
                                        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
                                        <th>FORMULA</th>
                                        <th style="width: 100px;">SATUAN</th>
                                        <th style="width: 110px;">POLARITAS</th>
                                        <th style="width: 110px;">BOBOT (%)</th>
                                        <th style="width: 130px;">TARGET</th>
                                        <th style="width: 130px;">REALISASI</th>
                                        <th style="width: 100px;">CAPAIAN</th>
                                        <th style="width: 100px;">RATING</th>
                                        <th style="width: 100px;">NILAI KPI</th>
                                        <th style="width: 60px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody id="body-pelanggan">
                                    <tr>
                                        <td class="text-center row-no fw-bold text-secondary">
                                            1
                                            <input type="hidden" name="perspektif[]" value="perspektif_pelanggan">
                                        </td>
                                        <td><input type="text" name="sasaran_strategis[]" class="form-control form-control-sm" placeholder="Contoh: Kepuasan Pelanggan"></td>
                                        <td><input type="text" name="program_inisiatif[]" class="form-control form-control-sm" placeholder="Contoh: Survei Layanan"></td>
                                        <td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Contoh: Indeks Kepuasan"></td>
                                        <td><input type="text" name="formula[]" class="form-control form-control-sm" placeholder="Contoh: Skor / Total"></td>
                                        <td>
                                            <select name="satuan[]" class="form-select form-select-sm">
                                                <option value="Rp">Rp</option>
                                                <option value="%">%</option>
                                                <option value="Jumlah">Jumlah</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="polaritas[]" class="form-select form-select-sm input-polaritas">
                                                <option value="Max">Max</option>
                                                <option value="Min">Min</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="bobot[]" class="form-control form-control-sm text-end" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="target[]" class="form-control form-control-sm text-end input-target" step="any" placeholder="0"></td>
                                        <td><input type="number" name="realisasi[]" class="form-control form-control-sm text-end input-realisasi" step="any" placeholder="0"></td>
                                        <td><input type="text" name="capaian[]" class="form-control form-control-sm text-end input-capaian" readonly placeholder="-"></td>
                                        <td><input type="number" name="rating[]" class="form-control form-control-sm text-end input-rating" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="nilai_kpi[]" class="form-control form-control-sm text-end input-nilai-kpi" step="0.01" placeholder="0"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="hapusBarisDirops(this)">✕</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-3" onclick="tambahBarisDirops('body-pelanggan')">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                        </button>
                    </div>
                </div>

                <!-- ================= PERSPEKTIF PROSES BISNIS INTERNAL ================= -->
                 
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
                        <div>
                            <h5 class="fw-bold text-warning mb-0">PERSPEKTIF PROSES BISNIS INTERNAL</h5>
                            <small class="text-muted">Efisiensi operasional dan kualitas tata kelola internal</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 fw-semibold text-nowrap">Total Bobot:</label>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" name="total_bobot_bisnis" class="form-control text-end fw-bold" placeholder="0" onblur="formatPersen(this)" onfocus="unformatPersen(this)">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light text-center align-middle">
                                    <tr>
                                        <th style="width: 50px;">NO</th>
                                        <th>SASARAN STRATEGIS</th>
                                        <th>PROGRAM / INISIATIF</th>
                                        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
                                        <th>FORMULA</th>
                                        <th style="width: 100px;">SATUAN</th>
                                        <th style="width: 110px;">POLARITAS</th>
                                        <th style="width: 110px;">BOBOT (%)</th>
                                        <th style="width: 130px;">TARGET</th>
                                        <th style="width: 130px;">REALISASI</th>
                                        <th style="width: 100px;">CAPAIAN</th>
                                        <th style="width: 100px;">RATING</th>
                                        <th style="width: 100px;">NILAI KPI</th>
                                        <th style="width: 60px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody id="body-bisnis">
                                    <tr>
                                        <td class="text-center row-no fw-bold text-secondary">
                                            1
                                            <input type="hidden" name="perspektif[]" value="perspektif_proses_bisnis_internal">
                                        </td>
                                        <td><input type="text" name="sasaran_strategis[]" class="form-control form-control-sm" placeholder="Contoh: Efisiensi Proses"></td>
                                        <td><input type="text" name="program_inisiatif[]" class="form-control form-control-sm" placeholder="Contoh: Digitalisasi Dokumen"></td>
                                        <td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Contoh: Waktu Penyelesaian"></td>
                                        <td><input type="text" name="formula[]" class="form-control form-control-sm" placeholder="Contoh: Standar waktu"></td>
                                        <td>
                                            <select name="satuan[]" class="form-select form-select-sm">
                                                <option value="Rp">Rp</option>
                                                <option value="%">%</option>
                                                <option value="Jumlah">Jumlah</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="polaritas[]" class="form-select form-select-sm input-polaritas">
                                                <option value="Max">Max</option>
                                                <option value="Min">Min</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="bobot[]" class="form-control form-control-sm text-end" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="target[]" class="form-control form-control-sm text-end input-target" step="any" placeholder="0"></td>
                                        <td><input type="number" name="realisasi[]" class="form-control form-control-sm text-end input-realisasi" step="any" placeholder="0"></td>
                                        <td><input type="text" name="capaian[]" class="form-control form-control-sm text-end input-capaian" readonly placeholder="-"></td>
                                        <td><input type="number" name="rating[]" class="form-control form-control-sm text-end input-rating" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="nilai_kpi[]" class="form-control form-control-sm text-end input-nilai-kpi" step="0.01" placeholder="0"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="hapusBarisDirops(this)">✕</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-warning btn-sm mt-3" onclick="tambahBarisDirops('body-bisnis')">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                        </button>
                    </div>
                </div>

                <!-- ================= PERSPEKTIF PEMBELAJARAN & PERTUMBUHAN ================= -->
                 
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom">
                        <div>
                            <h5 class="fw-bold text-info mb-0">PERSPEKTIF PEMBELAJARAN & PERTUMBUHAN</h5>
                            <small class="text-muted">Pengembangan SDM, kapabilitas, dan inovasi</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0 fw-semibold text-nowrap">Total Bobot:</label>
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <input type="text" name="total_bobot_pertumbuhan" class="form-control text-end fw-bold" placeholder="0" onblur="formatPersen(this)" onfocus="unformatPersen(this)">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light text-center align-middle">
                                    <tr>
                                        
                                        <th style="width: 50px;">NO</th>
                                        <th>SASARAN STRATEGIS</th>
                                        <th>PROGRAM / INISIATIF</th>
                                        <th>KEY PERFORMANCE INDIKATOR (KPI)</th>
                                        <th>FORMULA</th>
                                        <th style="width: 100px;">SATUAN</th>
                                        <th style="width: 110px;">POLARITAS</th>
                                        <th style="width: 110px;">BOBOT (%)</th>
                                        <th style="width: 130px;">TARGET</th>
                                        <th style="width: 130px;">REALISASI</th>
                                        <th style="width: 100px;">CAPAIAN</th>
                                        <th style="width: 100px;">RATING</th>
                                        <th style="width: 100px;">NILAI KPI</th>
                                        <th style="width: 60px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody id="body-pertumbuhan">
                                    <tr>
                                        <td class="text-center row-no fw-bold text-secondary">
                                            1
                                            <input type="hidden" name="perspektif[]" value="perspektif_pembelajaran_pertumbuhan">
                                        </td>
                                        <td><input type="text" name="sasaran_strategis[]" class="form-control form-control-sm" placeholder="Contoh: Pelatihan Karyawan"></td>
                                        <td><input type="text" name="program_inisiatif[]" class="form-control form-control-sm" placeholder="Contoh: Workshop Skill"></td>
                                        <td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Contoh: Jam Pelatihan"></td>
                                        <td><input type="text" name="formula[]" class="form-control form-control-sm" placeholder="Contoh: Total Jam"></td>
                                        <td>
                                            <select name="satuan[]" class="form-select form-select-sm">
                                                <option value="Rp">Rp</option>
                                                <option value="%">%</option>
                                                <option value="Jumlah">Jumlah</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="polaritas[]" class="form-select form-select-sm input-polaritas">
                                                <option value="Max">Max</option>
                                                <option value="Min">Min</option>
                                            </select>
                                        </td>
                                        <td><input type="number" name="bobot[]" class="form-control form-control-sm text-end" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="target[]" class="form-control form-control-sm text-end input-target" step="any" placeholder="0"></td>
                                        <td><input type="number" name="realisasi[]" class="form-control form-control-sm text-end input-realisasi" step="any" placeholder="0"></td>
                                        <td><input type="text" name="capaian[]" class="form-control form-control-sm text-end input-capaian" readonly placeholder="-"></td>
                                        <td><input type="number" name="rating[]" class="form-control form-control-sm text-end input-rating" step="0.01" placeholder="0"></td>
                                        <td><input type="number" name="nilai_kpi[]" class="form-control form-control-sm text-end input-nilai-kpi" step="0.01" placeholder="0"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="hapusBarisDirops(this)">✕</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-info btn-sm mt-3" onclick="tambahBarisDirops('body-pertumbuhan')">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                        </button>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Penilaian KPI
                    </button>
                </div>

            </form>

        </div>
    </main>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function tambahBarisDirops(bodyId) {
  const tbody = document.getElementById(bodyId);
  if (!tbody) return;

  // Tentukan nilai perspektif otomatis berdasarkan tabel tujuan
  let perspektifVal = 'perspektif_keuangan';
  if (bodyId === 'body-pelanggan') perspektifVal = 'perspektif_pelanggan';
  if (bodyId === 'body-bisnis') perspektifVal = 'perspektif_proses_bisnis_internal';
  if (bodyId === 'body-pertumbuhan') perspektifVal = 'perspektif_pembelajaran_pertumbuhan';

  const no = tbody.children.length + 1;
  const row = document.createElement('tr');

  row.innerHTML = `
    <td class="text-center row-no fw-bold text-secondary">
      ${no}
      <input type="hidden" name="perspektif[]" value="${perspektifVal}">
    </td>
    <td><input type="text" name="sasaran_strategis[]" class="form-control form-control-sm" placeholder="Contoh: Sasaran Baru"></td>
    <td><input type="text" name="program_inisiatif[]" class="form-control form-control-sm" placeholder="Contoh: Program Baru"></td>
    <td><input type="text" name="kpi[]" class="form-control form-control-sm" placeholder="Contoh: KPI Baru"></td>
    <td><input type="text" name="formula[]" class="form-control form-control-sm" placeholder="Contoh: Formula"></td>
    <td>
      <select name="satuan[]" class="form-select form-select-sm">
        <option value="Rp">Rp</option>
        <option value="%">%</option>
        <option value="Jumlah">Jumlah</option>
      </select>
    </td>
    <td>
      <select name="polaritas[]" class="form-select form-select-sm input-polaritas">
        <option value="Max">Max</option>
        <option value="Min">Min</option>
      </select>
    </td>
    <td><input type="number" name="bobot[]" class="form-control form-control-sm text-end" step="0.01" placeholder="0"></td>
    <td><input type="number" name="target[]" class="form-control form-control-sm text-end input-target" step="any" placeholder="0"></td>
    <td><input type="number" name="realisasi[]" class="form-control form-control-sm text-end input-realisasi" step="any" placeholder="0"></td>
    <td><input type="text" name="capaian[]" class="form-control form-control-sm text-end input-capaian" readonly placeholder="-"></td>
    <td><input type="number" name="rating[]" class="form-control form-control-sm text-end input-rating" step="0.01" placeholder="0"></td>
    <td><input type="number" name="nilai_kpi[]" class="form-control form-control-sm text-end input-nilai-kpi" step="0.01" placeholder="0"></td>
    <td class="text-center">
      <button type="button" class="btn btn-outline-danger btn-sm border-0" onclick="hapusBarisDirops(this)">✕</button>
    </td>
  `;

  tbody.appendChild(row);
}

function hapusBarisDirops(btn) {
  const row = btn.closest('tr');
  if (row) {
    const tbody = row.closest('tbody');
    row.remove();
    
    if (tbody) {
      const rows = tbody.querySelectorAll('tr');
      rows.forEach((tr, index) => {
        const noCell = tr.querySelector('.row-no') || tr.cells[0];
        if (noCell) {
          const hiddenInput = noCell.querySelector('input[type="hidden"]');
          noCell.innerHTML = (index + 1) + (hiddenInput ? hiddenInput.outerHTML : '');
        }
      });
    }
  }
}

function formatPersen(input) {
    if (input.value && !input.value.includes('%')) {
        input.value = input.value + '%';
    }
}

function unformatPersen(input) {
    input.value = input.value.replace('%', '');
}

function hitungCapaian(realisasi, target, polaritas) {
    if (realisasi === '' || realisasi === null || isNaN(realisasi)) {
        return null;
    }

    realisasi = parseFloat(realisasi);
    target = parseFloat(target);

    let capaian;
    if (String(polaritas).toLowerCase() === 'min') {
        capaian = (realisasi === 0) ? 2 : target / realisasi;
    } else {
        capaian = (target === 0 || isNaN(target)) ? 0 : realisasi / target;
    }

    return Math.min(capaian, 2);
}

function updateCapaianRow(row) {
    const targetInput    = row.querySelector('.input-target');
    const realisasiInput = row.querySelector('.input-realisasi');
    const polaritasInput = row.querySelector('.input-polaritas');
    const capaianInput   = row.querySelector('.input-capaian');

    if (!targetInput || !realisasiInput || !polaritasInput || !capaianInput) return;

    const capaian = hitungCapaian(
        realisasiInput.value,
        targetInput.value,
        polaritasInput.value
    );

   capaianInput.value = (capaian === null) ? '' : (capaian * 100).toFixed(2) + '%';
}

document.addEventListener('input', function (e) {
    if (e.target.matches('.input-target, .input-realisasi')) {
        updateCapaianRow(e.target.closest('tr'));
    }
});

document.addEventListener('change', function (e) {
    if (e.target.matches('.input-polaritas')) {
        updateCapaianRow(e.target.closest('tr'));
    }
});
</script>

</body>
</html>