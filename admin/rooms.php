<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Handle Add Room POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $room_type_id = $_POST['room_type_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $photo = $_POST['photo'];

    mysqli_query($conn, "INSERT INTO rooms (room_number, room_type_id, price, description, status, photo) 
        VALUES ('$room_number', '$room_type_id', '$price', '$description', '$status', '$photo')");

    header("Location: rooms.php");
    exit;
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_results = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM rooms WHERE room_number LIKE '%$search%'"));
$total_pages = ceil($total_results['total'] / $limit);

$rooms = mysqli_query($conn, "
    SELECT rooms.*, room_types.name AS room_type 
    FROM rooms 
    LEFT JOIN room_types ON rooms.room_type_id = room_types.id 
    WHERE room_number LIKE '%$search%' 
    LIMIT $limit OFFSET $offset
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Rooms | Admin</title>
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

  <!-- Main Content -->
  <main class="flex-1 p-10">
    <div class="flex items-center justify-between mb-8">
      <h2 class="text-3xl font-bold text-gray-800">Room Management</h2>
      <div class="space-x-2">
        <button onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">
          + Add Room
        </button>
        <a href="dashboard.php" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md shadow">Back to Dashboard</a>
      </div>
    </div>

    <!-- Search -->
    <form method="GET" class="mb-6">
      <div class="flex shadow rounded-md overflow-hidden">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by room number"
               class="w-full px-4 py-2 border-none focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2">Search</button>
      </div>
    </form>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
      <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-lg relative">
        <h3 class="text-xl font-semibold text-indigo-600 text-center mb-4">Add New Room</h3>
        <form method="POST" action="" class="space-y-4">
          <input type="hidden" name="add_room" value="1">

          <input type="text" name="room_number" placeholder="Room Number" required class="w-full border rounded p-2">

          <select name="room_type_id" required class="w-full border rounded p-2">
            <option value="">Select Room Type</option>
            <?php
            $room_types = mysqli_query($conn, "SELECT * FROM room_types");
            while ($type = mysqli_fetch_assoc($room_types)) {
                echo "<option value='{$type['id']}'>" . htmlspecialchars($type['name']) . "</option>";
            }
            ?>
          </select>

          <input type="text" name="photo" placeholder="Photo URL" required class="w-full border rounded p-2">
          <input type="number" name="price" step="0.01" placeholder="Price (€)" required class="w-full border rounded p-2">
          <textarea name="description" rows="3" placeholder="Description" class="w-full border rounded p-2"></textarea>

          <select name="status" class="w-full border rounded p-2">
            <option value="available">Available</option>
            <option value="booked">Booked</option>
          </select>

          <div class="flex justify-between items-center pt-4">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">Save</button>
            <button type="button" onclick="document.getElementById('addRoomModal').classList.add('hidden')" class="text-red-500 hover:underline">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Rooms Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="w-full text-sm text-left">
        <thead class="bg-indigo-100 text-indigo-800 uppercase text-xs">
          <tr>
            <th class="px-6 py-3">Room #</th>
            <th class="px-6 py-3">Type</th>
            <th class="px-6 py-3">Price (€)</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php while($room = mysqli_fetch_assoc($rooms)): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-800"><?= htmlspecialchars($room['room_number']) ?></td>
            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($room['room_type']) ?></td>
            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($room['price']) ?>€</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 rounded-full text-sm font-medium
                <?= $room['status'] === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= ucfirst($room['status']) ?>
              </span>
            </td>
            <td class="px-6 py-4 space-x-2">
              <a href="edit_room.php?id=<?= $room['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
              <a href="delete_room.php?id=<?= $room['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center space-x-2">
      <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
           class="px-4 py-2 rounded-md text-sm font-medium
           <?= ($page == $i) ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700 hover:bg-gray-100' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </main>
</div>

</body>
</html>
