<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$res = $conn->query("SELECT o.*, p.name AS product_name FROM orders o LEFT JOIN products p ON p.id=o.product_id ORDER BY o.id DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body><?php include 'navbar.php'; ?>
<div class="container"><div class="container-card">
  <div class="d-flex justify-content-between"><h3>Orders</h3><a class="btn btn-success" href="order_add.php">+ Add Order</a></div>
  <table class="table mt-3">
    <thead><tr><th>ID</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while($o=$res->fetch_assoc()): ?>
      <tr>
        <td><?=$o['id']?></td>
        <td><?=htmlspecialchars($o['customer'])?></td>
        <td><?=htmlspecialchars($o['product_name'])?></td>
        <td><?=$o['quantity']?></td>
        <td>₱<?=number_format($o['total_price'],2)?></td>
        <td><?=$o['order_date']?></td>
        <td><a class="btn btn-sm btn-primary" href="order_edit.php?id=<?=$o['id']?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="order_delete.php?id=<?=$o['id']?>" onclick="return confirm('Delete?')">Delete</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div></div></body></html>
