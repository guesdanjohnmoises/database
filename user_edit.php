<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT id, username, role FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$user) { header("Location: users.php"); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $up = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
    $up->bind_param("ssi", $username, $role, $id);
    if ($up->execute()) {
        if (!empty($_POST['password'])) {
            $nh = md5($_POST['password']);
            $ps = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $ps->bind_param("si", $nh, $id);
            $ps->execute();
            $ps->close();
        }
        header("Location: users.php");
        exit;
    } else {
        $error = $up->error;
    }
    $up->close();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit User</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Edit User</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="username" value="<?=htmlspecialchars($user['username'])?>" required>
    <input class="form-control" name="password" placeholder="Leave blank to keep current">
    <select class="form-control" name="role">
      <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>admin</option>
      <option value="staff" <?= $user['role']==='staff'?'selected':'' ?>>staff</option>
    </select>
    <button class="btn" type="submit">Save</button>
  </form>
</div>
</body></html>
