<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = $_POST['room_number'];
    $room_type_id = $_POST['room_type_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $photo = $_POST['photo'];

    mysqli_query($conn, "INSERT INTO rooms (room_number, room_type_id, price, description, status, photo) VALUES ('$room_number', '$room_type_id', '$price', '$description', '$status', '$photo')");

    header('Location: rooms.php');
    exit;
}

$room_types = mysqli_query($conn, "SELECT * FROM room_types");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-lg p-6">
    <h2 class="text-2xl font-extrabold text-indigo-600 mb-8">VistaLuxe</h2>
    <nav class="space-y-4 text-gray-700">
      <a href="dashboard.php" class="block hover:text-indigo-600 font-semibold">Dashboard</a>
      <a href="rooms.php" class="block hover:text-indigo-600">Manage Rooms</a>
      <a href="reservations.php" class="block hover:text-indigo-600">Manage Reservations</a>
      <a href="employees.php" class="block hover:text-indigo-600 font-semibold">Manage Employees</a>
      <a href="../logout.php" class="block text-red-500 hover:text-red-600">Logout</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="flex flex-col justify-center items-center flex-1 p-10">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add New Room</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                <input type="text" name="room_number" required class="border border-gray-300 p-2 rounded w-full" placeholder="Enter room number">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                <select name="room_type_id" required class="border border-gray-300 p-2 rounded w-full">
                    <option value="">Select room type</option>
                    <?php while($type = mysqli_fetch_assoc($room_types)): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                <input type="text" name="photo" required class="border border-gray-300 p-2 rounded w-full" placeholder="https://example.com/photo.jpg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price per Night ($)</label>
                <input type="number" step="0.01" name="price" required class="border border-gray-300 p-2 rounded w-full" placeholder="Enter price">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="border border-gray-300 p-2 rounded w-full" placeholder="Write a short description..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="border border-gray-300 p-2 rounded w-full">
                    <option value="available">Available</option>
                    <option value="booked">Booked</option>
                </select>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-semibold py-2 px-4 rounded">
                    Save Room
                </button>
                <a href="rooms.php" class="text-gray-600 hover:underline">← Back to Room List</a>
            </div>
        </form>
    </div>
  </div>
</div>

</body>
</html>
