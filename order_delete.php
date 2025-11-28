<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$id = intval($_GET['id'] ?? 0);
$o = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
if ($o) {
    // restore stock
    $conn->query("UPDATE products SET stock = stock + ".intval($o['quantity'])." WHERE id = ".intval($o['product_id']));
    $d = $conn->prepare("DELETE FROM orders WHERE id=?"); $d->bind_param('i',$id); $d->execute(); $d->close();
}
header('Location: orders.php'); exit;
