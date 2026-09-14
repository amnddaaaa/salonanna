<?php
require '../config/db.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = (int) $_GET['id'];
$result = $conn->query("SELECT * FROM reservasi WHERE id = $id");
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bukti Pembayaran</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-green-50 font-sans">
  <div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg text-center">
    <h2 class="text-2xl font-bold text-green-600 mb-6">Bukti Reservasi & Pembayaran</h2>
    <p class="mb-2">Nama: <strong><?= htmlspecialchars($data['nama']) ?></strong></p>
    <p class="mb-2">Email: <strong><?= htmlspecialchars($data['email']) ?></strong></p>
    <p class="mb-2">Phone: <strong><?= htmlspecialchars($data['phone']) ?></strong></p>
    <p class="mb-2">Service: <strong><?= htmlspecialchars($data['service']) ?></strong></p>
    <p class="mb-2">Treatments: <strong><?= htmlspecialchars($data['treatments']) ?></strong></p>
    <p class="mb-2">Tanggal: <strong><?= htmlspecialchars($data['tanggal']) ?></strong></p>
    <p class="mb-2">Jam: <strong><?= htmlspecialchars($data['jam']) ?></strong></p>
    <p class="mb-2">Status Pembayaran: <strong><?= htmlspecialchars($data['payment_status']) ?></strong></p>
    <a href="dashboard.php" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-400 transition">Kembali ke Dashboard</a>
  </div>
</body>
</html>
