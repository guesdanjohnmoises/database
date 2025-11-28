<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username = trim($_POST['username']); $fullname = trim($_POST['fullname']); $role = $_POST['role'];
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, role) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss',$username,$hash,$fullname,$role);
    if ($stmt->execute()) header('Location: users.php'); else $err='DB error';
    $stmt->close();
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Add User</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body><?php include 'navbar.php'; ?>
<div class="container"><div class="container-card"><h3>Add User</h3><?php if($err) echo "<div class='alert alert-danger'>$err</div>";?>
<form method="post">
  <input class="form-control mb-2" name="username" placeholder="Username" required>
  <input class="form-control mb-2" name="fullname" placeholder="Full name" required>
  <input class="form-control mb-2" name="password" type="password" placeholder="Password" required>
  <select class="form-control mb-2" name="role"><option value="admin">admin</option><option value="staff" selected>staff</option></select>
  <button class="btn btn-success">Create User</button>
</form></div></div></body></html>
