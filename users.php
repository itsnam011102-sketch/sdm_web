<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

require_once 'koneksi.php';

// Ambil search query dan nomor halaman dari GET
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit  = 10; // Jumlah data per halaman
$offset = ($page - 1) * $limit;

// Filter pencarian
$where = "";
if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $where = " WHERE username LIKE '%$search_safe%' OR role LIKE '%$search_safe%'";
}

// Hitung total user untuk pagination
$count_query  = "SELECT COUNT(*) as total FROM users" . $where;
$count_result = mysqli_query($conn, $count_query);
$total_rows   = ($count_result) ? (int)mysqli_fetch_assoc($count_result)['total'] : 0;
$total_pages  = ($total_rows > 0) ? ceil($total_rows / $limit) : 1;

if ($page > $total_pages) {
    $page = $total_pages;
    $offset = ($page - 1) * $limit;
}

// Query data user dengan limit & offset
$query = "SELECT id, username, role FROM users {$where} ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";
$result = mysqli_query($conn, $query);
$users = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .page-wrap { padding: 22px; }
        .panel { background: white; border-radius: 18px; padding: 20px; box-shadow: 0 14px 35px rgba(15, 23, 42, 0.08); }
        .top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px; }
        .search-section { flex: 1; display: flex; gap: 10px; }
        .search-box { flex: 1; display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0 12px; }
        .search-box input { flex: 1; border: none; background: none; padding: 10px; outline: none; font-size: 14px; }
        .search-box button { background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .search-box button:hover { background: #2563eb; }
        .btn { padding: 10px 14px; border-radius: 10px; border: none; background: #2563eb; color: white; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn:hover { background: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { color: #64748b; font-size: 12px; text-transform: uppercase; }
        .action-buttons { white-space: nowrap; }
        .btn-edit { display: inline-block; padding: 6px 12px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; font-size: 12px; margin-right: 5px; }
        .btn-edit:hover { background: #059669; }
        .btn-hapus { display: inline-block; padding: 6px 12px; background: #ef4444; color: white; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .btn-hapus:hover { background: #dc2626; }
        .no-results { text-align: center; padding: 30px; color: #64748b; }
        .role-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .role-admin { background: #dbeafe; color: #0c4a6e; }
        .role-user { background: #fef3c7; color: #78350f; }

        /* Style Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 12px;
        }
        .pagination-info {
            color: #64748b;
            font-size: 14px;
        }
        .pagination-links {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .pagination-links a, .pagination-links span {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            color: #334155;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .pagination-links a:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }
        .pagination-links .active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
        .pagination-links .disabled {
            color: #94a3b8;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="dashboard-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">SD</div>
                <h2>SDM App</h2>
            </div>
            <div class="nav-group">
                <p class="nav-title">Menu</p>
                <a class="nav-item" href="dashboard.php"><div class="nav-icon"><i class="bi bi-house-door-fill"></i></div><span>Dashboard</span></a>
                <a class="nav-item" href="data.php"><div class="nav-icon"><i class="bi bi-people-fill"></i></div><span>Data Pegawai</span></a>
                <a class="nav-item" href="data_pkwt.php"><div class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></div><span>Data PKWT</span></a>
                <a class="nav-item" href="kpi.php"><div class="nav-icon"><i class="bi bi-bar-chart-line-fill"></i></div><span>Data KPI</span></a>
                <a class="nav-item active" href="users.php"><div class="nav-icon"><i class="bi bi-person-gear"></i></div><span>Kelola User</span></a>
                <a class="nav-item" href="logout.php"><div class="nav-icon"><i class="bi bi-box-arrow-right"></i></div><span>Keluar</span></a>
            </div>
        </aside>

        <main class="main-panel">
            <div class="page-wrap">
                <div class="panel">
                    <div class="top-row">
                        <h2>Kelola User</h2>
                        <a href="tambah_user.php" class="btn">+ Tambah User</a>
                    </div>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'added'): ?>
                            <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">✅ User baru berhasil ditambahkan!</div>
                        <?php elseif ($_GET['msg'] === 'updated'): ?>
                            <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">✅ Data user berhasil diperbarui!</div>
                        <?php elseif ($_GET['msg'] === 'deleted'): ?>
                            <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">🗑️ User berhasil dihapus!</div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (isset($_GET['error']) && $_GET['error'] === 'self_delete'): ?>
                        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">❌ Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif login!</div>
                    <?php endif; ?>

                    <div class="search-section">
                        <form method="GET" class="search-box" style="flex: 0 0 300px;">
                            <input type="text" name="search" placeholder="Cari username atau role..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit">🔍 Cari</button>
                        </form>
                        <?php if (!empty($search)): ?>
                            <a href="users.php" style="padding: 10px 14px; background: #e2e8f0; color: #1e293b; text-decoration: none; border-radius: 6px; font-weight: 600;">✕ Hapus Filter</a>
                        <?php endif; ?>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($users) > 0) {
                                foreach ($users as $u) {
                                    $role_class = ($u['role'] === 'Administrator') ? 'role-admin' : 'role-user';
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($u['id']) . "</td>";
                                    echo "<td><strong>" . htmlspecialchars($u['username']) . "</strong></td>";
                                    echo "<td><span class='role-badge $role_class'>" . htmlspecialchars($u['role']) . "</span></td>";
                                    echo "<td class='action-buttons'>";
                                    echo "<a href='edit_user.php?id=" . $u['id'] . "' class='btn-edit'>✏️ Edit</a> ";
                                    $logged_in_id = $_SESSION['user']['id'] ?? 0;
                                    if ($u['id'] != $logged_in_id) {
                                        echo "<a href='hapus_user.php?id=" . $u['id'] . "' class='btn-hapus' onclick='return confirm(\"Yakin ingin hapus user ini?\")'>🗑️ Hapus</a>";
                                    } else {
                                        echo "<span style='color: #999; font-size: 12px;'>(User saat ini)</span>";
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                if (!empty($search)) {
                                    echo "<tr><td colspan='4' class='no-results'>Tidak ada hasil pencarian untuk '<strong>" . htmlspecialchars($search) . "</strong>'</td></tr>";
                                } else {
                                    echo "<tr><td colspan='4' class='no-results'>Tidak ada user</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Komponen Navigasi Pagination -->
                    <?php if ($total_rows > 0): ?>
                        <div class="pagination-container">
                            <div class="pagination-info">
                                Menampilkan <strong><?php echo ($total_rows > 0) ? ($offset + 1) : 0; ?></strong> - <strong><?php echo min($offset + $limit, $total_rows); ?></strong> dari <strong><?php echo $total_rows; ?></strong> user
                            </div>
                            <div class="pagination-links">
                                <?php
                                $search_param = !empty($search) ? '&search=' . urlencode($search) : '';
                                ?>
                                
                                <!-- Tombol Prev -->
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo ($page - 1) . $search_param; ?>">« Prev</a>
                                <?php else: ?>
                                    <span class="disabled">« Prev</span>
                                <?php endif; ?>

                                <!-- Nomor Halaman -->
                                <?php
                                $max_links = 5;
                                if ($total_pages <= $max_links) {
                                    $start_page = 1;
                                    $end_page = $total_pages;
                                } else {
                                    if ($page <= 3) {
                                        $start_page = 1;
                                        $end_page = 5;
                                    } elseif ($page >= $total_pages - 2) {
                                        $start_page = $total_pages - 4;
                                        $end_page = $total_pages;
                                    } else {
                                        $start_page = $page - 2;
                                        $end_page = $page + 2;
                                    }
                                }

                                if ($start_page > 1) {
                                    echo '<a href="?page=1' . $search_param . '">1</a>';
                                    if ($start_page > 2) {
                                        echo '<span class="disabled">...</span>';
                                    }
                                }

                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    if ($i == $page) {
                                        echo '<span class="active">' . $i . '</span>';
                                    } else {
                                        echo '<a href="?page=' . $i . $search_param . '">' . $i . '</a>';
                                    }
                                }

                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<span class="disabled">...</span>';
                                    }
                                    echo '<a href="?page=' . $total_pages . $search_param . '">' . $total_pages . '</a>';
                                }
                                ?>

                                <!-- Tombol Next -->
                                <?php if ($page < $total_pages): ?>
                                    <a href="?page=<?php echo ($page + 1) . $search_param; ?>">Next »</a>
                                <?php else: ?>
                                    <span class="disabled">Next »</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
