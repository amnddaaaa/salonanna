<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

if (isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE reservasi SET status='$status' WHERE id=$id");
}

header("Location: dashboard.php");
exit;
?>
