<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute(); $product = $stmt->get_result()->fetch_assoc(); $stmt->close();
if (!$product) { header('Location: products.php'); exit; }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']); $category = trim($_POST['category']);
    $price = floatval($_POST['price']); $stock = intval($_POST['stock']);
    $img = $product['image'];
    if (!empty($_FILES['image']['name'])) {
        if ($img && file_exists('uploads/'.$img)) unlink('uploads/'.$img);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $img = uniqid().'.'.$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/uploads/'.$img);
    }
    $up = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=?, image=? WHERE id=?");
    $up->bind_param('ssdssi',$name,$category,$price,$stock,$img,$id);
    if ($up->execute()) { header('Location: products.php'); exit; } else $err='DB error';
    $up->close();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Product</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div class="container-card">
    <h3>Edit Product</h3>
    <?php if ($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
    <form method="post" enctype="multipart/form-data">
      <input class="form-control mb-2" name="name" value="<?=htmlspecialchars($product['name'])?>" required>
      <select class="form-control mb-2" name="category" required>
        <option <?= $product['category']==='Clothes'?'selected':'' ?>>Clothes</option>
        <option <?= $product['category']==='Food'?'selected':'' ?>>Food</option>
        <option <?= $product['category']==='Gadgets'?'selected':'' ?>>Gadgets</option>
        <option <?= $product['category']==='Shoes'?'selected':'' ?>>Shoes</option>
        <option <?= $product['category']==='Accessories'?'selected':'' ?>>Accessories</option>
      </select>
      <input class="form-control mb-2" name="price" type="number" step="0.01" value="<?=htmlspecialchars($product['price'])?>" required>
      <input class="form-control mb-2" name="stock" type="number" value="<?=htmlspecialchars($product['stock'])?>" required>
      <div class="mb-2">
        <?php if ($product['image'] && file_exists('uploads/'.$product['image'])): ?>
          <img src="uploads/<?=htmlspecialchars($product['image'])?>" style="max-width:200px; display:block; margin-bottom:8px;">
        <?php endif; ?>
        <input class="form-control" name="image" type="file" accept="image/*">
      </div>
      <div><button class="btn btn-primary">Update Product</button></div>
    </form>
  </div>
</div>
</body>
</html>
