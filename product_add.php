<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $sql = "INSERT INTO products (name, category, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param("ssdi", $name, $category, $price, $stock);
    if ($stmt->execute()) {
        header("Location: products.php");
        exit;
    } else {
        $error = "Insert failed: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add Product</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Add Product</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="name" placeholder="Product name" required>
    <select class="form-control" name="category" required>
      <option value="">-- Select category --</option>
      <option>Food</option>
      <option>Clothing</option>
      <option>Electronics</option>
      <option>Accessories</option>
      <option>Home</option>
    </select>
    <input class="form-control" name="price" type="number" step="0.01" placeholder="Price" required>
    <input class="form-control" name="stock" type="number" value="1" required>
    <button class="btn" type="submit">Save</button>
  </form>
</div>
</body></html>
