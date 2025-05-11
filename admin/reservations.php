<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$status_filter_value = isset($_GET['status']) ? $_GET['status'] : '';
$status_filter = ($status_filter_value !== '') 
  ? "AND reservations.status = '" . mysqli_real_escape_string($conn, $status_filter_value) . "'" 
  : '';

$reservations = mysqli_query($conn, "
  SELECT reservations.*, rooms.room_number, users.first_name, users.last_name 
  FROM reservations
  JOIN rooms ON reservations.room_id = rooms.id
  JOIN users ON reservations.user_id = users.id
  WHERE 1=1 $status_filter
  ORDER BY reservations.created_at DESC
");

$total_reservations = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reservations"));
$total_confirmed = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reservations WHERE status = 'confirmed'"));
$total_cancelled = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM reservations WHERE status = 'cancelled'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Reservations | Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">

<!-- Sidebar -->
<div class="flex">
  <aside class="w-64 bg-white shadow-lg min-h-screen p-6">
    <h2 class="text-2xl font-extrabold text-indigo-600 mb-8">VistaLuxe</h2>
    <nav class="space-y-4 text-gray-700">
      <a href="dashboard.php" class="block hover:text-indigo-600 font-semibold">Dashboard</a>
      <a href="rooms.php" class="block hover:text-indigo-600">Manage Rooms</a>
      <a href="reservations.php" class="block hover:text-indigo-600">Manage Reservations</a>
      <a href="employees.php" class="block hover:text-indigo-600">Manage Employees</a>
      <a href="../logout.php" class="block text-red-500 hover:text-red-600">Logout</a>
    </nav>
  </aside>

  <div class="flex-1 p-10">
    <div class="flex justify-between items-center mb-10">
      <h2 class="text-3xl font-bold text-gray-800">Reservation Management</h2>
      <a href="dashboard.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow">Back to Dashboard</a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6 text-center">
        <h3 class="text-xl font-bold text-indigo-700">Total Reservations</h3>
        <p class="text-2xl font-semibold text-gray-800"><?php echo $total_reservations; ?></p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 text-center">
        <h3 class="text-xl font-bold text-green-600">Confirmed</h3>
        <p class="text-2xl font-semibold text-gray-800"><?php echo $total_confirmed; ?></p>
      </div>
      <div class="bg-white rounded-lg shadow p-6 text-center">
        <h3 class="text-xl font-bold text-red-500">Cancelled</h3>
        <p class="text-2xl font-semibold text-gray-800"><?php echo $total_cancelled; ?></p>
      </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="mb-6">
      <label for="status" class="mr-2 font-semibold">Filter by status:</label>
      <select name="status" id="status" onchange="this.form.submit()" class="border rounded px-3 py-2">
        <option value="" <?= $status_filter_value === '' ? 'selected' : '' ?>>All</option>
        <option value="pending" <?= $status_filter_value === 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="confirmed" <?= $status_filter_value === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
        <option value="cancelled" <?= $status_filter_value === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
      </select>
    </form>

    <!-- Reservations Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-indigo-100 text-indigo-800 uppercase text-xs">
          <tr>
            <th class="px-6 py-3">Room #</th>
            <th class="px-6 py-3">Client</th>
            <th class="px-6 py-3">Check-In</th>
            <th class="px-6 py-3">Check-Out</th>
            <th class="px-6 py-3">Total Price (€)</th>
            <th class="px-6 py-3">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php while($res = mysqli_fetch_assoc($reservations)): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4"><?= htmlspecialchars($res['room_number']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($res['first_name'] . ' ' . $res['last_name']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($res['check_in']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($res['check_out']) ?></td>
              <td class="px-6 py-4"><?= htmlspecialchars($res['total_price']) ?> €</td>
              <td class="px-6 py-4">
                <?php
                  $status = strtolower($res['status']);
                  $color = match($status) {
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'confirmed' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-600'
                  };
                ?>
                <span class="px-3 py-1 rounded-full text-sm font-medium <?= $color ?>">
                  <?= ucfirst($status) ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
