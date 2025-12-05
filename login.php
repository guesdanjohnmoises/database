<?php
session_start();
require_once 'config.php';
if (!empty($_SESSION['user'])) { header("Location: admin.php"); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows === 1) {
        $u = $res->fetch_assoc();
        $_SESSION['user'] = $u['username'];
        $_SESSION['role'] = $u['role'];
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="login-box">
  <h2>Sign in</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="username" placeholder="Username" required>
    <input class="form-control" name="password" placeholder="Password" type="password" required>
    <button class="btn" type="submit">Login</button>
  </form>
  <p class="header-small" style="margin-top:10px;">Default admin: <strong>admin</strong> / <strong>admin123</strong></p>
</div>
</body>
</html>
