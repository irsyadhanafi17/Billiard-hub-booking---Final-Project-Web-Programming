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
        // W13: Amankan password mentah menggunakan hash standar bawaan PHP
        $objUser->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $objUser->name = $_POST['name'];
        $objUser->role = 'customer'; // Default role pendaftar baru

        // W14: Logika penanganan upload file berkas gambar avatar profil
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

<div class="row">
    <div class="col-md-6">
        <h4>Form Pendaftaran Member Afterhour</h4>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Foto Profil (Opsional)</label>
                <input type="file" name="avatar" class="form-control">
            </div>
            <input type="submit" name="btnRegister" class="btn btn-primary" value="Daftar Sekarang">
        </form>
    </div>
</div>