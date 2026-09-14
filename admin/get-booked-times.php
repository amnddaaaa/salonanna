<?php
require 'config/db.php'; // sesuaikan path jika perlu

if (isset($_GET['tanggal'])) {
    $tanggal = $conn->real_escape_string($_GET['tanggal']);
    $result = $conn->query("SELECT jam FROM reservasi WHERE tanggal = '$tanggal'");
    
    $booked = [];
    while ($row = $result->fetch_assoc()) {
        $booked[] = $row['jam'];
    }

    header('Content-Type: application/json');
    echo json_encode($booked);
}
?>
