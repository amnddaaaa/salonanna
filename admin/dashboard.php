<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}
require '../config/db.php';

// Filter
$where = [];
if (!empty($_GET['tanggal'])) {
    $tanggal = $conn->real_escape_string($_GET['tanggal']);
    $where[] = "tanggal = '$tanggal'";
}
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where[] = "(nama LIKE '%$search%' OR email LIKE '%$search%')";
}
$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// Query Data
$result = $conn->query("SELECT * FROM reservasi $whereSQL ORDER BY tanggal DESC");

// Ringkasan
$total_all = $conn->query("SELECT COUNT(*) AS total FROM reservasi")->fetch_assoc()['total'];
$today = date('Y-m-d');
$total_today = $conn->query("SELECT COUNT(*) AS total FROM reservasi WHERE tanggal = '$today'")->fetch_assoc()['total'];
$month = date('Y-m');
$total_month = $conn->query("SELECT COUNT(*) AS total FROM reservasi WHERE DATE_FORMAT(tanggal, '%Y-%m') = '$month'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Anna Salon Cirebon</title>
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

<!-- Header -->
<header class="bg-gradient-to-r from-green-100 to-green-50 shadow p-6 text-center">
  <h1 class="text-3xl font-bold text-green-800 mb-2">Admin Dashboard</h1>
  <p class="text-green-600">Manage All Salon Reservations</p>
</header>

<!-- Stats -->
<section class="max-w-6xl mx-auto px-4 py-6 grid md:grid-cols-3 gap-6">
  <div class="bg-white shadow rounded-lg p-6 text-center">
    <h2 class="text-green-700 text-lg font-semibold">Reservasi Hari Ini</h2>
    <p class="text-3xl font-bold text-green-900"><?= $total_today ?></p>
  </div>
  <div class="bg-white shadow rounded-lg p-6 text-center">
    <h2 class="text-green-700 text-lg font-semibold">Reservasi Bulan Ini</h2>
    <p class="text-3xl font-bold text-green-900"><?= $total_month ?></p>
  </div>
  <div class="bg-white shadow rounded-lg p-6 text-center">
    <h2 class="text-green-700 text-lg font-semibold">Total Reservasi</h2>
    <p class="text-3xl font-bold text-green-900"><?= $total_all ?></p>
  </div>
</section>

<!-- Filter + Export -->
<section class="max-w-6xl mx-auto px-4 mb-6">
  <form method="GET" class="flex flex-wrap gap-2 mb-4">
    <input type="date" name="tanggal" value="<?= htmlspecialchars($_GET['tanggal'] ?? '') ?>" class="border px-3 py-2 rounded w-full md:w-auto">
    <input type="text" name="search" placeholder="Cari nama/email" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" class="border px-3 py-2 rounded w-full md:w-1/3">
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-400">Filter</button>
    <a href="dashboard.php" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-200">Reset</a>
    <a href="export.php" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-400 ml-auto">Export CSV</a>
  </form>
</section>

<!-- Table -->
<section class="max-w-6xl mx-auto px-4">
  <div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-green-200">
      <thead class="bg-green-100 text-green-700">
        <tr>
          <th class="px-4 py-2 text-left">ID</th>
          <th class="px-4 py-2 text-left">Nama</th>
          <th class="px-4 py-2 text-left">Email</th>
          <th class="px-4 py-2 text-left">Phone</th>
          <th class="px-4 py-2 text-left">Service</th>
          <th class="px-4 py-2 text-left">Treatments</th>
          <th class="px-4 py-2 text-left">Tanggal</th>
          <th class="px-4 py-2 text-left">Status</th>
          <th class="px-4 py-2 text-left">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-green-100">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-green-50">
              <td class="px-4 py-2"><?= $row['id'] ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['phone']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['service']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['treatments']) ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['tanggal']) ?></td>
              <td class="px-4 py-2">
                <form action="update_status.php" method="POST" class="flex items-center gap-2">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <select name="status" class="border rounded px-2 py-1">
                    <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                  </select>
                  <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-400">✔</button>
                </form>
              </td>
              <td class="px-4 py-2">
                <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500 hover:underline">Edit</a> |
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')" class="text-red-500 hover:underline">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" class="px-4 py-4 text-center text-gray-500">Belum ada reservasi.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Footer -->
<footer class="mt-10 bg-green-100 text-center py-4 text-green-500 text-sm">
  &copy; 2025 Anna Salon. All rights reserved.
</footer>
</body>
</html>
