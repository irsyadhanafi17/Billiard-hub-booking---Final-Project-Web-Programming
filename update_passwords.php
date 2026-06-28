<?php
/**
 * TOOL SATU KALI PAKAI — Update Password Plain Text ke BCrypt
 * 
 * Jalankan SEKALI via browser: http://localhost/afterhour/update_passwords.php
 * Setelah selesai, HAPUS file ini dari server!
 * 
 * Script ini akan:
 * 1. Scan semua user di database
 * 2. Jika password bukan bcrypt, tanya apakah mau di-update
 * 3. Set semua password non-hash menjadi sama dengan plain text-nya (di-hash)
 */

$koneksi = mysqli_connect("localhost", "root", "", "afterhour_db");
if (!$koneksi) { die("Koneksi gagal: " . mysqli_connect_error()); }

$action = $_GET['action'] ?? '';

// Jika ada request update spesifik
if ($action === 'update' && isset($_GET['uid']) && isset($_GET['newpwd'])) {
    $uid     = (int)$_GET['uid'];
    $newpwd  = $_GET['newpwd'];
    $hash    = password_hash($newpwd, PASSWORD_DEFAULT);
    $escaped = mysqli_real_escape_string($koneksi, $hash);
    if (mysqli_query($koneksi, "UPDATE users SET password='$escaped' WHERE userid=$uid")) {
        echo "<p style='color:green'>✅ User ID $uid berhasil di-update ke bcrypt.</p>";
    }
}

// Ambil semua user
$res   = mysqli_query($koneksi, "SELECT userid, email, name, role, password FROM users ORDER BY userid");
$users = [];
while ($row = mysqli_fetch_assoc($res)) { $users[] = $row; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Password Tool — Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { background:#111; color:#eee; font-family:monospace; padding:30px; }
        h2 { color:#8bd100; }
        table { width:100%; }
        th { background:#222; color:#8A7E6C; padding:10px; text-align:left; }
        td { border-bottom:1px solid #333; padding:10px; }
        .badge-hash { background:#0a2a0a; color:#5acc8a; padding:3px 8px; border-radius:4px; font-size:11px; }
        .badge-plain { background:#3a2a00; color:#ffaa00; padding:3px 8px; border-radius:4px; font-size:11px; }
        .btn-fix { background:#8bd100; color:#000; border:none; padding:6px 14px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:bold; }
        .alert { background:#3a0a0a; border:1px solid #8a2020; border-radius:8px; padding:14px 18px; margin-bottom:20px; color:#ff8888; }
        .success { background:#0a2a0a; border:1px solid #1a5a1a; border-radius:8px; padding:14px 18px; margin-bottom:20px; color:#5acc8a; }
        .pwd-input { background:#222; border:1px solid #444; color:#fff; padding:6px 10px; border-radius:6px; width:120px; }
    </style>
</head>
<body>
<h2>🔐 Afterhour — Password Upgrade Tool</h2>
<div class="alert">
    <strong>⚠️ HAPUS FILE INI SETELAH SELESAI!</strong> Jangan biarkan file ini di server production.
</div>

<p style="color:#8A7E6C; margin-bottom:20px;">
    Script ini mendeteksi password plain text dan meng-upgrade ke <strong style="color:#fff">bcrypt hash</strong>.
    Password yang sudah berbentuk hash (<code>$2y$...</code>) tidak akan diubah.
</p>

<table>
    <thead>
        <tr>
            <th>ID</th><th>Email</th><th>Nama</th><th>Role</th><th>Status Password</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $u):
        $isHash = (strlen($u['password']) >= 60 && substr($u['password'], 0, 4) === '$2y$');
    ?>
    <tr>
        <td><?= $u['userid'] ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= $u['role'] ?></td>
        <td>
            <?php if ($isHash): ?>
                <span class="badge-hash">✅ BCRYPT — Aman</span>
            <?php else: ?>
                <span class="badge-plain">⚠️ PLAIN TEXT: "<?= htmlspecialchars($u['password']) ?>"</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if (!$isHash): ?>
                <form method="get" style="display:inline-flex;gap:8px;align-items:center">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="uid" value="<?= $u['userid'] ?>">
                    <input type="text" name="newpwd" class="pwd-input"
                           value="<?= htmlspecialchars($u['password']) ?>"
                           placeholder="password baru">
                    <button type="submit" class="btn-fix">🔒 Upgrade ke Hash</button>
                </form>
            <?php else: ?>
                <span style="color:#555; font-size:12px">Tidak perlu di-update</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<br>
<div style="background:#1a0a1a; border:1px solid #2f0618; border-radius:8px; padding:16px; margin-top:20px">
    <h4 style="color:#8bd100; margin:0 0 10px">Atau: Set Ulang Semua Password ke Default</h4>
    <p style="color:#8A7E6C; font-size:13px; margin-bottom:12px">
        Klik tombol di bawah untuk set password semua akun menjadi <strong style="color:#fff">password</strong>
    </p>
    <a href="?action=resetall" class="btn-fix" style="text-decoration:none"
       onclick="return confirm('Reset semua password ke \'password\'?')">
        🔄 Reset Semua ke "password"
    </a>
</div>

<?php
if ($action === 'resetall') {
    $hash = password_hash('password', PASSWORD_DEFAULT);
    $esc  = mysqli_real_escape_string($koneksi, $hash);
    mysqli_query($koneksi, "UPDATE users SET password='$esc'");
    echo "<div class='success' style='margin-top:16px'>✅ Semua password berhasil di-reset ke <strong>\"password\"</strong>. Silakan login ulang.</div>";
}
?>

<br><br>
<p style="color:#555; font-size:12px">Setelah selesai, hapus file ini: <code style="color:#ff8888">rm afterhour/update_passwords.php</code></p>
</body>
</html>
<?php mysqli_close($koneksi); ?>
