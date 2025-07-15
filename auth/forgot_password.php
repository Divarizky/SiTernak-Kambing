<?php
session_start();
require_once '../config/database.php';

$step = 1;
$error = '';
$nama = '';
$username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['step1'])) {
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);

        if (empty($nama) || empty($username)) {
            $error = 'Nama dan username harus diisi.';
        } else {
            $sql = "SELECT id_user FROM users WHERE nama = ? AND username = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ss", $nama, $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($user_id);
                $stmt->fetch();
                $_SESSION['reset_user_id'] = $user_id;
                $step = 2; // Move to step 2
            } else {
                $error = 'Nama atau username tidak ditemukan.';
            }
            $stmt->close();
        }
    } elseif (isset($_POST['step2'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['reset_user_id'];

        if (empty($new_password) || empty($confirm_password)) {
            $error = 'Semua field password harus diisi.';
            $step = 2;
        } elseif ($new_password !== $confirm_password) {
            $error = 'Password baru dan konfirmasi password tidak cocok.';
            $step = 2;
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id_user = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                unset($_SESSION['reset_user_id']);
                $_SESSION['message'] = 'Password Anda telah berhasil direset. Silakan login.';
                $_SESSION['message_type'] = 'success';
                header("location: login.php");
                exit;
            } else {
                $error = 'Terjadi kesalahan. Gagal memperbarui password.';
                $step = 2;
            }
            $stmt->close();
        }
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SiTernak Kambing</title>
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container auth-container">
        <h2>Lupa Password</h2>
        <p class="reset-instruction">
            <?php echo $step == 1 ? 'Masukkan nama dan username Anda untuk verifikasi.' : 'Buat password baru Anda.'; ?>
        </p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="step1" value="1">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" required value="<?php echo htmlspecialchars($nama); ?>">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <button type="submit" class="login-button">Verifikasi</button>
        </form>
        <?php else: // Step 2 ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="step2" value="1">
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="login-button">Reset Password</button>
        </form>
        <?php endif; ?>
        
        <div class="login-footer">
            <a href="login.php">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>
