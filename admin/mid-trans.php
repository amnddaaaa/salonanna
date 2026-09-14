<?php
require_once __DIR__ . '/../vendor/autoload.php';
require '../config/db.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'Mid-server-idyVejX2y5oRO_I6DnkrHXiv';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil id dari GET
if (!isset($_GET['id'])) {
    echo "<script>alert('ID booking tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$id = (int)$_GET['id'];

// Ambil data reservasi
$result = $conn->query("SELECT * FROM reservasi WHERE id = $id");
if (!$result || $result->num_rows == 0) {
    echo "<script>alert('Data reservasi tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$data = $result->fetch_assoc();

// Buat order_id yang unik
$orderIdMidtrans = 'ANNA-' . $id;

// Pastikan DP selalu 10.000
$dpAmount = 10000;

// Update order_id di database kalau belum diisi
if (empty($data['order_id'])) {
    $conn->query("UPDATE reservasi SET order_id = '$orderIdMidtrans', dp_amount = $dpAmount WHERE id = $id");
}

// Buat Snap Token
$params = array(
    'transaction_details' => array(
        'order_id' => $orderIdMidtrans,
        'gross_amount' => $dpAmount,
    ),
    'customer_details' => array(
        'first_name' => $data['nama'] ?? 'Guest',
        'email' => $data['email'] ?? 'email@contoh.com',
        'phone' => $data['phone'] ?? '08123456789',
    ),
);

$snapToken = \Midtrans\Snap::getSnapToken($params);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pembayaran QRIS - Anna Salon</title>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-wiggqv--uV_pYbsA"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-green-50 font-sans">
  <div class="max-w-md mx-auto mt-12 bg-white p-6 rounded shadow text-center">
    <h2 class="text-2xl font-bold text-green-700 mb-4">Konfirmasi Pembayaran DP</h2>
    <p class="mb-2">Silakan bayar DP sebesar:</p>
    <p class="text-3xl font-bold text-green-800 mb-6">Rp <?= number_format($dpAmount, 0, ',', '.') ?></p>

    <button id="pay-button" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded shadow mb-4">Bayar Sekarang</button>

    <form action="check-status.php" method="get" class="mt-4">
      <input type="hidden" name="order_id" value="<?= htmlspecialchars($orderIdMidtrans) ?>">
      <button type="submit" class="text-green-600 hover:underline text-sm">Cek Status Pembayaran</button>
    </form>
  </div>

  <script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
      snap.pay("<?= $snapToken ?>", {
        onSuccess: function(result){
          alert("Pembayaran sukses! Silakan cek status.");
        },
        onPending: function(result){
          alert("Menunggu pembayaran Anda. Silakan selesaikan QRIS.");
        },
        onError: function(result){
          alert("Terjadi kesalahan pembayaran. Silakan coba lagi.");
        }
      });
    });
  </script>
</body>
</html>
