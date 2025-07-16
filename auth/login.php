<?php
session_start();
require_once '../config/database.php';

$error = '';
$username = '';

// Check for messages from other pages (e.g., successful password reset)
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        $sql = "SELECT id_user, username, password, role FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("location: ../dashboard.php"); // Redirect to dashboard
                exit;
            } else {
                $error = 'Incorrect username or password.';
            }
        } else {
            $error = 'Incorrect username or password.';
        }
        $stmt->close();
    }
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiTernak Kambing</title>
    <link rel="stylesheet" href="css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-icon">
            <img src="../assets/images/icons/user-color.png" alt="User Icon">
        </div>

        <div class="auth-header">
            <h1>Welcome Back!</h1>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="auth-form">
            <div class="form-group">
                <span class="input-icon">👤</span>
                <input type="text" name="username" id="username" required value="<?php echo htmlspecialchars($username); ?>" placeholder="Username">
            </div>
            <div class="form-group">
                <span class="input-icon">🔒</span>
                <input type="password" name="password" id="password" required placeholder="Password">
            </div>
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <button type="submit" class="auth-button">LOGIN</button>
            <div class="auth-footer">
                <p>Don't have an account? <a href="../register.php">Sign Up</a></p>
            </div>
        </form>
    </div>
</body>
</html>