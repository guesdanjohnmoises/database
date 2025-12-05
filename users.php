<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$res = $conn->query("SELECT id, username, role FROM users ORDER BY id DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Users</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Users</h2>
    <a class="btn" href="user_add.php">+ Add User</a>
  </div>
  <table class="table">
    <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($u = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td>
          <a class="btn" href="user_edit.php?id=<?= $u['id'] ?>">Edit</a>
          <a class="btn btn-danger" href="user_delete.php?id=<?= $u['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body></html>
