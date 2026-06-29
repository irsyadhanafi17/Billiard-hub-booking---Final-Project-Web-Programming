<?php
if (!isset($_SESSION)) { session_start(); }

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') { header('Location: dashboardadmin.php'); exit(); }
    if ($_SESSION['role'] === 'manager') { header('Location: dashboardmanager.php'); exit(); }
    header('Location: dashboardcustomer.php'); exit();
}

require_once(__DIR__.'/../class/class.User.php');

$error = '';

if (isset($_POST['btnLogin'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $objUser = new User();
        $objUser->ValidateEmail($email);

        if ($objUser->hasil) {
            $storedPass = $objUser->password;
            $isHash     = (strlen($storedPass) >= 60 && substr($storedPass, 0, 4) === '$2y$');
            $isMatch    = $isHash ? password_verify($password, $storedPass) : ($password === $storedPass);

            if ($isMatch) {
                if (!$isHash) {
                    $objUser->UpgradePassword(password_hash($password, PASSWORD_DEFAULT));
                }
                $_SESSION['userid'] = $objUser->userid;
                $_SESSION['role']   = $objUser->role;
                $_SESSION['name']   = $objUser->name;
                $_SESSION['email']  = $objUser->email;

                if ($_SESSION['role'] === 'admin') {
                    header('Location: dashboardadmin.php'); exit();
                } elseif ($_SESSION['role'] === 'manager') {
                    header('Location: dashboardmanager.php'); exit();
                } else {
                    header('Location: dashboardcustomer.php'); exit();
                }
            } else {
                $error = 'Password salah! Silakan coba lagi.';
            }
        } else {
            $error = 'Email tidak terdaftar di sistem kami!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Masuk — Afterhour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Boldonse&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{background:#070103;font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .back-link{position:fixed;top:24px;left:32px;color:#8A7E6C;font-size:13px;text-decoration:none;display:flex;align-items:center;gap:6px;transition:color 0.2s;z-index:10}
        .back-link:hover{color:#8bd100;text-decoration:none}
        .logo-top{position:fixed;top:20px;left:50%;transform:translateX(-50%);font-family:'Boldonse',sans-serif;font-size:20px;color:#8bd100;letter-spacing:0.08em;z-index:10;text-decoration:none}
        .logo-top span{color:#fff}
        .auth-card{background:#1f0410;border:1px solid #2f0618;border-radius:24px;padding:48px 40px;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.7)}
        .auth-title{font-family:'Boldonse',sans-serif;font-size:26px;text-align:center;color:#fff;margin-bottom:6px}
        .auth-sub{text-align:center;color:#8A7E6C;font-size:13px;margin-bottom:30px}
        .fgroup{margin-bottom:18px}
        .fgroup label{font-size:11px;color:#8A7E6C;letter-spacing:1px;text-transform:uppercase;margin-bottom:7px;display:block;font-weight:600}
        .finput{background:rgba(0,0,0,0.5);border:1px solid #2f0618;border-radius:12px;height:50px;color:#fff;font-size:15px;padding:0 18px;width:100%;transition:all 0.3s;display:block;outline:none}
        .finput:focus{border-color:#e81b7b;box-shadow:0 0 10px rgba(232,27,123,0.2)}
        .pass-wrap{position:relative}
        .pass-wrap .finput{padding-right:46px}
        .eye-btn{position:absolute;right:16px;top:50%;transform:translateY(-50%);cursor:pointer;color:#8A7E6C;font-size:15px;transition:color 0.2s;background:none;border:none;padding:0}
        .eye-btn:hover{color:#e81b7b}
        .btn-submit{background:#8bd100;color:#000;font-weight:700;font-size:13px;letter-spacing:0.06em;text-transform:uppercase;height:50px;width:100%;border-radius:30px;border:none;cursor:pointer;transition:all 0.3s;margin-top:8px;display:flex;align-items:center;justify-content:center;gap:8px}
        .btn-submit:hover{background:#fff;transform:translateY(-2px)}
        .divider{display:flex;align-items:center;gap:12px;margin:20px 0}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:#2f0618}
        .divider span{color:#555;font-size:12px}
        .switch-txt{font-size:13px;color:#8A7E6C;text-align:center}
        .switch-txt a{color:#8bd100;font-weight:700;text-decoration:none}
        .switch-txt a:hover{color:#fff}
        .alert-err{background:#3a0a0a;border:1px solid #8a2020;border-radius:10px;padding:12px 16px;color:#ff8888;font-size:13px;margin-bottom:20px;display:flex;align-items:center;gap:8px}
    </style>
</head>
<body>
    <a href="index.php" class="back-link">
        <span class="glyphicon glyphicon-arrow-left"></span> Kembali
    </a>
    <a href="index.php" class="logo-top">AFTER<span>HOUR</span></a>

    <div class="auth-card">
        <h4 class="auth-title">Selamat Datang</h4>
        <p class="auth-sub">Masuk ke akun Afterhour Member Anda</p>

        <?php if ($error): ?>
        <div class="alert-err">
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="fgroup">
                <label>Alamat Email</label>
                <input type="email" name="email" class="finput" placeholder="email@domain.com"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                       required autocomplete="email">
            </div>
            <div class="fgroup">
                <label>Kata Sandi</label>
                <div class="pass-wrap">
                    <input type="password" name="password" id="pwdInput" class="finput"
                           placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="eye-btn" id="eyeBtn">
                        <span class="glyphicon glyphicon-eye-open" id="eyeIcon"></span>
                    </button>
                </div>
            </div>
            <button type="submit" name="btnLogin" class="btn-submit">
                MASUK <span class="glyphicon glyphicon-log-in"></span>
            </button>
        </form>

        <div class="divider"><span>atau</span></div>
        <div class="switch-txt">
            Belum punya akun? <a href="index.php?p=register">DAFTAR GRATIS</a>
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
