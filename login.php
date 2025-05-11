<?php
include 'config.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
      $_SESSION['user'] = $user;

      // 
      if (isset($user['role']) && $user['role'] === 'admin') {
        header('Location: admin/dashboard.php');
      } else {
        header('Location: index.php');
      }
      exit;
    } else {
      $error = "Incorrect password!";
    }
  } else {
    $error = "Email does not exist!";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-600 to-blue-400 min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white w-full max-w-4xl shadow-xl rounded-lg overflow-hidden flex">
    
    <!-- Left Panel -->
    <div class="bg-blue-600 text-white w-1/2 p-10 flex flex-col justify-center">
      <div class="text-white text-2xl font-bold mb-4">VistaLuxe</div>
      <h2 class="text-4xl font-bold mb-4">Hello, welcome!</h2>
      <p class="text-sm leading-relaxed">
        Welcome to our platform. Please log in to continue with your bookings.
      </p>
    </div>

    <!-- Right Form Panel -->
    <div class="w-1/2 p-10">
      <h3 class="text-2xl font-bold text-gray-800 mb-6">Sign in to your account</h3>

      <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email address</label>
          <input type="email" name="email" required
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" required
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
        </div>

        <div class="flex items-center justify-between text-sm text-gray-600">
          <label class="flex items-center space-x-2">
            <input type="checkbox" class="form-checkbox">
            <span>Remember me</span>
          </label>
          <a href="forgot_password.php" class="text-sm text-blue-500 hover:underline">Forgot password?</a>
        </div>

        <div class="flex items-center justify-between mt-4">
          <button type="submit"
                  class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            Login
          </button>
          <a href="register.php" class="border border-blue-600 text-blue-600 px-6 py-2 rounded hover:bg-blue-100 transition">
            Sign up
          </a>
        </div>

        <div class="text-center mt-6 text-gray-500 text-sm">
          FOLLOW:
          <span class="ml-2 text-blue-700">🐦</span>
          <span class="ml-2 text-blue-700">📘</span>
          <span class="ml-2 text-blue-700">📸</span>
        </div>
      </form>
    </div>
  </div>

</body>
</html>