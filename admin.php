<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");

// counts
$p = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$o = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$u = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
  <h2>Dashboard</h2>
  <div style="display:flex; gap:12px; margin-top:14px;">
    <div style="flex:1; padding:12px; background:#f8f9fa; border-radius:8px;">Products<br><strong><?=intval($p)?></strong></div>
    <div style="flex:1; padding:12px; background:#f8f9fa; border-radius:8px;">Orders<br><strong><?=intval($o)?></strong></div>
    <div style="flex:1; padding:12px; background:#f8f9fa; border-radius:8px;">Users<br><strong><?=intval($u)?></strong></div>
  </div>
  <p style="margin-top:18px;">Welcome, <strong><?=htmlspecialchars($_SESSION['user'])?></strong></p>
</div>
</body></html>
