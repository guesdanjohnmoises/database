<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$res = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center">
    <h2 class="mt-3">Products</h2>
    <a class="btn btn-success mt-3" href="product_add.php">+ Add Product</a>
  </div>

  <div class="row mt-3">
    <?php while($p = $res->fetch_assoc()): ?>
      <div class="col-md-3">
        <div class="product-card mb-3">
          <?php if ($p['image'] && file_exists('uploads/'.$p['image'])): ?>
            <img src="uploads/<?=htmlspecialchars($p['image'])?>" alt="">
          <?php else: ?>
            <img src="https://via.placeholder.com/300x160?text=No+Image" alt="">
          <?php endif; ?>
          <h5 class="mt-2"><?=htmlspecialchars($p['name'])?></h5>
          <p class="small-muted"><?=htmlspecialchars($p['category'])?></p>
          <p><strong>₱<?=number_format($p['price'],2)?></strong></p>
          <p class="small-muted">Stock: <?=intval($p['stock'])?></p>
          <div class="mb-2">
            <a class="btn btn-sm btn-primary" href="product_edit.php?id=<?=$p['id']?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="product_delete.php?id=<?=$p['id']?>" onclick="return confirm('Delete?')">Delete</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
</body>
</html>
