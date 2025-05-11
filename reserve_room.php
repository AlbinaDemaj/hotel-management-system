<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// query 
$where = "WHERE 1";

if (!empty($search)) {
    $where .= " AND room_number LIKE '%$search%'";
}

if (!empty($status)) {
    $where .= " AND status = '$status'";
}

$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM rooms $where"))['count'];
$total_pages = ceil($total_rooms / $limit);

$result = mysqli_query($conn, "SELECT * FROM rooms $where LIMIT $limit OFFSET $offset");

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reserve Room</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

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

  <div class="max-w-6xl mx-auto mt-12">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Reserve a Room</h1>

    <!-- Search and Filter Form -->
    <form method="GET" class="mb-6 flex flex-wrap justify-center gap-2">
      <input type="text" name="search" placeholder="Search by room number" value="<?= htmlspecialchars($search) ?>" class="border p-2 rounded w-1/3 min-w-[200px]">

      <select name="status" class="border p-2 rounded">
        <option value="">All Statuses</option>
        <option value="available" <?= $status === 'available' ? 'selected' : '' ?>>Available</option>
        <option value="booked" <?= $status === 'booked' ? 'selected' : '' ?>>Booked</option>
      </select>
      <button type="submit" class="bg-indigo-500 text-white px-4 rounded">Search</button>
    </form>

    

    <!-- Room Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <?php while ($room = mysqli_fetch_assoc($result)): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden <?= $room['status'] == 'booked' ? 'border-4 border-red-500' : ''; ?>">
          <img src="<?= htmlspecialchars($room['photo']) ?>" alt="Room Photo" class="w-full h-48 object-cover">
          <div class="p-4">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Room <?= htmlspecialchars($room['room_number']) ?></h2>
            <p class="text-gray-600 text-sm mb-2"><?= htmlspecialchars($room['description']) ?></p>
            <p class="text-indigo-600 font-bold mb-4">Price: $<?= $room['price'] ?>/night</p>
            <p class="text-red-600 font-semibold <?= $room['status'] == 'booked' ? '' : 'hidden' ?>">This room is booked</p>
            <form method="POST" action="confirm_reservation.php">
              <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
              <label class="block text-sm text-gray-700">Check-In</label>
              <input type="date" name="check_in" required class="border p-2 mb-2 w-full">
              <label class="block text-sm text-gray-700">Check-Out</label>
              <input type="date" name="check_out" required class="border p-2 mb-4 w-full">
              <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500 w-full">
                  Reserve
              </button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center space-x-2">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&page=<?= $i ?>"
           class="px-3 py-1 rounded border <?= $i === $page ? 'bg-indigo-500 text-white' : 'bg-white text-gray-800' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>

  <!-- Footer -->
<footer class="bg-gradient-to-tr from-blue-950 via-blue-900 to-blue-800 text-white mt-16">
  <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 md:grid-cols-4 gap-10">

    <!-- Logo -->
    <div>
      <h3 class="text-3xl font-bold mb-4">VistaLuxe</h3>
      <p class="text-sm text-gray-300 leading-relaxed">
        Experience comfort and luxury at every step. We warmly welcome you to our hotel.
      </p>
    </div>

    <!-- Useful Links -->
    <div>
      <h4 class="text-lg font-semibold mb-4">Useful Links</h4>
      <ul class="space-y-2 text-sm text-gray-300">
        <li><a href="#" class="hover:text-white transition">Home</a></li>
        <li><a href="#rooms" class="hover:text-white transition">Rooms</a></li>
        <li><a href="#about" class="hover:text-white transition">About</a></li>
        <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div>
      <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
      <ul class="space-y-2 text-sm text-gray-300">
        <li>📍 Dëshmorët St., Tirana</li>
        <li>📞 +355 69 123 4567</li>
        <li>✉️ info@vistaluxe.com</li>
      </ul>
    </div>

    <!-- Newsletter -->
    <div>
      <h4 class="text-lg font-semibold mb-4">Newsletter</h4>
      <p class="text-sm text-gray-300 mb-3">Subscribe to receive the latest offers and updates.</p>
      <form class="flex">
        <input type="email" placeholder="Your email"
               class="w-full p-2 rounded-l-md text-gray-800 focus:outline-none focus:ring focus:ring-blue-300">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white px-4 rounded-r-md transition">
          Subscribe
        </button>
      </form>
    </div>
  </div>

  <div class="text-center text-gray-400 py-4 border-t border-blue-700 text-sm">
    &copy; <?= date('Y') ?> VistaLuxe Hotel. All rights reserved.
  </div>
</footer>

</body>
</html>
