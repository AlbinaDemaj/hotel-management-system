<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
$user = $_SESSION['user'];
$first_name = isset($user['first_name']) ? $user['first_name'] : 'Admin';
$last_name = isset($user['last_name']) ? $user['last_name'] : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile | VistaLuxe</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-8">

  <div class="max-w-3xl mx-auto bg-white shadow-md rounded-xl p-8">
    <div class="flex items-center space-x-6">
      <img src="https://ui-avatars.com/api/?name=<?= urlencode($first_name . ' ' . $last_name) ?>" 
           alt="Avatar" class="w-20 h-20 rounded-full border-2 border-indigo-500 shadow">
      <div>
        <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($first_name . ' ' . $last_name) ?></h2>
        <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
        <span class="mt-1 inline-block px-2 py-1 text-xs font-medium bg-indigo-100 text-indigo-700 rounded">Admin</span>
      </div>
    </div>

    <div class="mt-8">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">Change Password</h3>
      <form action="#" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600">Current Password</label>
          <input type="password" name="current_password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">New Password</label>
          <input type="password" name="new_password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600">Confirm New Password</label>
          <input type="password" name="confirm_password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring">
        </div>
        <button type="submit" disabled class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
          Save Changes (coming soon)
        </button>
      </form>
    </div>

    <div class="mt-6">
      <a href="dashboard.php" class="text-blue-600 hover:underline text-sm">← Back to Dashboard</a>
    </div>
  </div>

</body>
</html>
