<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        header("Location: reset_password.php?email=" . urlencode($email));
        exit;
    } else {
        $error = "Email not found in the system.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-600 to-blue-400 min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white w-full max-w-2xl shadow-xl rounded-lg overflow-hidden flex">
    
    <!-- Left Panel -->
    <div class="bg-blue-600 text-white w-1/2 p-8 flex flex-col justify-center">
      <div class="text-white text-2xl font-bold mb-4">VistaLuxe</div>
      <h2 class="text-3xl font-bold mb-4">Forgot your password?</h2>
      <p class="text-sm leading-relaxed">
        Enter your registered email address and we’ll help you reset your password.
      </p>
    </div>

    <!-- Right Form Panel -->
    <div class="w-1/2 p-8">
      <h3 class="text-2xl font-bold text-gray-800 mb-6">Recover your account</h3>

      <?php if (isset($error)): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email address</label>
          <input type="email" name="email" placeholder="Enter your email" required
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
          Continue
        </button>

        <div class="text-center mt-4">
          <a href="login.php" class="text-sm text-blue-500 hover:underline">Back to login</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
