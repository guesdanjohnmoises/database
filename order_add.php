<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$products = $conn->query("SELECT * FROM products WHERE stock>0");
$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $customer = trim($_POST['customer']);
    $product_id = intval($_POST['product_id']);
    $qty = intval($_POST['quantity']);
    // get price and stock
    $s = $conn->prepare("SELECT price, stock FROM products WHERE id=?"); $s->bind_param('i',$product_id); $s->execute(); $p = $s->get_result()->fetch_assoc(); $s->close();
    if (!$p) { $err='Invalid product'; }
    elseif ($qty <= 0 || $qty > $p['stock']) { $err='Invalid quantity or out of stock'; }
    else {
        $total = $p['price'] * $qty;
        $ins = $conn->prepare("INSERT INTO orders (customer, product_id, quantity, total_price) VALUES (?,?,?,?)");
        $ins->bind_param('siid',$customer,$product_id,$qty,$total); $ins->execute(); $ins->close();
        // decrement stock
        $upd = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id=?"); $upd->bind_param('ii',$qty,$product_id); $upd->execute(); $upd->close();
        header('Location: orders.php'); exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Add Order</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body><?php include 'navbar.php'; ?>
<div class="container"><div class="container-card">
  <h3>Add Order</h3><?php if($err) echo "<div class='alert alert-danger'>$err</div>"; ?>
  <form method="post">
    <input class="form-control mb-2" name="customer" placeholder="Customer name" required>
    <select class="form-control mb-2" name="product_id" required>
      <option value="">-- Select product --</option>
      <?php foreach($products as $p): ?>
        <option value="<?=$p['id']?>"><?=htmlspecialchars($p['name'])?> (Stock: <?=$p['stock']?>) - ₱<?=number_format($p['price'],2)?></option>
      <?php endforeach; ?>
    </select>
    <input class="form-control mb-2" name="quantity" type="number" value="1" required>
    <button class="btn btn-success">Place Order</button>
  </form>
</div></div></body></html>
