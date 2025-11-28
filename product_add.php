<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $img = null;
    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/uploads/'.$img);
    }
    $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssdis', $name, $category, $price, $stock, $img);
    if ($stmt->execute()) {
        header('Location: products.php'); exit;
    } else $err = 'DB error';
    $stmt->close();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Add Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div class="container-card">
    <h3>Add Product</h3>
    <?php if ($err): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data">
      <input class="form-control mb-2" name="name" placeholder="Product name" required>
      <select class="form-control mb-2" name="category" required>
        <option value="">-- Category --</option>
        <option>Clothes</option>
        <option>Food</option>
        <option>Gadgets</option>
        <option>Shoes</option>
        <option>Accessories</option>
      </select>
      <input class="form-control mb-2" name="price" type="number" step="0.01" placeholder="Price" required>
      <input class="form-control mb-2" name="stock" type="number" value="1" required>
      <input class="form-control mb-3" name="image" type="file" accept="image/*">
      <div><button class="btn btn-success">Save Product</button></div>
    </form>
  </div>
</div>
</body>
</html>
