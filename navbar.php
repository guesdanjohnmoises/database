<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
  <a href="admin.php">Dashboard</a>
  <a href="products.php">Products</a>
  <a href="orders.php">Orders</a>
  <a href="users.php">Users</a>
  <div style="margin-left:auto;">
    <a href="changepassword.php" style="margin-right:10px;">Change Password</a>
    <a href="logout.php">Logout</a>
  </div>
</div>
