<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $room_type_id = $_POST['room_type_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    mysqli_query($conn, "UPDATE rooms SET room_number='$room_number', room_type_id='$room_type_id', price='$price', description='$description', status='$status' WHERE id='$id'");

    header('Location: rooms.php');
    exit;
}

$room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rooms WHERE id='$id'"));
$room_types = mysqli_query($conn, "SELECT * FROM room_types");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<div class="flex">
  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-lg min-h-screen p-6">
    <h2 class="text-2xl font-extrabold text-indigo-600 mb-8">VistaLuxe</h2>
    <nav class="space-y-4 text-gray-700">
      <a href="dashboard.php" class="block hover:text-indigo-600 font-semibold">Dashboard</a>
      <a href="rooms.php" class="block hover:text-indigo-600 font-semibold">Manage Rooms</a>
      <a href="reservations.php" class="block hover:text-indigo-600">Manage Reservations</a>
      <a href="employees.php" class="block hover:text-indigo-600">Manage Employees</a>
      <a href="../logout.php" class="block text-red-500 hover:text-red-600">Logout</a>
    </nav>
  </aside>

  <!-- Edit Room Form -->
  <main class="flex-1 p-10">
    <div class="max-w-2xl mx-auto bg-white p-8 shadow-lg rounded-lg">
      <h2 class="text-3xl font-bold text-indigo-700 mb-6">Edit Room</h2>

      <form method="POST" class="space-y-5">
        <div>
          <label class="block text-gray-700 font-semibold mb-1">Room Number</label>
          <input type="text" name="room_number" value="<?= htmlspecialchars($room['room_number']) ?>" required class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Room Type</label>
          <select name="room_type_id" required class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-indigo-400">
            <?php while($type = mysqli_fetch_assoc($room_types)): ?>
              <option value="<?= $type['id'] ?>" <?= ($type['id'] == $room['room_type_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($type['name']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Price (€)</label>
          <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($room['price']) ?>" required class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-indigo-400">
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Description</label>
          <textarea name="description" rows="4" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-indigo-400"><?= htmlspecialchars($room['description']) ?></textarea>
        </div>

        <div>
          <label class="block text-gray-700 font-semibold mb-1">Status</label>
          <select name="status" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-indigo-400">
            <option value="available" <?= ($room['status'] == 'available') ? 'selected' : '' ?>>Available</option>
            <option value="booked" <?= ($room['status'] == 'booked') ? 'selected' : '' ?>>Booked</option>
          </select>
        </div>

        <div class="flex justify-between items-center mt-6">
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md shadow">Save Changes</button>
          <a href="rooms.php" class="text-gray-600 hover:underline">Back to Rooms</a>
        </div>
      </form>
    </div>
  </main>
</div>

</body>
</html>