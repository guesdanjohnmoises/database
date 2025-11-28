<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT image FROM products WHERE id=?"); $stmt->bind_param('i',$id); $stmt->execute(); $row = $stmt->get_result()->fetch_assoc(); $stmt->close();
if ($row && $row['image'] && file_exists('uploads/'.$row['image'])) unlink('uploads/'.$row['image']);
$d = $conn->prepare("DELETE FROM products WHERE id=?"); $d->bind_param('i',$id); $d->execute(); $d->close();
header('Location: products.php'); exit;
