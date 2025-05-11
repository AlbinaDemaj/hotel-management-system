<?php
include 'config.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        $success = "Registration successful! You can now log in.";
    } else {
        $error = "Registration error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-600 to-blue-400 min-h-screen flex items-center justify-center font-sans">

  <div class="bg-white w-full max-w-4xl shadow-xl rounded-lg overflow-hidden flex">
    <!-- Left Panel -->
    <div class="bg-blue-600 text-white w-1/2 p-10 flex flex-col justify-center">
      <div class="text-white text-2xl font-bold mb-4">VistaLuxe</div>
      <h2 class="text-4xl font-bold mb-4">Join Us Today!</h2>
      <p class="text-sm leading-relaxed">
        Create an account and enjoy the best hotel booking experience.
      </p>
    </div>

    <!-- Right Form Panel -->
    <div class="w-1/2 p-10">
      <h3 class="text-2xl font-bold text-gray-800 mb-6">Create an account</h3>

      <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
          <?= $success ?> <a href="login.php" class="underline">Log in now</a>
        </div>
      <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <input type="text" name="first_name" placeholder="First name" required class="px-4 py-2 border rounded">
          <input type="text" name="last_name" placeholder="Last name" required class="px-4 py-2 border rounded">
        </div>

        <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded">

        <div class="flex justify-between mt-4">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            Register
          </button>
          <a href="login.php" class="border border-blue-600 text-blue-600 px-6 py-2 rounded hover:bg-blue-100 transition">
            Login
          </a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
