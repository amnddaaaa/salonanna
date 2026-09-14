<?php
$host = "localhost";
$user = "root";     // default user XAMPP
$pass = "";         // biasanya kosong kalau belum diubah
$db   = "salon_db"; // sesuai database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
