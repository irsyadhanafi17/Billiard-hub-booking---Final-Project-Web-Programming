<?php
if (!isset($_SESSION)) { session_start(); }

if (isset($_SESSION['role'])) {
    header('Location: index.php'); exit();
}

require_once(__DIR__.'/../class/class.User.php');
require_once(__DIR__.'/../class/class.Mail.php');

$error   = '';
$success = '';

if (isset($_POST['btnRegister'])) {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $objUser = new User();

        if ($objUser->CheckEmailExists($email)) {
            $error = 'Email sudah terdaftar! Silakan gunakan email lain atau login.';
        } else {
            $objUser->email    = $email;
            $objUser->password = password_hash($password, PASSWORD_DEFAULT);
            $objUser->name     = $name;
            $objUser->role     = 'customer';
            $objUser->avatar   = '';

            // Handle upload avatar (opsional)
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg','image/png','image/gif','image/webp'];
                $maxSize      = 2 * 1024 * 1024;
                if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
                    $error = 'Format foto tidak didukung. Gunakan JPG/PNG/WEBP.';
                } elseif ($_FILES['avatar']['size'] > $maxSize) {
                    $error = 'Ukuran foto maksimal 2MB.';
                } else {
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
                    $ext      = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                    $filename = time() . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $filename)) {
                        $objUser->avatar = $filename;
                    }
                }
            }

            if (empty($error)) {
                $objUser->AddUser();
                if ($objUser->hasil) {
                    @Mail::SendWelcome($email, $name);
                    // Auto login setelah register
                    $_SESSION['userid'] = $objUser->userid ?? 0;
                    // Get userid dari DB
                    $newUser = new User();
                    $newUser->ValidateEmail($email);
                    if ($newUser->hasil) {
                        $_SESSION['userid'] = $newUser->userid;
                        $_SESSION['role']   = $newUser->role;
                        $_SESSION['name']   = $newUser->name;
                        $_SESSION['email']  = $newUser->email;
                        header('Location: dashboardcustomer.php?welcome=1');
                        exit();
                    } else {
                        header('Location: index.php?p=login&registered=1');
                        exit();
                    }
                } else {
                    $error = 'Registrasi gagal. Silakan coba lagi.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar Member — Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Boldonse&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{background:#070103;font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px 20px}
        .back-link{position:fixed;top:24px;left:32px;color:#8A7E6C;font-size:13px;text-decoration:none;display:flex;align-items:center;gap:6px;transition:color 0.2s;z-index:10}
        .back-link:hover{color:#8bd100;text-decoration:none}
        .logo-top{position:fixed;top:20px;left:50%;transform:translateX(-50%);font-family:'Boldonse',sans-serif;font-size:20px;color:#8bd100;letter-spacing:0.08em;z-index:10;text-decoration:none}
        .logo-top span{color:#fff}
        .auth-card{background:#1f0410;border:1px solid #2f0618;border-radius:24px;padding:44px 40px;width:100%;max-width:500px;box-shadow:0 20px 60px rgba(0,0,0,0.7)}
        .auth-title{font-family:'Boldonse',sans-serif;font-size:24px;text-align:center;color:#fff;margin-bottom:6px}
        .auth-sub{text-align:center;color:#8A7E6C;font-size:13px;margin-bottom:28px}
        .fgroup{margin-bottom:16px}
        .fgroup label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase;margin-bottom:7px;display:block;font-weight:600}
        .finput{background:rgba(0,0,0,0.5);border:1px solid #2f0618;border-radius:12px;height:48px;color:#fff;font-size:14px;padding:0 16px;width:100%;transition:all 0.3s;display:block;outline:none}
        .finput:focus{border-color:#e81b7b;box-shadow:0 0 10px rgba(232,27,123,0.2)}
        .pass-wrap{position:relative}
        .pass-wrap .finput{padding-right:46px}
        .eye-btn{position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#8A7E6C;font-size:14px;background:none;border:none;padding:0;transition:color 0.2s}
        .eye-btn:hover{color:#e81b7b}
        .file-hint{font-size:11px;color:#555;margin-top:5px}
        .btn-submit{background:#8bd100;color:#000;font-weight:700;font-size:13px;letter-spacing:0.06em;text-transform:uppercase;height:50px;width:100%;border-radius:30px;border:none;cursor:pointer;transition:all 0.3s;margin-top:10px;display:flex;align-items:center;justify-content:center;gap:8px}
        .btn-submit:hover{background:#fff;transform:translateY(-2px)}
        .switch-txt{font-size:13px;color:#8A7E6C;text-align:center;margin-top:20px}
        .switch-txt a{color:#8bd100;font-weight:700;text-decoration:none}
        .switch-txt a:hover{color:#fff}
        .alert-err{background:#3a0a0a;border:1px solid #8a2020;border-radius:10px;padding:12px 16px;color:#ff8888;font-size:13px;margin-bottom:18px;display:flex;align-items:center;gap:8px}
    </style>
</head>
<body>
    <a href="index.php" class="back-link">
        <span class="glyphicon glyphicon-arrow-left"></span> Kembali
    </a>
    <a href="index.php" class="logo-top">AFTER<span>HOUR</span></a>

    <div class="auth-card">
        <h4 class="auth-title">Daftar Member</h4>
        <p class="auth-sub">Bergabung dan nikmati pengalaman premium Afterhour</p>

        <?php if ($error): ?>
        <div class="alert-err">
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data">
            <div class="fgroup">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="finput" placeholder="Nama Anda"
                       value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>"
                       required autocomplete="name">
            </div>
            <div class="fgroup">
                <label>Alamat Email</label>
                <input type="email" name="email" class="finput" placeholder="email@domain.com"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                       required autocomplete="email">
            </div>
            <div class="fgroup">
                <label>Kata Sandi <span style="color:#444">(min. 6 karakter)</span></label>
                <div class="pass-wrap">
                    <input type="password" name="password" id="pwdInput" class="finput"
                           placeholder="Min. 6 karakter" required minlength="6" autocomplete="new-password">
                    <button type="button" class="eye-btn" id="eyeBtn">
                        <span class="glyphicon glyphicon-eye-open" id="eyeIcon"></span>
                    </button>
                </div>
            </div>
            <div class="fgroup">
                <label>Foto Profil <span style="color:#444">(Opsional)</span></label>
                <input type="file" name="avatar" class="finput" style="padding-top:12px" accept="image/jpeg,image/png,image/webp,image/gif">
                <p class="file-hint">Format: JPG, PNG, WEBP &bull; Maks. 2MB</p>
            </div>
            <button type="submit" name="btnRegister" class="btn-submit">
                DAFTAR SEKARANG <span class="glyphicon glyphicon-ok"></span>
            </button>
        </form>

        <div class="switch-txt">
            Sudah punya akun? <a href="index.php?p=login">MASUK DI SINI</a>
        </div>
    </div>

    <script>
    document.getElementById('eyeBtn').addEventListener('click', function() {
        var inp  = document.getElementById('pwdInput');
        var icon = document.getElementById('eyeIcon');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.replace('glyphicon-eye-open','glyphicon-eye-close');
        } else {
            inp.type = 'password';
            icon.classList.replace('glyphicon-eye-close','glyphicon-eye-open');
        }
    });
    </script>
</body>
</html>
