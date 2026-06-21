<?php
require_once('./class/class.User.php');

if (isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $objUser = new User();
    $objUser->ValidateEmail($email);

    if ($objUser->hasil) {
        $isMatch = password_verify($password, $objUser->password);
        if ($isMatch) {
            if (!isset($_SESSION))
                session_start();

            $_SESSION["userid"] = $objUser->userid;
            $_SESSION["role"] = $objUser->role;
            $_SESSION["name"] = $objUser->name;
            $_SESSION["email"] = $objUser->email;

            echo "<script>alert('Selamat datang di Afterhour!');</script>";

            if ($_SESSION["role"] == 'admin') {
                echo '<script>window.location = "dashboardadmin.php";</script>';
            } else {
                echo '<script>window.location = "dashboardcustomer.php";</script>';
            }
            exit();
        } else {
            echo "<script>alert('Kombinasi password salah!');</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar di sistem kami!');</script>";
    }
}
?>

<style>
    .auth-container-section {
        padding: 160px 20px 100px;
        min-height: calc(100vh - 90px);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #000000;
        background-repeat: no-repeat !important;
        background-size: 110% auto !important;
        background-position: -30% center !important;
    }

    .auth-editorial-card {
        background: #1f0410 !important;
        border: 1px solid #2f0618 !important;
        border-radius: 24px !important;
        padding: 50px 40px;
        max-width: 480px;
        width: 100%;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    .auth-editorial-title {
        font-size: 32px !important;
        margin-bottom: 30px !important;
        letter-spacing: 0.02em !important;
        text-align: center;
    }

    .auth-form-group {
        margin-bottom: 22px;
    }

    .auth-form-group label {
        font-family: 'DM Sans', sans-serif !important;
        font-weight: 500;
        color: #8A7E6C;
        font-size: 13px;
        text-transform: none;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }

    .auth-form-input {
        background: rgba(0, 0, 0, 0.4) !important;
        border: 1px solid #1f030f !important;
        border-radius: 12px !important;
        height: 50px;
        color: #ffffff !important;
        font-family: 'DM Sans', sans-serif !important;
        font-size: 15px;
        padding: 0 18px;
        width: 100%;
        transition: all 0.3s;
    }

    .auth-form-input:focus {
        border-color: #e81b7b !important;
        outline: none;
        box-shadow: 0 0 10px rgba(232, 27, 123, 0.2);
    }

    .auth-switch-text {
        font-family: 'DM Sans', sans-serif !important;
        font-size: 14px;
        color: #8A7E6C;
        text-align: center;
        margin-top: 25px;
    }

    .auth-switch-text a {
        color: #8bd100;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.3s;
    }

    .auth-switch-text a:hover {
        color: #ffffff;
    }

    .auth-input-group {
    position: relative;
    width: 100%;
    }

    .auth-toggle-password {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #8A7E6C;
        font-size: 16px;
        transition: color 0.3s;
        z-index: 10;
    }

    .auth-toggle-password:hover {
        color: #e81b7b;
    }
</style>

<div class="auth-container-section">
    <div class="auth-editorial-card">
        <h4 class="auth-editorial-title font-horizon">Masuk ke Akunmu</h4>
        
        <form action="" method="post">
            <div class="auth-form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" class="auth-form-input" required autocomplete="off">
            </div>
            
            <div class="auth-form-group">
                <label>Kata Sandi</label>
                <div class="auth-input-group">
                    <input type="password" name="password" id="passwordInput" class="auth-form-input" required>
                    <span class="glyphicon glyphicon-eye-open auth-toggle-password" id="togglePassword"></span>
                </div>
            </div>
            
            <button type="submit" name="btnLogin" class="btn-gold-action" style="width: 100%; justify-content: center; margin-top: 10px;">
                MASUK
            </button>
        </form>

        <div class="auth-switch-text">
            Belum punya akun? <a href="index.php?p=register">DAFTAR MEMBER SEKARANG!</a>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordInput = document.getElementById('passwordInput');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.remove('glyphicon-eye-open');
            this.classList.add('glyphicon-eye-close');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('glyphicon-eye-close');
            this.classList.add('glyphicon-eye-open');
        }
    });
</script>