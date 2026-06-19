<?php
require_once('./class/class.User.php');

if (isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $objUser = new User();
    $objUser->ValidateEmail($email);

    if ($objUser->hasil) {
        // W13: Bandingkan password input mentah dengan hash terenkripsi dari DB
        $isMatch = password_verify($password, $objUser->password);
        if ($isMatch) {
            if (!isset($_SESSION))
                session_start();

            // simpan data user ke session  
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

<div class="row">
    <div class="col-md-6">
        <h4>Masuk ke Sistem Afterhour</h4>
        <form action="" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <input type="submit" name="btnLogin" class="btn btn-success" value="Login">
        </form>
    </div>
</div>