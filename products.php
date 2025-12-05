<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$res = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Products</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Products</h2>
    <a class="btn" href="product_add.php">+ Add Product</a>
  </div>

  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($r = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['category']) ?></td>
          <td>₱<?= number_format($r['price'],2) ?></td>
          <td><?= intval($r['stock']) ?></td>
          <td>
            <a class="btn" href="product_edit.php?id=<?= $r['id'] ?>">Edit</a>
            <a class="btn btn-danger" href="product_delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body></html>
