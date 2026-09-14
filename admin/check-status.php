<?php
require_once __DIR__ . '/../vendor/autoload.php';



// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'Mid-server-idyVejX2y5oRO_I6DnkrHXiv';
\Midtrans\Config::$isProduction = false;

$order_id = $_GET['order_id'] ?? '';

if (!$order_id) {
    die('Order ID tidak ditemukan.');
}

try {
    $status = \Midtrans\Transaction::status($order_id);
    $transaction_status = $status->transaction_status;
} catch (Exception $e) {
    die("Gagal mengambil status: " . $e->getMessage());
}

// Penjelasan status
if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
    $status_message = "✅ Pembayaran LUNAS. Terima kasih!";
} elseif ($transaction_status == 'pending') {
    $status_message = "⏳ Menunggu pembayaran. Silakan selesaikan di QRIS.";
} else {
    $status_message = "❌ Status transaksi: " . htmlspecialchars($transaction_status);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Status Pembayaran</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <meta http-equiv="refresh" content="10"> <!-- refresh tiap 10 detik -->
</head>
<body class="bg-gray-100 font-sans">
  <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded shadow text-center">
    <h2 class="text-xl font-bold text-green-700 mb-4">Cek Status Pembayaran</h2>
    <p class="text-lg mb-6"><?= $status_message ?></p>

    <p class="text-sm text-gray-500 mb-2">Order ID: <?= htmlspecialchars($order_id) ?></p>

    <?php if ($transaction_status != 'settlement' && $transaction_status != 'capture'): ?>
      <a href="check-status.php?order_id=<?= urlencode($order_id) ?>" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Cek Lagi</a>
    <?php else: ?>
      <a href="bukti.php?order_id=<?= urlencode($order_id) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Lihat Bukti</a>
    <?php endif; ?>

    <div class="mt-4">
      <a href="mid-trans.php" class="text-blue-500 underline text-sm">Kembali ke QRIS</a>
    </div>
  </div>
</body>
</html>
