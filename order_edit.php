<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$id = intval($_GET['id'] ?? 0);
$o = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
if (!$o) { header("Location: orders.php"); exit; }
$products = $conn->query("SELECT * FROM products");
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // revert old stock
    $conn->query("UPDATE products SET stock = stock + ".intval($o['quantity'])." WHERE id = ".intval($o['product_id']));

    // check new product stock
    $p = $conn->query("SELECT price, stock FROM products WHERE id=$product_id")->fetch_assoc();
    if (!$p) $error = 'Invalid product';
    elseif ($quantity <= 0 || $quantity > intval($p['stock'])) $error = 'Invalid quantity';
    else {
        $total = $p['price'] * $quantity;
        $up = $conn->prepare("UPDATE orders SET product_id=?, quantity=?, total=? WHERE id=?");
        $up->bind_param("iidi", $product_id, $quantity, $total, $id);
        if ($up->execute()) {
            // decrement stock
            $conn->query("UPDATE products SET stock = stock - $quantity WHERE id = $product_id");
            header("Location: orders.php"); exit;
        } else {
            $error = $up->error;
        }
        $up->close();
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit Order</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Edit Order</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <select class="form-control" name="product_id" required>
      <?php while ($p = $products->fetch_assoc()): ?>
        <option value="<?= $p['id'] ?>" <?= $p['id']==$o['product_id']?'selected':'' ?>><?= htmlspecialchars($p['name']) ?> (Stock: <?= intval($p['stock']) ?>)</option>
      <?php endwhile; ?>
    </select>
    <input class="form-control" name="quantity" type="number" value="<?= intval($o['quantity']) ?>" required>
    <button class="btn" type="submit">Update</button>
  </form>
</div>
</body></html>
