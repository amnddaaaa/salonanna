<?php
session_start();
require '../config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = md5($_POST['password']);


    $result = $conn->query("SELECT * FROM admin_users WHERE username='$username' AND password='$password'");
    if ($result && $result->num_rows > 0) {
        $_SESSION["admin"] = $username;
        $_SESSION["login_success"] = "Berhasil login!";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login - Anna Salon</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>
<!-- Back button di pojok kanan atas -->
  <a href="../index.html" class="absolute top-4 right-4 text-green-600 hover:text-green-800 text-sm font-semibold">
    ⬅️ Back
  </a>

<body class="bg-green-50 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
    <h2 class="text-2xl font-bold mb-6 text-green-600 text-center">Admin Login</h2>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="space-y-4">
      <input type="text" name="username" placeholder="Username" required class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-green-200">
      <input type="password" name="password" placeholder="Password" required class="w-full p-3 border rounded focus:outline-none focus:ring focus:ring-green-200">
      <button type="submit" class="w-full bg-green-400 text-white p-3 rounded hover:bg-green-500 transition">Login</button>
    </form>
    <div class="text-center mt-3">
  <a href="reset_admin_password.php" class="text-green-600 text-sm hover:text-green-700 hover:underline">
    🔒 Lupa password?
  </a>
</div>
  </div>
</body>
</html>
