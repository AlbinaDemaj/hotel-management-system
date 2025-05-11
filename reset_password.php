<?php
include 'config.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$new_password' WHERE email='$email'");
    $success = "Password successfully changed! <a href='login.php' class='text-blue-500 underline'>Login now</a>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | Hotel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-600 to-blue-400 min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white w-full max-w-2xl shadow-xl rounded-lg overflow-hidden flex">
    
    <!-- Left Panel -->
    <div class="bg-blue-600 text-white w-1/2 p-8 flex flex-col justify-center">
      <div class="text-white text-2xl font-bold mb-4">VistaLuxe</div>
      <h2 class="text-3xl font-bold mb-4">Set a New Password</h2>
      <p class="text-sm leading-relaxed">
        Enter your new password below and you’ll be ready to log in to your account.
      </p>
    </div>

    <!-- Right Form Panel -->
    <div class="w-1/2 p-8">
      <h3 class="text-2xl font-bold text-gray-800 mb-6">Reset your password</h3>

      <?php if (isset($success)): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <input type="hidden" name="email" value="<?= $email ?>">

        <div>
          <label class="block text-sm font-medium text-gray-700">New Password</label>
          <input type="password" name="password" placeholder="Enter new password" required
                 class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:border-blue-400">
        </div>

        <button type="submit"
                class="w-full bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
          Change Password
        </button>

        <div class="text-center mt-4">
          <a href="login.php" class="text-sm text-blue-500 hover:underline">Back to login</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
