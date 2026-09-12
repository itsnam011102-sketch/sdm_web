<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];

// Hubungkan ke database
require_once 'koneksi.php';

// Hitung total pegawai
$query_total = "SELECT COUNT(*) as total FROM employe";
$result_total = mysqli_query($conn, $query_total);
$total_pegawai = 0;
if ($result_total) {
    $row = mysqli_fetch_assoc($result_total);
    $total_pegawai = $row['total'];
}

// Hitung total PKWT
$query_pkwt = "SELECT COUNT(*) as total FROM pkwt";
$result_pkwt = mysqli_query($conn, $query_pkwt);
$total_pkwt = 0;
if ($result_pkwt) {
    $row_pkwt = mysqli_fetch_assoc($result_pkwt);
    $total_pkwt = (int)($row_pkwt['total'] ?? 0);
}

// Hitung total kantor dari database employe
$query_kantor = "SELECT COUNT(DISTINCT kantor) as total FROM employe WHERE kantor IS NOT NULL AND kantor != ''";
$result_kantor = mysqli_query($conn, $query_kantor);
$total_kantor = 0;
if ($result_kantor) {
    $row_kantor = mysqli_fetch_assoc($result_kantor);
    $total_kantor = (int)($row_kantor['total'] ?? 0);
}

// Ambil data employee dari database
$query = "SELECT id, kode_pegawai, nama, email, no_hp, jenis_kelamin, nik, jabatan, kantor FROM employe ORDER BY id DESC LIMIT 10";
$result = mysqli_query($conn, $query);
$pegawai = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pegawai[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SDM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">SDM</div>
                <h2>SDM App</h2>
            </div>

            <div class="nav-group">
                <p class="nav-title">Menu</p>
                <a class="nav-item active" href="dashboard.php">
                    <div class="nav-icon"><i class="bi bi-house-door-fill"></i></div>
                    <span>Dashboard</span>
                </a>
                <a class="nav-item" href="data.php">
                    <div class="nav-icon"><i class="bi bi-people-fill"></i></div>
                    <span>Data Pegawai</span>
                </a>
                <a class="nav-item" href="data_pkwt.php">
                    <div class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <span>Data PKWT</span>
                </a>
                
                <a class="nav-item" href="kpi.php">
                    <div class="nav-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                    <span>Data KPI</span>
                </a>
                <a class="nav-item" href="users.php">
                    <div class="nav-icon"><i class="bi bi-person-gear"></i></div>
                    <span>Kelola User</span>
                </a>
                <a class="nav-item" href="#">
                    <div class="nav-icon"><i class="bi bi-gear-fill"></i></div>
                    <span>Pengaturan</span>
                </a>
            </div>

            <div class="nav-group">
                <p class="nav-title">Akun</p>
                <a class="nav-item" href="logout.php">
                    <div class="nav-icon">🚪</div>
                    <span>Keluar</span>
                </a>
            </div>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div class="topbar-title">
                    <h1>Dashboard</h1>
                    <p>Selamat datang kembali, <?php echo htmlspecialchars($user['username'] ?? 'User'); ?></p>
                </div>

                <div class="topbar-actions">
                    <div class="search-box">
                        <span>🔎</span>
                        <input type="text" placeholder="Cari pegawai...">
                    </div>

                    <button class="icon-btn" type="button" aria-label="Notifikasi">
                        🔔
                        <span class="badge">3</span>
                    </button>

                    <div class="user-chip">
                        <div class="avatar"><?php echo strtoupper(substr(($user['username'] ?? 'U'), 0, 1)); ?></div>
                        <div class="user-meta">
                            <strong><?php echo htmlspecialchars($user['username'] ?? 'User'); ?></strong>
                            <span>Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <section class="content">
                <div class="hero">
                    <div>
                        <h2>Ringkasan SDM</h2>
                        <p>Monitoring performa pegawai, data aktif, dan kebutuhan operasional hari ini.</p>
                    </div>
                    <div class="hero-actions">
                        <button class="secondary-btn" type="button">+ Tambah Data</button>
                        <button class="primary-btn" type="button">Lihat Laporan</button>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-top">
                            <h3>Total Pegawai</h3>
                            <div class="stat-icon blue">👥</div>
                        </div>
                        <div class="stat-value"><?php echo number_format($total_pegawai); ?></div>
                        <div class="stat-trend">Data dari database</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <h3>Total PKWT</h3>
                            <div class="stat-icon green">📜</div>
                        </div>
                        <div class="stat-value"><?php echo number_format($total_pkwt); ?></div>
                        <div class="stat-trend">Data dari database PKWT</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <h3>Menunggu Approval</h3>
                            <div class="stat-icon orange">⏳</div>
                        </div>
                        <div class="stat-value">42</div>
                        <div class="stat-trend">▼ 2.1% dari minggu lalu</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-top">
                            <h3>Total Kantor</h3>
                            <div class="stat-icon purple">🏢</div>
                        </div>
                        <div class="stat-value"><?php echo number_format($total_kantor); ?></div>
                        <div class="stat-trend">Data dari database Pegawai</div>
                    </div>
                </div>

                <div class="grid-two">
                    <div class="card">
                        <div class="card-header">
                            <h3>Trend Rekrutmen</h3>
                            <a class="link-btn" href="#">Lihat detail</a>
                        </div>

                        <div class="chart-wrap" aria-label="Chart rekrutmen">
                            <div class="bar-group">
                                <div class="bar" style="height: 35%;"></div>
                                <span>Jan</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar secondary" style="height: 48%;"></div>
                                <span>Feb</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 62%;"></div>
                                <span>Mar</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar secondary" style="height: 55%;"></div>
                                <span>Apr</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar" style="height: 72%;"></div>
                                <span>Mei</span>
                            </div>
                            <div class="bar-group">
                                <div class="bar secondary" style="height: 80%;"></div>
                                <span>Jun</span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3>Aktivitas Terbaru</h3>
                            <a class="link-btn" href="#">Semua</a>
                        </div>

                        <ul class="task-list">
                            <li class="task-item">
                                <div class="task-dot"></div>
                                <div>
                                    <strong>Update data pegawai</strong>
                                    <span>2 jam yang lalu</span>
                                </div>
                            </li>
                            <li class="task-item">
                                <div class="task-dot warn"></div>
                                <div>
                                    <strong>Persetujuan cuti</strong>
                                    <span>4 jam yang lalu</span>
                                </div>
                            </li>
                            <li class="task-item">
                                <div class="task-dot danger"></div>
                                <div>
                                    <strong>Data belum lengkap</strong>
                                    <span>Hari ini</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card table-card">
                    <div class="card-header">
                        <h3>Pegawai Terbaru</h3>
                        <a class="link-btn" href="data.php">Lihat semua</a>
                    </div>

                    <div class="table-wrap">
                        <main class="main-panel">
            <div class="page-wrap">
                <div class="panel">
                    <div class="top-row">
                        <h2>Data Pegawai</h2>
                        <a href="tambah_data.php" class="btn">+ Tambah Pegawai</a>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Kode Pegawai</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No HP</th>
                                <th>Jenis Kelamin</th>
                                <th>NIK</th>
                                <th>Jabatan</th>
                                <th>Kantor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($pegawai) > 0) {
                                foreach ($pegawai as $p) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($p['kode_pegawai'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['nama'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['email'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['no_hp'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['jenis_kelamin'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['nik'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['jabatan'] ?? '') . "</td>";
                                    echo "<td>" . htmlspecialchars($p['kantor'] ?? '') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align:center;'>Tidak ada data pegawai</td></tr>";
                            }
                            ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
