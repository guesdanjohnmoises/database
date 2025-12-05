<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$product) { header("Location: products.php"); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $up = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=? WHERE id=?");
    $up->bind_param("ssdii", $name, $category, $price, $stock, $id);
    if ($up->execute()) { header("Location: products.php"); exit; } else { $error = $up->error; }
    $up->close();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit Product</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Edit Product</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="name" value="<?=htmlspecialchars($product['name'])?>" required>
    <select class="form-control" name="category" required>
      <option <?= $product['category']==='Food'?'selected':'' ?>>Food</option>
      <option <?= $product['category']==='Clothing'?'selected':'' ?>>Clothing</option>
      <option <?= $product['category']==='Electronics'?'selected':'' ?>>Electronics</option>
      <option <?= $product['category']==='Accessories'?'selected':'' ?>>Accessories</option>
      <option <?= $product['category']==='Home'?'selected':'' ?>>Home</option>
    </select>
    <input class="form-control" name="price" type="number" step="0.01" value="<?=htmlspecialchars($product['price'])?>" required>
    <input class="form-control" name="stock" type="number" value="<?=intval($product['stock'])?>" required>
    <button class="btn" type="submit">Update</button>
  </form>
</div>
</body></html>
