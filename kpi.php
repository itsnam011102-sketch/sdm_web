<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data KPI - SDM App</title>

    <!-- Bootstrap 5 & Icons (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy-950: #0f172a;
            --navy-900: #111827;
            --indigo-900: #1e3a8a;
            --blue-600: #2563eb;
            --blue-400: #60a5fa;
            --bg: #f4f7fb;
            --ink: #1f2937;
            --ink-soft: #64748b;
            --line: #e6ebf3;

            --status-good: #16a34a;
            --status-good-bg: #ecfdf3;
            --status-warn: #d97706;
            --status-warn-bg: #fffaeb;
            --status-bad: #dc2626;
            --status-bad-bg: #fef2f2;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        h1, h2, h3, h4, .brand h2, .nav-title, .kpi-nav-item, .kpi-btn-back, .section-heading {
            font-family: 'Manrope', 'Segoe UI', sans-serif;
        }

        /* ===== LAYOUT ===== */
        .dashboard-shell { display: flex; min-height: 100vh; }

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

        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 32px; padding: 10px 12px; }
        .brand-mark {
            width: 38px; height: 38px; border-radius: 12px;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            display: grid; place-items: center;
            font-weight: 700; font-size: 0.75rem; color: white; flex-shrink: 0;
        }
        .brand h2 { font-size: 1.1rem; color: white; font-weight: 700; }

        .nav-group { margin-bottom: 28px; }
        .nav-title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.12em; color: #94a3b8; margin: 0 12px 12px; font-weight: 700; }
        .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 12px;
            color: #dbeafe; margin-bottom: 4px; text-decoration: none; transition: all 0.2s ease; font-size: 0.92rem;
        }
        .nav-item:hover, .nav-item.active { background: rgba(96, 165, 250, 0.18); color: white; }
        .nav-icon {
            width: 32px; height: 32px; border-radius: 10px; background: rgba(255,255,255,0.06);
            display: grid; place-items: center; font-size: 0.9rem; flex-shrink: 0;
        }

        /* ===== MAIN PANEL ===== */
        .main-panel { flex: 1; padding: 28px; overflow-x: hidden; }

        .page-heading { margin-bottom: 20px; text-align: center; font-size: 4.5rem; }
        .page-heading h1 { font-size: 1.8rem; font-weight: 800; color: var(--navy-950); margin-bottom: 4px; }
        .page-heading p { color: var(--ink-soft); font-size: 0.92rem; }

        /* ===== SCOPE CARD GRID (pengganti navbar atas) ===== */
        .scope-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .scope-card {
            background: white;
            border: 1.5px solid var(--line);
            border-radius: 16px;
            padding: 20px 18px;
            text-decoration: none;
            color: var(--ink);
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .scope-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.1);
            border-color: var(--blue-400);
            color: var(--ink);
        }
        .scope-card.active {
            border-color: var(--blue-600);
            background: linear-gradient(180deg, #eef4ff 0%, #ffffff 100%);
        }
        .scope-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg, var(--blue-400), var(--blue-600));
            display: grid; place-items: center; color: white; font-size: 1.1rem;
        }
        .scope-card h3 { font-size: 0.98rem; font-weight: 700; color: var(--navy-950); margin-bottom: 2px; }
        .scope-card p { font-size: 0.8rem; color: var(--ink-soft); line-height: 1.4; }
        .scope-card .go {
            margin-top: auto; font-size: 0.78rem; font-weight: 700; color: var(--blue-600);
            display: flex; align-items: center; gap: 4px;
        }

        @media (max-width: 900px) {
            .scope-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 480px) {
            .scope-grid { grid-template-columns: 1fr; }
        }

        /* ===== CONTENT CARD ===== */
        .content-card {
            background: white;
            border-radius: 18px;
            padding: 30px 32px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08);
            width: 100%;
        }

        .section-heading {
            font-size: 0.95rem; font-weight: 700; color: var(--navy-950);
            margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
        }
        .section-heading i { color: var(--blue-600); }

        /* Selector periode & divisi */
        .selector-row { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 26px; padding-bottom: 26px; border-bottom: 1px solid var(--line); }
        .selector-field { flex: 1; min-width: 220px; }
        .selector-field label { display: block; font-size: 0.8rem; font-weight: 600; color: var(--ink-soft); margin-bottom: 6px; }
        .selector-field select {
            width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid var(--line);
            font-size: 0.92rem; color: var(--ink); background: #fbfcfe; transition: border-color 0.15s ease;
            font-family: 'Inter', sans-serif;
        }
        .selector-field select:focus { outline: none; border-color: var(--blue-400); background: white; }

        /* Wadah form divisi (diisi via fetch) */
        #wadah-form-divisi { min-height: 120px; }
        #wadah-form-divisi table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        #wadah-form-divisi table th {
            text-align: left; padding: 10px 12px; background: #f8fafc; color: var(--ink-soft);
            font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em;
            border-bottom: 1.5px solid var(--line);
        }
        #wadah-form-divisi table td { padding: 10px 12px; border-bottom: 1px solid var(--line); vertical-align: middle; }
        #wadah-form-divisi input[type="text"],
        #wadah-form-divisi input[type="number"] {
            width: 100%; padding: 8px 10px; border-radius: 8px; border: 1.5px solid var(--line); font-size: 0.88rem;
        }
        #wadah-form-divisi input:focus { outline: none; border-color: var(--blue-400); }

        .empty-state {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 10px; padding: 48px 20px; color: var(--ink-soft); text-align: center;
        }
        .empty-state i { font-size: 2rem; color: var(--blue-400); }
        .empty-state b { color: var(--navy-950); }

        /* Ringkasan skor total */
        .score-summary {
            margin-top: 26px; padding-top: 22px; border-top: 1px solid var(--line);
            display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
        }
        .score-summary .label { font-size: 0.85rem; color: var(--ink-soft); font-weight: 600; }
        .score-summary .value-row { display: flex; align-items: center; gap: 14px; }
        #text-total-skor { font-size: 1.9rem; font-weight: 800; color: var(--navy-950); font-family: 'Manrope', sans-serif; }
        .score-badge {
            font-size: 0.78rem; font-weight: 700; padding: 5px 12px; border-radius: 999px;
            background: var(--status-warn-bg); color: var(--status-warn);
        }
        .score-badge.good { background: var(--status-good-bg); color: var(--status-good); }
        .score-badge.bad { background: var(--status-bad-bg); color: var(--status-bad); }

        .btn-simpan {
            background: linear-gradient(135deg, var(--blue-400), var(--blue-600));
            color: white; border: none; padding: 11px 26px; border-radius: 10px; font-weight: 700;
            font-size: 0.9rem; box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25); transition: transform 0.15s ease;
        }
        .btn-simpan:hover { transform: translateY(-1px); color: white; }
        .btn-simpan:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* ===== RESPONSIVE ===== */
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
            .score-summary { flex-direction: column; align-items: stretch; }
            .btn-simpan { width: 100%; }
        }
        @media (max-width: 640px) {
            .dashboard-shell { flex-direction: column; }
            .sidebar { width: 100%; height: auto; min-width: unset; flex-direction: row; flex-wrap: nowrap; overflow-x: auto; padding: 10px 12px; }
            .nav-group { display: flex; flex-direction: row; gap: 4px; margin: 0; }
            .brand { margin-bottom: 0; margin-right: 12px; }
            .nav-item { padding: 8px 10px; min-width: auto; }
            .main-panel { padding: 16px; }
            .content-card { padding: 22px 18px; }
        }
    </style>
</head>

<body>

<div class="dashboard-shell">

    <!-- Sidebar / Navigation -->
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">SDM</div>
            <h2>SDM App</h2>
        </div>
        <div class="nav-group">
            <p class="nav-title">Menu</p>
            <a class="nav-item" href="dashboard.php"><div class="nav-icon"><i class="bi bi-house-door-fill"></i></div><span>Dashboard</span></a>
            <a class="nav-item" href="data.php"><div class="nav-icon"><i class="bi bi-people-fill"></i></div><span>Data Pegawai</span></a>
            <a class="nav-item" href="data_pkwt.php"><div class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></div><span>Data PKWT</span></a>
            <a class="nav-item active" href="kpi.php"><div class="nav-icon"><i class="bi bi-bar-chart-line-fill"></i></div><span>Data KPI</span></a>
            <a class="nav-item" href="users.php"><div class="nav-icon"><i class="bi bi-person-gear"></i></div><span>Kelola User</span></a>
            <a class="nav-item" href="logout.php"><div class="nav-icon"><i class="bi bi-box-arrow-right"></i></div><span>Keluar</span></a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-panel">

        <div class="page-heading">
            <h1>Data KPI</h1>
            <p>Kelola dan pantau capaian KPI per divisi, kanwil, dan kantor cabang.</p>
        </div>

        <!-- Kartu Pilihan Cakupan (pengganti navbar atas) -->
        <div class="scope-grid">
            <a class="scope-card active" href="kpi.php">
                <div class="scope-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <div>
                    <h3>Ringkasan</h3>
                    <p>Ikhtisar capaian KPI di seluruh unit kerja.</p>
                </div>
                <span class="go">Sedang dibuka <i class="bi bi-check2"></i></span>
            </a>
            <a class="scope-card" href="halaman_direktur/direktur.php">
                <div class="scope-icon"><i class="bi bi-person-badge-fill"></i></div>
                <div>
                    <h3>Direktur</h3>
                    <p>Penilaian KPI tingkat direksi.</p>
                </div>
                <span class="go">Buka <i class="bi bi-arrow-right"></i></span>
            </a>
            <a class="scope-card" href="divisi.php">
                <div class="scope-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <div>
                    <h3>Divisi</h3>
                    <p>Penilaian KPI per divisi kerja.</p>
                </div>
                <span class="go">Buka <i class="bi bi-arrow-right"></i></span>
            </a>
            <a class="scope-card" href="#">
                <div class="scope-icon"><i class="bi bi-building-fill"></i></div>
                <div>
                    <h3>Kanwil</h3>
                    <p>Penilaian KPI tingkat kantor wilayah.</p>
                </div>
                <span class="go">Buka <i class="bi bi-arrow-right"></i></span>
            </a>
            <a class="scope-card" href="#">
                <div class="scope-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <h3>Kacab</h3>
                    <p>Penilaian KPI tingkat kantor cabang.</p>
                </div>
                <span class="go">Buka <i class="bi bi-arrow-right"></i></span>
            </a>
        </div>

        <!-- Kartu Konten -->
        <div class="content-card">

            <div class="section-heading"><i class="bi bi-funnel-fill"></i> Pilih Cakupan Penilaian</div>

            <div class="selector-row">
                <div class="selector-field">
                    <label for="pilih-periode">Periode</label>
                    <select id="pilih-periode" class="form-select-custom">
                        <option value="">-- Pilih Periode --</option>
                        <option value="2026-q1">Triwulan 1 - 2026</option>
                        <option value="2026-q2">Triwulan 2 - 2026</option>
                        <option value="2026-q3">Triwulan 3 - 2026</option>
                        <option value="2026-q4">Triwulan 4 - 2026</option>
                    </select>
                </div>
                <div class="selector-field">
                    <label for="pilih-divisi">Divisi</label>
                    <select id="pilih-divisi" onchange="pilihDivisi(this.value)">
                        <option value="">-- Pilih Divisi --</option>
                        <option value="marketing">Marketing</option>
                        <option value="operasional">Operasional</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="sdm">SDM</option>
                    </select>
                </div>
            </div>

            <div class="section-heading"><i class="bi bi-clipboard-data-fill"></i> Formulir Capaian KPI</div>

            <div id="wadah-form-divisi">
                <div class="empty-state">
                    <i class="bi bi-info-circle"></i>
                    <div>Silakan pilih <b>divisi</b> di atas untuk memuat formulir KPI-nya.</div>
                </div>
            </div>

            <div class="score-summary">
                <div>
                    <div class="label">Total Skor Capaian</div>
                    <div class="value-row">
                        <span id="text-total-skor">0.00%</span>
                        <span class="score-badge" id="badge-total-skor">Belum dinilai</span>
                    </div>
                </div>
                <button type="button" class="btn-simpan" id="btn-simpan-kpi" disabled>
                    <i class="bi bi-save2 me-1"></i> Simpan Penilaian
                </button>
            </div>

        </div>

    </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function pilihDivisi(namaDivisi) {
    let wadah = document.getElementById("wadah-form-divisi");
    let tombolSimpan = document.getElementById("btn-simpan-kpi");

    if (!namaDivisi) {
        wadah.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-info-circle"></i>
                <div>Silakan pilih <b>divisi</b> di atas untuk memuat formulir KPI-nya.</div>
            </div>`;
        tombolSimpan.disabled = true;
        updateTotalSkorGlobal();
        return;
    }

    wadah.innerHTML = `
        <div class="empty-state">
            <div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem;"></div>
            <div>Memuat formulir divisi...</div>
        </div>`;

    fetch('form_divisi/' + namaDivisi + '.php')
        .then(response => {
            if (!response.ok) throw new Error('File tidak ditemukan');
            return response.text();
        })
        .then(html => {
            wadah.innerHTML = html;

            const scripts = wadah.querySelectorAll("script");
            scripts.forEach(oldScript => {
                const newScript = document.createElement("script");
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });

            tombolSimpan.disabled = false;
            updateTotalSkorGlobal();
        })
        .catch(err => {
            wadah.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                    <div>Gagal memuat form divisi ini. Pastikan file <b>form_divisi/${namaDivisi}.php</b> sudah ada.</div>
                </div>`;
            tombolSimpan.disabled = true;
        });
}

function updateTotalSkorGlobal() {
    const textTotal = document.getElementById("text-total-skor");
    const badgeTotal = document.getElementById("badge-total-skor");
    if (!textTotal) return;

    let totalSkor = 0;
    const allCapaian = document.querySelectorAll('input[name="capaian[]"]');

    allCapaian.forEach(inputCap => {
        const row = inputCap.closest('tr');
        if (row) {
            const bobot = parseFloat(row.querySelector('input[name="bobot[]"]')?.value) || 0;
            const capaianStr = inputCap.value || "0";
            const capaian = parseFloat(capaianStr.replace('%', '')) || 0;
            totalSkor += (bobot / 100) * capaian;
        }
    });

    textTotal.innerText = totalSkor.toFixed(2) + "%";

    if (badgeTotal) {
        badgeTotal.classList.remove('good', 'bad');
        if (allCapaian.length === 0) {
            badgeTotal.textContent = "Belum dinilai";
        } else if (totalSkor >= 90) {
            badgeTotal.textContent = "Sangat baik";
            badgeTotal.classList.add('good');
        } else if (totalSkor >= 70) {
            badgeTotal.textContent = "Cukup baik";
        } else {
            badgeTotal.textContent = "Perlu perhatian";
            badgeTotal.classList.add('bad');
        }
    }
}
</script>

</body>
</html>