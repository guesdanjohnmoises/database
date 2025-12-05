<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$res = $conn->query("SELECT o.*, p.name AS product_name FROM orders o LEFT JOIN products p ON p.id=o.product_id ORDER BY o.id DESC");
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Orders</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Orders</h2>
    <a class="btn" href="order_add.php">+ Add Order</a>
  </div>
  <table class="table">
    <thead><tr><th>ID</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($o = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['customer']) ?></td>
        <td><?= htmlspecialchars($o['product_name']) ?></td>
        <td><?= intval($o['quantity']) ?></td>
        <td>₱<?= number_format($o['total'],2) ?></td>
        <td><?= $o['order_date'] ?></td>
        <td>
          <a class="btn" href="order_edit.php?id=<?= $o['id'] ?>">Edit</a>
          <a class="btn btn-danger" href="order_delete.php?id=<?= $o['id'] ?>" onclick="return confirm('Delete order?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body></html>
