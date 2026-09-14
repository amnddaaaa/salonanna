<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $service = $conn->real_escape_string($_POST['service']);
    $treatments = $conn->real_escape_string($_POST['treatments']);
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $status = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE reservasi SET 
        nama='$nama',
        email='$email',
        phone='$phone',
        service='$service',
        treatments='$treatments',
        tanggal='$tanggal',
        status='$status'
        WHERE id=$id
    ");
}

header("Location: dashboard.php");
exit;
?>
