<?php
require_once('./class/class.User.php');

if (isset($_POST['btnRegister'])) {
    $email = $_POST['email'];
    $objUser = new User();
    $objUser->ValidateEmail($email);

    if ($objUser->hasil) {
        echo "<script>alert('Email sudah digunakan di sistem Afterhour!');</script>";
    } else {
        $objUser->email = $email;
        $objUser->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $objUser->name = $_POST['name'];
        $objUser->role = 'customer'; 

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $folderTujuan = "uploads/";
            $namaFile = time() . "_" . basename($_FILES["avatar"]["name"]);
            $targetPath = $folderTujuan . $namaFile;

            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetPath)) {
                $objUser->avatar = $namaFile;
            }
        }

        $objUser->AddUser();
        if ($objUser->hasil) {
            echo "<script>alert('Registrasi sukses! Silakan login.');</script>";
            echo '<script>window.location="index.php?p=login";</script>';
        }
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
        background-position: 130% center !important;
    }

    .auth-editorial-card {
        background: #1f0410 !important;
        border: 1px solid #2f0618 !important;
        border-radius: 24px !important;
        padding: 50px 40px;
        max-width: 520px;
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
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }

    .auth-form-input {
        background: rgba(0, 0, 0, 0.4) !important;
        border: 1px solid #2f0618 !important;
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

    .auth-file-input-wrapper {
        position: relative;
        width: 100%;
    }

    .auth-file-input-wrapper input[type="file"] {
        padding-top: 12px;
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
        <h4 class="auth-editorial-title font-horizon">REGISTRASI</h4>
        
        <form action="" method="post" enctype="multipart/form-data">
            <div class="auth-form-group">
                <label>Nama</label>
                <input type="text" name="name" class="auth-form-input" required autocomplete="off">
            </div>

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

            <div class="auth-form-group">
                <label>Foto Profil (Opsional)</label>
                <div class="auth-file-input-wrapper">
                    <input type="file" name="avatar" class="auth-form-input">
                </div>
            </div>
            
            <button type="submit" name="btnRegister" class="btn-gold-action" style="width: 100%; justify-content: center; margin-top: 10px;">
                SELESAI & DAFTAR
            </button>
        </form>

        <div class="auth-switch-text">
            Sudah punya akun? <a href="index.php?p=login">MASUK DI SINI</a>
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