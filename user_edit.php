<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT id, username, fullname, role FROM users WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute(); $user = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$user) { header('Location: users.php'); exit; }
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username = trim($_POST['username']); $fullname = trim($_POST['fullname']); $role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE users SET username=?, fullname=?, role=? WHERE id=?");
    $stmt->bind_param('sssi',$username,$fullname,$role,$id);
    $stmt->execute(); $stmt->close();
    if (!empty($_POST['password'])) {
        $nh = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $u2 = $conn->prepare("UPDATE users SET password=? WHERE id=?"); $u2->bind_param('si',$nh,$id); $u2->execute(); $u2->close();
    }
    header('Location: users.php'); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body><?php include 'navbar.php'; ?>
<div class="container"><div class="container-card"><h3>Edit User</h3>
<form method="post">
  <input class="form-control mb-2" name="username" value="<?=htmlspecialchars($user['username'])?>" required>
  <input class="form-control mb-2" name="fullname" value="<?=htmlspecialchars($user['fullname'])?>" required>
  <input class="form-control mb-2" name="password" type="password" placeholder="Leave blank to keep current password">
  <select class="form-control mb-2" name="role">
    <option value="admin" <?=$user['role']==='admin'?'selected':''?>>admin</option>
    <option value="staff" <?=$user['role']==='staff'?'selected':''?>>staff</option>
  </select>
  <button class="btn btn-primary">Save</button>
</form>
</div></div></body></html>
