<?php 
session_start();
require '../config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $new_password_raw = $_POST["new_password"];
    $pin = $conn->real_escape_string($_POST["pin_key"]);
    $new_password_hash = md5($new_password_raw);

    // cek apakah username + PIN cocok
    $check = $conn->query("SELECT * FROM admin_users WHERE username='$username' AND reset_pin='$pin' AND can_reset=1");

    if ($check && $check->num_rows > 0) {
        // Update password
        $result = $conn->query("UPDATE admin_users SET password='$new_password_hash' WHERE username='$username'");
        if ($result) {
            $message = "✅ Password berhasil direset untuk user '$username'.";
        } else {
            $message = "❌ Gagal mengupdate password.";
        }
    } else {
        $message = "⚠️ PIN salah atau akun tidak diizinkan reset password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reset Admin Password - Anna Salon</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-green-50 flex items-center justify-center h-screen relative">

  <!-- Back button di pojok kanan atas -->
  <a href="login.php" class="absolute top-4 right-4 text-green-600 hover:text-green-800 text-sm font-semibold">
    ⬅️ Back
  </a>

  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-green-600 text-center">Reset Admin Password</h2>
    <?php if ($message): ?>
      <div class="bg-blue-100 text-blue-700 p-2 mb-4 rounded"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
     <input type="text" name="username" value="admin" readonly
  class="w-full p-3 border rounded bg-gray-100 text-gray-600 cursor-not-allowed">
     
  <input type="password" name="new_password" placeholder="New Password" required class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-green-200">
      
   <input type="password" name="pin_key" placeholder="Masukkan PIN Rahasia" required
    class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-green-200">
    
  <button type="submit" class="w-full bg-green-400 text-white p-3 rounded hover:bg-green-500 transition">Reset Password</button>
    </form>
  </div>
</body>

</html>
