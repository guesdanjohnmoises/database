<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$id = intval($_GET['id'] ?? 0);
$o = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
if (!$o) { header('Location: orders.php'); exit; }
$products = $conn->query("SELECT * FROM products");
$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $customer = trim($_POST['customer']); $new_pid = intval($_POST['product_id']); $new_qty = intval($_POST['quantity']);
    // get current order
    $old_qty = $o['quantity']; $old_pid = $o['product_id'];
    // revert old stock
    if ($old_pid) { $conn->query("UPDATE products SET stock = stock + $old_qty WHERE id = $old_pid"); }
    // check new product stock
    $p = $conn->query("SELECT stock, price FROM products WHERE id=$new_pid")->fetch_assoc();
    if (!$p) $err='Invalid product';
    elseif ($new_qty <=0 || $new_qty > $p['stock']) $err='Invalid quantity';
    else {
        $total = $p['price'] * $new_qty;
        $up = $conn->prepare("UPDATE orders SET customer=?, product_id=?, quantity=?, total_price=? WHERE id=?");
        $up->bind_param('siidi',$customer,$new_pid,$new_qty,$total,$id); $up->execute(); $up->close();
        // decrement new stock
        $conn->query("UPDATE products SET stock = stock - $new_qty WHERE id = $new_pid");
        header('Location: orders.php'); exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Edit Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body><?php include 'navbar.php'; ?>
<div class="container"><div class="container-card">
  <h3>Edit Order</h3><?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
  <form method="post">
    <input class="form-control mb-2" name="customer" value="<?=htmlspecialchars($o['customer'])?>" required>
    <select class="form-control mb-2" name="product_id" required>
      <?php while($p = $products->fetch_assoc()): ?>
        <option value="<?=$p['id']?>" <?=$p['id']==$o['product_id']?'selected':''?>><?=htmlspecialchars($p['name'])?> (Stock: <?=$p['stock']?>) - ₱<?=number_format($p['price'],2)?></option>
      <?php endwhile; ?>
    </select>
    <input class="form-control mb-2" name="quantity" type="number" value="<?=$o['quantity']?>" required>
    <button class="btn btn-primary">Update Order</button>
  </form>
</div></div></body></html>
