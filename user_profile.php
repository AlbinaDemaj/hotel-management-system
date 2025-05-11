<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'] ?? null;
$first_name = $_SESSION['user']['first_name'] ?? '';
$last_name = $_SESSION['user']['last_name'] ?? '';
$name = $first_name . ' ' . $last_name;
$email = $_SESSION['user']['email'] ?? '';
$tab = $_GET['tab'] ?? 'profile';

$reservations = mysqli_query($conn, "SELECT r.*, rm.room_number, rm.photo, rm.price FROM reservations r JOIN rooms rm ON r.room_id = rm.id WHERE r.user_id = '$user_id' ORDER BY r.created_at DESC");

$reviews = mysqli_query($conn, "SELECT rv.*, rm.room_number, rm.photo FROM reviews rv JOIN rooms rm ON rv.room_id = rm.id WHERE rv.user_id = '$user_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile | VistaLuxe</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 to-white min-h-screen">

<!-- Navbar -->
<nav class="bg-white shadow-md py-4 sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
    <h1 class="text-3xl font-extrabold text-indigo-700 tracking-tight">VistaLuxe</h1>
    <ul class="flex space-x-6 text-gray-700 font-medium">
      <li><a href="index.php" class="hover:text-indigo-600 transition">Home</a></li>
      <li><a href="reserve_room.php" class="hover:text-indigo-600 transition">Rooms</a></li>
      <li><a href="#about" class="hover:text-indigo-600 transition">About</a></li>
      <li><a href="#contact" class="hover:text-indigo-600 transition">Contact</a></li>

      <?php if (isset($_SESSION['user'])): ?>
        <li><a href="my_reservations.php" class="hover:text-indigo-600 transition">My Reservations</a></li>
      <?php endif; ?>

      <?php if (isset($_SESSION['user'])): ?>
        <li><a href="user_profile.php" class="hover:text-indigo-600 transition">Profile</a></li>
        <li><a href="logout.php" class="hover:text-indigo-600 transition">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php" class="hover:text-indigo-600 transition">Login</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>

<!-- Main Layout -->
<div class="max-w-6xl mx-auto py-12 px-4">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

<!-- Sidebar -->
<div class="bg-white shadow-md rounded-xl p-6">
  <div class="flex items-center space-x-3 mb-6">
    <div class="w-12 h-12 bg-indigo-100 text-indigo-700 flex items-center justify-center rounded-full text-xl font-bold">
      <?= strtoupper($first_name[0] . $last_name[0]) ?>
    </div>
    <div>
      <h2 class="text-lg font-semibold">Hello,</h2>
      <p class="text-indigo-700 font-bold"><?= htmlspecialchars($name) ?></p>
    </div>
  </div>
  <ul class="space-y-3 text-sm font-medium">
    <li><a href="?tab=profile" class="flex items-center gap-2 <?= $tab === 'profile' ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600' ?>">👤 Profile Info</a></li>
    <li><a href="?tab=reservations" class="flex items-center gap-2 <?= $tab === 'reservations' ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600' ?>">📅 My Reservations</a></li>
    <li><a href="?tab=reviews" class="flex items-center gap-2 <?= $tab === 'reviews' ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600' ?>">📝 My Reviews</a></li>
    <li><a href="?tab=change_password" class="flex items-center gap-2 <?= $tab === 'change_password' ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600' ?>">🔒 Change Password</a></li>
  </ul>
</div>



    <!-- Content Section -->
<div class="md:col-span-3 bg-white shadow-md rounded-xl p-8">

<?php if ($tab === 'profile'): ?>
  <!-- Profile Content -->
  <h2 class="text-2xl font-bold text-gray-800 mb-6">👤 Profile Information</h2>
  <div class="space-y-2 text-gray-700 text-base">
    <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
  </div>
  <div class="mt-6 text-sm text-gray-500 italic">
    To update your profile, please contact our support team.
  </div>

<?php elseif ($tab === 'reservations'): ?>
  <!-- Reservations Content -->
  <h2 class="text-2xl font-bold text-gray-800 mb-6">📅 My Reservations</h2>
  <?php if (mysqli_num_rows($reservations) === 0): ?>
    <p class="text-gray-600">You have no reservations yet.</p>
  <?php else: ?>
    <div class="space-y-6">
      <?php while ($res = mysqli_fetch_assoc($reservations)):
        $check_in = new DateTime($res['check_in']);
        $check_out = new DateTime($res['check_out']);
        $nights = $check_out->diff($check_in)->days;
        $total = $nights * $res['price'];
      ?>
      <div class="p-5 bg-indigo-50 rounded-xl shadow hover:shadow-lg transition">
        <div class="flex gap-5">
          <img src="<?= $res['photo'] ?>" class="w-32 h-32 object-cover rounded-xl border">
          <div class="flex-1 space-y-1 text-gray-800">
            <h3 class="text-xl font-bold">Room <?= $res['room_number'] ?></h3>
            <p><strong>Check-in:</strong> <?= $res['check_in'] ?></p>
            <p><strong>Check-out:</strong> <?= $res['check_out'] ?></p>
            <p><strong>Status:</strong> <span class="capitalize"><?= $res['status'] ?></span></p>
            <p><strong>Total:</strong> <span class="text-green-600 font-semibold">$<?= $total ?></span></p>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>

<?php elseif ($tab === 'reviews'): ?>
  <!-- Reviews Content -->
  <h2 class="text-2xl font-bold text-gray-800 mb-6">📝 My Reviews</h2>
  <?php if (mysqli_num_rows($reviews) === 0): ?>
    <p class="text-gray-600">You haven’t written any reviews yet.</p>
  <?php else: ?>
    <div class="space-y-6">
      <?php while ($rev = mysqli_fetch_assoc($reviews)): ?>
      <div class="p-5 bg-white border-l-4 border-indigo-300 rounded-lg shadow-sm hover:shadow transition">
        <div class="flex gap-4">
          <img src="<?= $rev['photo'] ?>" class="w-20 h-20 object-cover rounded-md border">
          <div class="flex-1">
            <h4 class="font-semibold text-gray-800">Room <?= $rev['room_number'] ?></h4>
            <p class="italic text-gray-700 mt-1">"<?= htmlspecialchars($rev['comment']) ?>"</p>
            <p class="text-yellow-500 font-bold mt-1">⭐ <?= $rev['rating'] ?>/5</p>
            <p class="text-xs text-gray-400 mt-1"><?= date('d M Y', strtotime($rev['created_at'])) ?></p>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>

<?php elseif ($tab === 'change_password'): ?>
  <!-- Change Password Content -->
  <h2 class="text-2xl font-bold text-gray-800 mb-6">🔒 Change Password</h2>

  <?php if (isset($_SESSION['password_error'])): ?>
    <div class="mb-4 text-red-600 font-semibold"><?= $_SESSION['password_error'] ?></div>
    <?php unset($_SESSION['password_error']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['password_success'])): ?>
    <div class="mb-4 text-green-600 font-semibold"><?= $_SESSION['password_success'] ?></div>
    <?php unset($_SESSION['password_success']); ?>
  <?php endif; ?>

  <form method="POST" action="change_password.php" class="space-y-4">
    <div>
      <label class="block text-gray-700">Old Password</label>
      <input type="password" name="old_password" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-indigo-300">
    </div>
    <div>
      <label class="block text-gray-700">New Password</label>
      <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-indigo-300">
    </div>
    <div>
      <label class="block text-gray-700">Confirm New Password</label>
      <input type="password" name="confirm_password" required class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-indigo-300">
    </div>
    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
      Update Password
    </button>
  </form>
<?php endif; ?>

</div>


</body>
</html>
