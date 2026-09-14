<?php  
require_once __DIR__ . '/../config/db.php';

$keyword = "";
$results = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $keyword = $conn->real_escape_string($_POST['keyword']);
  $sql = "SELECT * FROM reservasi 
          WHERE nama LIKE '%$keyword%' 
          OR email LIKE '%$keyword%' 
          OR phone LIKE '%$keyword%'
          ORDER BY tanggal DESC, jam DESC";
  $query = $conn->query($sql);

  if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
      $results[] = $row;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cek Riwayat Booking - Anna Salon</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      color: #2e4630;
      background: linear-gradient(135deg, #d7f8ef, #fef6fb);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* NAVBAR */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffffcc;
      backdrop-filter: blur(6px);
      padding: 1rem 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .navbar .logo {
      font-weight: 700;
      font-size: 1.3rem;
      color: #00a86b;
    }
    .navbar nav a {
      margin-left: 1.2rem;
      text-decoration: none;
      color: #444;
      font-weight: 500;
      transition: 0.3s;
    }
    .navbar nav a:hover,
    .navbar nav a.active {
      color: #00a86b;
    }
    .btn-book {
      background: #ff4081;
      padding: 0.6rem 1rem;
      border-radius: 8px;
      color: #fff !important;
      font-weight: 600;
    }

    h1 {
      text-align: center;
      font-size: 2.4rem;
      margin: 2rem 0 1.5rem;
      color: #006644;
    }

    /* SEARCH */
    .search-form {
      max-width: 650px;
      margin: 0 auto 2rem;
      background: #fff;
      border-radius: 20px;
      padding: 1.8rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      text-align: center;
    }
    .search-box {
      display: flex;
      align-items: center;
      border: 1px solid #cde9dc;
      border-radius: 12px;
      overflow: hidden;
    }
    .search-box input {
      flex: 1;
      padding: 0.9rem 1rem;
      border: none;
      outline: none;
      font-size: 1rem;
    }
    .search-box button {
      background: #00a86b;
      color: #fff;
      border: none;
      padding: 0.9rem 1.2rem;
      font-size: 1rem;
      cursor: pointer;
      transition: 0.3s;
    }
    .search-box button:hover {
      background: #008f5a;
    }

    /* RESULTS */
    .results {
      max-width: 1100px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
      gap: 1.8rem;
      padding: 0 1.2rem 3rem;
      flex: 1;
    }
    .booking-card {
      background: #fff;
      border-radius: 18px;
      padding: 1.6rem;
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
      border-top: 6px solid #00a86b;
      transition: transform 0.2s;
    }
    .booking-card:hover {
      transform: translateY(-5px);
    }
    .booking-card h3 {
      margin-bottom: 1rem;
      color: #00a86b;
      font-size: 1.2rem;
    }
    .booking-card p {
      margin: 0.35rem 0;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
    }
    .booking-card p strong {
      width: 140px;
      display: inline-block;
    }

    /* STATUS */
    .status-label {
      font-weight: bold;
      padding: 6px 14px;
      border-radius: 12px;
      font-size: 0.85rem;
      display: inline-block;
    }
    .pending { background: #fff3cd; color: #856404; }
    .on-process { background: #d1ecf1; color: #0c5460; }
    .success { background: #d4edda; color: #155724; }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 1.5rem;
      background: #ffffffdd;
      font-size: 0.9rem;
      color: #666;
      margin-top: auto;
    }
  </style>
</head>
<body>

  <main>
    <h1>🔍 Cek Riwayat Booking</h1>

    <div class="search-form">
      <form method="POST">
        <div class="search-box">
          <input type="text" name="keyword" placeholder="Masukkan Nama, Email, atau No. HP..." value="<?= htmlspecialchars($keyword) ?>" required>
          <button type="submit">Cari</button>
        </div>
      </form>
    </div>

    <div class="results">
      <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <?php if (count($results) > 0): ?>
          <?php foreach ($results as $row): ?>
            <div class="booking-card">
              <h3>💇 Booking Detail</h3>
              <p><strong>Nama:</strong> <?= htmlspecialchars($row['nama']) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
              <p><strong>Telepon:</strong> <?= htmlspecialchars($row['phone']) ?></p>
              <p><strong>Layanan:</strong> <?= htmlspecialchars($row['service']) ?></p>
              <p><strong>Treatment Tambahan:</strong> <?= htmlspecialchars($row['treatments']) ?></p>
              <p><strong>Tanggal & Jam:</strong> <?= htmlspecialchars($row['tanggal']) ?> - <?= htmlspecialchars($row['jam']) ?></p>
              <p><strong>DP:</strong> Rp <?= number_format($row['dp_amount'],0,",",".") ?></p>
              <p><strong>Status:</strong> 
                <span class="status-label <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="grid-column:1/-1; text-align:center; color:#c00; font-weight:600;">❌ Data tidak ditemukan.<br>Pastikan nama/email/no.hp benar.</p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </main>

  <footer>&copy; <?= date("Y") ?> Anna Salon</footer>

</body>
</html>
