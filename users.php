<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$res = $conn->query("SELECT id, username, fullname, role FROM users ORDER BY id DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body><?php include 'navbar.php'; ?>
<div class="container">
  <div class="container-card">
    <div class="d-flex justify-content-between">
      <h3>Users</h3>
      <a class="btn btn-success" href="user_add.php">+ Add User</a>
    </div>
    <table class="table mt-3">
      <thead><tr><th>ID</th><th>Username</th><th>Fullname</th><th>Role</th><th>Actions</th></tr></thead>
      <tbody>
      <?php while($u = $res->fetch_assoc()): ?>
        <tr>
          <td><?=$u['id']?></td>
          <td><?=htmlspecialchars($u['username'])?></td>
          <td><?=htmlspecialchars($u['fullname'])?></td>
          <td><?=htmlspecialchars($u['role'])?></td>
          <td class="table-actions">
            <a class="btn btn-sm btn-primary" href="user_edit.php?id=<?=$u['id']?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="user_delete.php?id=<?=$u['id']?>" onclick="return confirm('Delete?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
