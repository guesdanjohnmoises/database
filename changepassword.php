<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['user'];
    $old = md5($_POST['oldpass']);
    $new = md5($_POST['newpass']);
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $old);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows === 1) {
        $up = $conn->prepare("UPDATE users SET password=? WHERE username=?");
        $up->bind_param("ss", $new, $username);
        $up->execute();
        $up->close();
        $msg = "Password changed.";
    } else {
        $msg = "Old password incorrect.";
    }
    $stmt->close();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Change Password</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Change Password</h2>
  <?php if ($msg): ?><div class="alert alert-success"><?=htmlspecialchars($msg)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="oldpass" type="password" placeholder="Old password" required>
    <input class="form-control" name="newpass" type="password" placeholder="New password" required>
    <button class="btn" type="submit">Change</button>
  </form>
</div>
</body></html>
