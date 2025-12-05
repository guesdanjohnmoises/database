<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$products = $conn->query("SELECT * FROM products WHERE stock > 0");
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = trim($_POST['customer']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    $pstmt = $conn->prepare("SELECT price, stock FROM products WHERE id=?");
    $pstmt->bind_param("i", $product_id);
    $pstmt->execute();
    $prod = $pstmt->get_result()->fetch_assoc();
    $pstmt->close();

    if (!$prod) $error = "Invalid product selected.";
    elseif ($quantity <= 0 || $quantity > intval($prod['stock'])) $error = "Invalid quantity or not enough stock.";
    else {
        $total = $prod['price'] * $quantity;
        $ins = $conn->prepare("INSERT INTO orders (product_id, quantity, total, order_date, customer) VALUES (?, ?, ?, NOW(), ?)");
        // note: table originally didn't have customer in SQL; we will place customer column dynamically: if not present store as 'Walk-in'
        // But for compatibility, we used customer in the SELECT/INSERT above; to avoid mismatch, we will set customer default below if column not exists.
        // For simplicity here, we'll use a version with customer parameter only if column present.

        // check customer column existence
        $colRes = $conn->query("SHOW COLUMNS FROM orders LIKE 'customer'");
        if ($colRes->num_rows === 1) {
            $ins = $conn->prepare("INSERT INTO orders (product_id, quantity, total, order_date, customer) VALUES (?, ?, ?, NOW(), ?)");
            $ins->bind_param("iids", $product_id, $quantity, $total, $customer);
        } else {
            $ins = $conn->prepare("INSERT INTO orders (product_id, quantity, total, order_date) VALUES (?, ?, ?, NOW())");
            $ins->bind_param("iid", $product_id, $quantity, $total);
        }

        if ($ins->execute()) {
            // decrement stock
            $up = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id=?");
            $up->bind_param("ii", $quantity, $product_id);
            $up->execute();
            $up->close();
            header("Location: orders.php");
            exit;
        } else {
            $error = $ins->error;
        }
        $ins->close();
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Add Order</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Add Order</h2>
  <?php if ($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <input class="form-control" name="customer" placeholder="Customer name (optional)">
    <select class="form-control" name="product_id" required>
      <option value="">-- Choose product --</option>
      <?php while ($p = $products->fetch_assoc()): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?> (Stock: <?= intval($p['stock']) ?>) - ₱<?= number_format($p['price'],2) ?></option>
      <?php endwhile; ?>
    </select>
    <input class="form-control" name="quantity" type="number" value="1" required>
    <button class="btn" type="submit">Place Order</button>
  </form>
</div>
</body></html>
