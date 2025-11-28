<?php
// admin.php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$counts = [];
$q1 = $conn->query("SELECT COUNT(*) AS c FROM products"); $counts['products'] = $q1->fetch_assoc()['c'];
$q2 = $conn->query("SELECT COUNT(*) AS c FROM orders"); $counts['orders'] = $q2->fetch_assoc()['c'];
$q3 = $conn->query("SELECT COUNT(*) AS c FROM users"); $counts['users'] = $q3->fetch_assoc()['c'];
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <div class="container-card">
    <h2>Dashboard</h2>
    <p class="small-muted">Welcome, <?=htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'])?></p>
    <div class="row mt-4">
      <div class="col-md-4"><div class="p-3 bg-light rounded"><h4>Products</h4><p class="display-6"><?=$counts['products']?></p><a class="btn btn-sm btn-primary" href="products.php">Manage</a></div></div>
      <div class="col-md-4"><div class="p-3 bg-light rounded"><h4>Orders</h4><p class="display-6"><?=$counts['orders']?></p><a class="btn btn-sm btn-primary" href="orders.php">Manage</a></div></div>
      <div class="col-md-4"><div class="p-3 bg-light rounded"><h4>Users</h4><p class="display-6"><?=$counts['users']?></p><a class="btn btn-sm btn-primary" href="users.php">Manage</a></div></div>
    </div>
  </div>
</div>
</body>
</html>
