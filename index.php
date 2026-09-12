<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

require_once 'koneksi.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $login_success = false;

    if (isset($conn) && $conn) {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password, role FROM users WHERE username = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    $_SESSION['user'] = [
                        'id'       => $row['id'],
                        'username' => $row['username'],
                        'role'     => $row['role']
                    ];
                    $login_success = true;
                }
            }
        }
    }

    if (!$login_success && $username === 'admin' && $password === 'admin123') {
        $_SESSION['user'] = ['id' => 1, 'username' => 'admin', 'role' => 'Administrator'];
        $login_success = true;
    }

    if ($login_success) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SDM</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            font-family: Arial, sans-serif;
        }
        .login-box {
            width: min(420px, 90vw);
            background: white;
            border-radius: 22px;
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12);
            padding: 32px;
        }
        h2 {
            margin: 0 0 8px;
            text-align: center;
            color: #1e3a8a;
        }
        .sub {
            text-align: center;
            color: #64748b;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
        }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
        }
        button {
            width: 100%;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .demo {
            margin-top: 18px;
            text-align: center;
            color: #475569;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login SDM</h2>
        <p class="sub">Masuk ke dashboard pegawai</p>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="admin" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="admin123" required>
            </div>

            <button type="submit">Masuk</button>
        </form>

        <div class="demo">
            Demo login: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </div>
</body>
</html>
