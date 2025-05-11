<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$reservations = mysqli_query($conn, "SELECT r.*, rm.room_number, rm.photo FROM reservations r JOIN rooms rm ON r.room_id = rm.id WHERE r.user_id = '$user_id' ORDER BY r.created_at DESC");

$reviews = [];
$review_query = mysqli_query($conn, "SELECT * FROM reviews WHERE user_id = '$user_id'");
while ($row = mysqli_fetch_assoc($review_query)) {
    $reviews[$row['room_id']] = $row;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'], $_POST['comment'], $_POST['rating'])) {
    $room_id = $_POST['room_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $rating = (int) $_POST['rating'];


    if (!isset($reviews[$room_id])) {
        mysqli_query($conn, "INSERT INTO reviews (user_id, room_id, comment, rating, created_at) VALUES ('$user_id', '$room_id', '$comment', '$rating', NOW())");
        header("Location: my_reservations.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

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



<div class="max-w-3xl mx-auto py-10">
    <h1 class="text-3xl font-bold text-center text-brown-700 mb-10">My Reservations</h1>

    <?php while ($row = mysqli_fetch_assoc($reservations)): ?>
        <div class="bg-white shadow-lg rounded-xl p-6 mb-8">
            <div class="flex mb-4">
                <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Room Photo" class="w-32 h-32 object-cover rounded-lg mr-6">
                <div>
                    <p class="text-lg"><strong class="text-gray-800">Room Nr:</strong> <?= $row['room_number'] ?></p>
                    <p class="text-gray-700 mt-2">
                        <strong>Check-in:</strong> <?= $row['check_in'] ?> |
                        <strong>Check-out:</strong> <?= $row['check_out'] ?>
                    </p>
                    <p class="text-gray-700"><strong>Status:</strong> <?= ucfirst($row['status']) ?></p>
                </div>
            </div>

            <?php if (isset($reviews[$row['room_id']])): ?>
                <div class="mt-6 bg-green-100 p-4 rounded-lg">
                    <p class="font-semibold text-green-700">Your Review:</p>
                    <p class="italic text-gray-700">"<?= htmlspecialchars($reviews[$row['room_id']]['comment']) ?>"</p>
                    <p class="text-yellow-600 font-bold mt-1">⭐ <?= $reviews[$row['room_id']]['rating'] ?>/5</p>
                </div>
            <?php elseif ($row['status'] == 'confirmed' && strtotime($row['check_out']) < time()): ?>
                <form method="POST" class="mt-6">
                    <input type="hidden" name="room_id" value="<?= $row['room_id'] ?>">

                    <label class="block font-medium text-gray-800 mb-2">Leave a review:</label>
                    <textarea name="comment" required class="w-full border border-gray-300 p-3 rounded-lg mb-3" placeholder="Write your comment..."></textarea>

                    <select name="rating" required class="w-full border border-gray-300 p-3 rounded-lg mb-3">
                        <option value="">Choose rating</option>
                        <option value="1">⭐ 1</option>
                        <option value="2">⭐ 2</option>
                        <option value="3">⭐ 3</option>
                        <option value="4">⭐ 4</option>
                        <option value="5">⭐ 5</option>
                    </select>

                    <button type="submit" class="bg-brown-700 hover:bg-brown-800 text-indigo-700 font-semibold px-6 py-2 rounded-lg transition duration-200 shadow-md">
                        Submit Review
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>

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
