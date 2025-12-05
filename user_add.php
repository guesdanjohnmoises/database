<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    if ($stmt->execute()) { header("Location: users.php"); exit; } else { $error = $stmt->error; }
    $stmt->close();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add User</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Add User</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="username" placeholder="Username" required>
    <input class="form-control" name="password" type="password" placeholder="Password" required>
    <select class="form-control" name="role">
      <option value="admin">admin</option>
      <option value="staff" selected>staff</option>
    </select>
    <button class="btn" type="submit">Create</button>
  </form>
</div>
</body></html>
