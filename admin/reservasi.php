<?php
require '../config/db.php';

// ✅ Bagian untuk AJAX: cek jam tersedia berdasarkan tanggal
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['tanggal'])) {
    $tanggal = $conn->real_escape_string($_GET['tanggal']);

    // Semua jam kerja default (misal 09:00 - 17:00)
    $all_jam = [
        "09:00", "10:00", "11:00", "12:00",
        "13:00", "14:00", "15:00", "16:00", "17:00"
    ];

    // Ambil jam yang sudah dibooking
    $result = $conn->query("SELECT jam FROM reservasi WHERE tanggal = '$tanggal'");
    $booked = [];
    while ($row = $result->fetch_assoc()) {
        $booked[] = $row['jam'];
    }

    // Filter jam yang masih tersedia
    $available = array_diff($all_jam, $booked);

    header('Content-Type: application/json');
    echo json_encode(array_values($available));
    exit;
}

// ✅ Bagian untuk booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $service = $conn->real_escape_string($_POST['service']);
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $jam = $conn->real_escape_string($_POST['jam']);
    $treatments = isset($_POST['treatment']) ? implode(", ", $_POST['treatment']) : "";

    // Tambahkan sistem pembayaran DP (contoh DP tetap Rp 10.000)
    $dp_amount = 10000;

    // ✅ Cek apakah tanggal + jam sudah dibooking
    $cek = $conn->query("SELECT * FROM reservasi WHERE tanggal = '$tanggal' AND jam = '$jam'");
    if ($cek->num_rows > 0) {
        // Jika tanggal & jam sudah ada, tampilkan pesan gagal
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Jam Sudah Dipesan</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
        </head>
        <body class="bg-yellow-50 font-sans">
            <div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-bold text-yellow-600 mb-6">Jam Tidak Tersedia</h2>
                <p class="text-yellow-700 mb-4">Maaf, pada tanggal <b><?= htmlspecialchars($tanggal) ?></b> jam <b><?= htmlspecialchars($jam) ?></b> sudah dipesan. Silakan pilih jam lain.</p>
                <a href="../reservasi.html" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500 transition">Kembali ke Form Booking</a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    // Jika belum ada booking di tanggal & jam tsb, baru simpan
    $sql = "INSERT INTO reservasi (nama, email, phone, service, treatments, tanggal, jam, dp_amount) 
            VALUES ('$nama', '$email', '$phone', '$service', '$treatments', '$tanggal', '$jam', '$dp_amount')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        header("Location: mid-trans.php?id=$last_id");
        exit;
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Error Booking</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
        </head>
        <body class="bg-red-50 font-sans">
            <div class="max-w-lg mx-auto mt-10 bg-white p-8 rounded-lg shadow-lg text-center">
                <h2 class="text-2xl font-bold text-red-600 mb-6">Gagal melakukan booking.</h2>
                <p class="text-red-700 mb-4">Error: <?= htmlspecialchars($conn->error) ?></p>
                <a href="../reservasi.html" class="bg-red-400 text-white px-4 py-2 rounded hover:bg-red-500 transition">Kembali ke Form Booking</a>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    header("Location: ../reservasi.html");
    exit;
}
?>
