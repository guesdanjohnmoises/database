<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['oldpass']; $new = $_POST['newpass']; $id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param('i',$id); $stmt->execute(); $r = $stmt->get_result()->fetch_assoc(); $stmt->close();
    if ($r && (password_verify($old, $r['password']) || $old === $r['password'])) {
        $nh = password_hash($new, PASSWORD_DEFAULT);
        $up = $conn->prepare("UPDATE users SET password=? WHERE id=?"); $up->bind_param('si',$nh,$id); $up->execute(); $up->close();
        $msg = 'Password updated';
    } else $msg = 'Old password incorrect';
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div class="container-card">
    <h3>Change Password</h3>
    <?php if ($msg): ?><div class="alert alert-info"><?=$msg?></div><?php endif; ?>
    <form method="post">
      <input class="form-control mb-2" name="oldpass" placeholder="Old password" type="password" required>
      <input class="form-control mb-2" name="newpass" placeholder="New password" type="password" required>
      <div><button class="btn btn-primary">Change</button></div>
    </form>
  </div>
</div>
</body>
</html>
