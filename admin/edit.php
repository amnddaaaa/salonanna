<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

require '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM reservasi WHERE id = $id");
$row = $res->fetch_assoc();

if (!$row) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Reservasi - Anna Salon</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-green-50 font-sans">

<!-- Navbar -->
<nav class="bg-white shadow-lg">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
    <div class="flex items-center space-x-2">
      <img src="../img/logo.png" alt="Logo" class="h-10 w-10 rounded-full" />
      <span class="text-xl font-semibold text-green-700">Anna Salon Admin</span>
    </div>
    <div class="flex items-center space-x-4">
      <span class="text-green-600">Hello, <?= htmlspecialchars($_SESSION["admin"]) ?></span>
      <a href="logout.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-400 transition">Logout</a>
    </div>
  </div>
</nav>

<section class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-lg">
  <h2 class="text-2xl font-bold text-green-700 mb-4">Edit Reservasi</h2>
  <form action="update.php" method="POST" class="space-y-4">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <div>
      <label class="block mb-1 text-green-700">Nama</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required class="w-full border px-3 py-2 rounded">
    </div>

    <div>
      <label class="block mb-1 text-green-700">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required class="w-full border px-3 py-2 rounded">
    </div>

    <div>
      <label class="block mb-1 text-green-700">Phone</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" required class="w-full border px-3 py-2 rounded">
    </div>

    <div>
      <label class="block mb-1 text-green-700">Service</label>
      <input type="text" name="service" value="<?= htmlspecialchars($row['service']) ?>" class="w-full border px-3 py-2 rounded">
    </div>

    <div>
      <label class="block mb-1 text-green-700">Treatments</label>
      <input type="text" name="treatments" value="<?= htmlspecialchars($row['treatments']) ?>" class="w-full border px-3 py-2 rounded">
    </div>

    <div>
      <label class="block mb-1 text-green-700">Tanggal</label>
      <input type="date" name="tanggal" value="<?= htmlspecialchars($row['tanggal']) ?>" required class="w-full border px-3 py-2 rounded">
    </div>

    <div>
      <label class="block mb-1 text-green-700">Status</label>
      <select name="status" class="w-full border px-3 py-2 rounded">
        <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
        <option <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
      </select>
    </div>

    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-400">Simpan Perubahan</button>
  </form>
</section>
</body>
</html>
