<?php
require_once 'config.php';
session_start();
if (empty($_SESSION['user'])) header("Location: login.php");
$id = intval($_GET['id'] ?? 0);
$d = $conn->prepare("DELETE FROM products WHERE id=?");
$d->bind_param("i", $id);
$d->execute();
$d->close();
header("Location: products.php");
exit;
