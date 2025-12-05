<?php
// redirect to login or admin if logged in
session_start();
if (!empty($_SESSION['user'])) {
    header("Location: admin.php");
    exit;
}
header("Location: login.php");
exit;
