<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="reservasi.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Nama', 'Email', 'Phone', 'Service', 'Treatments', 'Tanggal', 'Status']);

$result = $conn->query("SELECT * FROM reservasi ORDER BY tanggal DESC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['nama'],
        $row['email'],
        $row['phone'],
        $row['service'],
        $row['treatments'],
        $row['tanggal'],
        $row['status']
    ]);
}

fclose($output);
?>
