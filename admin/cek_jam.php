<?php
require '../config/db.php';

if (isset($_GET['tanggal'])) {
    $tanggal = $conn->real_escape_string($_GET['tanggal']);
    $result = $conn->query("SELECT jam FROM reservasi WHERE tanggal = '$tanggal'");
    
    $jam_terpakai = [];
    while ($row = $result->fetch_assoc()) {
        $jam_terpakai[] = $row['jam'];
    }

    echo json_encode($jam_terpakai);
}
?>
