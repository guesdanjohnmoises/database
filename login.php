<?php
// login.php
require_once 'config.php';
if (isset($_SESSION['user_id'])) header('Location: admin.php');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, fullname, role FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();
        $hash = $u['password'];

        // login works if password_verify OR if plain-text matches (then upgrade to hash)
        if (password_verify($password, $hash)) {
            // ok
        } elseif ($password === $hash) {
            // legacy plain text - upgrade to hashed password
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $up->bind_param('si', $newHash, $u['id']);
            $up->execute();
        } else {
            $msg = 'Invalid credentials';
            $stmt->close();
            goto render;
        }

        // success
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['username'] = $u['username'];
        $_SESSION['fullname'] = $u['fullname'];
        $_SESSION['role'] = $u['role'];
        header('Location: admin.php'); exit;
    } else {
        $msg = 'Invalid credentials';
    }
    $stmt->close();
}

render:
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - PHP CRUD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="container-card">
          <h3 class="mb-3">Sign in</h3>
          <?php if ($msg): ?>
            <div class="alert alert-danger"><?=htmlspecialchars($msg)?></div>
          <?php endif; ?>
          <form method="post" novalidate>
            <div class="mb-3"><input name="username" class="form-control" placeholder="Username" required></div>
            <div class="mb-3"><input name="password" type="password" class="form-control" placeholder="Password" required></div>
            <div class="d-grid">
              <button class="btn btn-primary">Login</button>
            </div>
          </form>
          <p class="mt-3 small-muted">Default admin: <strong>admin</strong> / <strong>admin123</strong></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
