
<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users"))['total'];
$total_reservations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reservations"))['total'];
$total_reviews = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reviews"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM reservations WHERE status = 'confirmed'"))['total'] ?? 0;

$monthlyData = mysqli_query($conn, "
    SELECT MONTH(created_at) as month, COUNT(*) as total 
    FROM reservations 
    GROUP BY MONTH(created_at)
");
$months = [];
$reservations = [];
while ($row = mysqli_fetch_assoc($monthlyData)) {
    $months[] = date("F", mktime(0, 0, 0, $row['month'], 1));
    $reservations[] = $row['total'];
}

$latest_res = mysqli_query($conn, "
    SELECT r.*, u.first_name, u.last_name FROM reservations r
    JOIN users u ON u.id = r.user_id
    ORDER BY r.created_at DESC LIMIT 5
");

// Review-t 
$latest_reviews = mysqli_query($conn, "
    SELECT r.comment, r.rating, r.created_at, u.first_name, u.last_name 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    ORDER BY r.created_at DESC LIMIT 4
");

// Revenue per month
$monthly_revenue = mysqli_query($conn, "
  SELECT MONTH(created_at) as month, SUM(total_price) as revenue 
  FROM reservations 
  WHERE status = 'confirmed'
  GROUP BY MONTH(created_at)
");

$messages = mysqli_query($conn, "
  SELECT * FROM contacts 
  ORDER BY created_at DESC 
  LIMIT 5
");

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$upcoming_checkouts = mysqli_query($conn, "
  SELECT reservations.*, users.first_name, users.last_name, rooms.room_number
  FROM reservations
  JOIN users ON users.id = reservations.user_id
  JOIN rooms ON rooms.id = reservations.room_id
  WHERE check_out = '$today' OR check_out = '$tomorrow'
  ORDER BY check_out ASC
");

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $reservation_id = $_GET['id'];
    $status = '';

    if ($action == 'confirm') {
        $status = 'confirmed';
    } elseif ($action == 'cancel') {
        $status = 'cancelled';
    }

    
    $update_query = "UPDATE reservations SET status='$status' WHERE id='$reservation_id'";
    if (mysqli_query($conn, $update_query)) {
      
        $reservation = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM reservations WHERE id='$reservation_id'"));
        $user_id = $reservation['user_id'];
        $user_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));
        $subject = "Rezervimi juaj është " . ucfirst($status);
        $message = "Përshëndetje " . $user_info['first_name'] . ",\n\n";

        if ($status == 'confirmed') {
            $message .= "Rezervimi juaj është konfirmuar. Ju lutemi kontrolloni detajet e rezervimit.\n\n";
        } else {
            $message .= "Rezervimi juaj është anuluar. Ju lutemi provoni përsëri më vonë.\n\n";
        }

        mail($user_info['email'], $subject, $message);

        header('Location: dashboard.php');
        exit;
    } else {
        echo "Ka ndodhur një gabim gjatë përditësimit të statusit.";
    }
}

$rev_labels = [];
$rev_data = [];
while ($row = mysqli_fetch_assoc($monthly_revenue)) {
    $rev_labels[] = date("F", mktime(0, 0, 0, $row['month'], 1));
    $rev_data[] = $row['revenue'];
}


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

// Rezervimet prr 7 ditet e ardhshme
$upcoming_reservations = mysqli_query($conn, "
SELECT reservations.*, users.first_name, users.last_name, rooms.room_number 
FROM reservations
JOIN users ON users.id = reservations.user_id
JOIN rooms ON rooms.id = reservations.room_id
WHERE check_in BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
ORDER BY check_in ASC
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | VistaLuxe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<!-- Sidebar -->
<div class="flex">
  <aside class="w-64 bg-white shadow-lg min-h-screen p-6">
    <h2 class="text-2xl font-extrabold text-indigo-600 mb-8">VistaLuxe</h2>
    <nav class="space-y-4 text-gray-700">
      <a href="dashboard.php" class="block hover:text-indigo-600 font-semibold">Dashboard</a>
      <a href="rooms.php" class="block hover:text-indigo-600">Manage Rooms</a>
      <a href="reservations.php" class="block hover:text-indigo-600">Manage Reservations</a>
      <a href="employees.php" class="block hover:text-indigo-600">
  Manage Employees
</a>

      <a href="../logout.php" class="block text-red-500 hover:text-red-600">Logout</a>
    </nav>
  </aside>


  
  <main class="flex-1 p-10">

   <!-- Top Header with Admin Profile (Dropdown via JS) -->
<div class="flex items-center justify-between mb-10 relative">
  <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>

  <!-- Profile Dropdown -->
  <div class="relative inline-block text-left">
    <button onclick="toggleDropdown()" class="flex items-center space-x-3 focus:outline-none">
      <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['first_name']) ?>" 
           class="w-10 h-10 rounded-full border-2 border-indigo-500 shadow-sm" alt="Avatar">
      <span class="text-gray-700 font-medium"><?= htmlspecialchars($_SESSION['user']['first_name']) ?></span>
      <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
      </svg>
    </button>

    <!-- Dropdown Menu -->
    <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-md shadow-lg z-10">
      <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
      <a href="../logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
    </div>
  </div>
</div>

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
      <div class="bg-white p-6 rounded-xl shadow border-l-4 border-indigo-500">
        <h4 class="text-sm text-gray-600">Total Users</h4>
        <p class="text-2xl font-bold"><?= $total_users ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500">
        <h4 class="text-sm text-gray-600">Reservations</h4>
        <p class="text-2xl font-bold"><?= $total_reservations ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
        <h4 class="text-sm text-gray-600">Reviews</h4>
        <p class="text-2xl font-bold"><?= $total_reviews ?></p>
      </div>
      <div class="bg-white p-6 rounded-xl shadow border-l-4 border-yellow-500">
        <h4 class="text-sm text-gray-600">Revenue ($)</h4>
        <p class="text-2xl font-bold"><?= number_format($total_revenue, 2) ?></p>
      </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl shadow p-6 mb-10">
      <h2 class="text-xl font-semibold text-gray-700 mb-4">Monthly Reservations</h2>
      <canvas id="reservationChart" height="100"></canvas>
    </div>

    <!-- Monthly Revenue Chart -->
<div class="bg-white rounded-xl shadow p-6 mb-10">
  <h2 class="text-xl font-semibold text-gray-700 mb-4">Monthly Revenue (€)</h2>
  <canvas id="revenueChart" height="100"></canvas>
</div>


<!-- Latest Reservations -->
<div class="bg-white rounded-xl shadow p-6 mb-10">
  <h2 class="text-xl font-semibold text-gray-700 mb-4">Latest Reservations</h2>
  <div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
      <thead class="bg-gray-200 text-gray-600 uppercase text-xs">
        <tr>
          <th class="py-2 px-4">User</th>
          <th class="py-2 px-4">Room ID</th>
          <th class="py-2 px-4">Check-in</th>
          <th class="py-2 px-4">Check-out</th>
          <th class="py-2 px-4">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php while($res = mysqli_fetch_assoc($latest_res)): ?>
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4"><?= htmlspecialchars($res['first_name'] . ' ' . $res['last_name']) ?></td>
            <td class="py-3 px-4"><?= $res['room_id'] ?></td>
            <td class="py-3 px-4"><?= $res['check_in'] ?></td>
            <td class="py-3 px-4"><?= $res['check_out'] ?></td>
            <td class="py-3 px-4">
              <?php
                $status = strtolower($res['status']);
                $status_class = match($status) {
                  'pending' => 'bg-yellow-100 text-yellow-800',
                  'confirmed' => 'bg-green-100 text-green-800',
                  'cancelled' => 'bg-red-100 text-red-800',
                  default => 'bg-gray-100 text-gray-700'
                };
              ?>
              <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $status_class ?>">
                <?= ucfirst($status) ?>
              </span>

              <?php if ($status === 'pending'): ?>
              <div class="mt-2 flex gap-2">
                <a href="?action=confirm&id=<?= $res['id'] ?>" 
                   class="inline-block bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs shadow">
                  ✔ Confirm
                </a>
                <a href="?action=cancel&id=<?= $res['id'] ?>" 
                   class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs shadow">
                  ✖ Cancel
                </a>
              </div>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>


    <!-- Latest Reviews -->
    <div class="bg-white rounded-xl shadow p-6">
      <h2 class="text-xl font-semibold text-gray-700 mb-4">Latest Reviews</h2>
      <div class="space-y-4">
        <?php while($rev = mysqli_fetch_assoc($latest_reviews)): ?>
          <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <p class="text-sm text-gray-700 italic mb-1">"<?= htmlspecialchars($rev['comment']) ?>"</p>
            <div class="text-sm text-gray-600 flex justify-between">
              <span>⭐ <?= $rev['rating'] ?>/5</span>
              <span><?= $rev['first_name'] . ' ' . $rev['last_name'] ?></span>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
    
  </main>

    <!-- Contact Messages -->
<div class="bg-white rounded-xl shadow p-6 mb-10">
  <h2 class="text-xl font-semibold text-gray-700 mb-4">Latest Contact Messages</h2>

  <div class="space-y-4">
    <?php while($msg = mysqli_fetch_assoc($messages)): ?>
      <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow transition">
        <div class="flex justify-between items-center mb-1">
          <h4 class="font-semibold text-gray-800"><?= strtoupper(htmlspecialchars($msg['name'])) ?></h4>
          <span class="text-xs text-gray-500">
            <?= date("d M Y H:i", strtotime($msg['created_at'])) ?>
          </span>
        </div>
        <p class="text-sm text-indigo-600 mb-1"><?= htmlspecialchars($msg['email']) ?></p>
        <p class="text-gray-700 italic">“<?= htmlspecialchars($msg['message']) ?>”</p>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
  <!-- Upcoming Reservations -->
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold text-indigo-700 mb-6">Upcoming Reservations (Next 7 Days)</h2>
    <?php if (mysqli_num_rows($upcoming_reservations) > 0): ?>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-indigo-100 text-indigo-800 uppercase text-xs">
            <tr>
              <th class="py-3 px-4">Guest</th>
              <th class="py-3 px-4">Room</th>
              <th class="py-3 px-4">Check-in</th>
              <th class="py-3 px-4">Check-out</th>
              <th class="py-3 px-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php while ($res = mysqli_fetch_assoc($upcoming_reservations)): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($res['first_name'] . ' ' . $res['last_name']) ?></td>
                <td class="px-4 py-3 text-gray-700">Room <?= $res['room_number'] ?></td>
                <td class="px-4 py-3 text-gray-600"><?= date("d M Y", strtotime($res['check_in'])) ?></td>
                <td class="px-4 py-3 text-gray-600"><?= date("d M Y", strtotime($res['check_out'])) ?></td>
                <td class="px-4 py-3">
                  <span class="px-2 py-1 text-xs rounded-full font-semibold
                    <?= match(strtolower($res['status'])) {
                      'confirmed' => 'bg-green-100 text-green-700',
                      'pending' => 'bg-yellow-100 text-yellow-700',
                      'cancelled' => 'bg-red-100 text-red-700',
                      default => 'bg-gray-100 text-gray-600'
                    } ?>">
                    <?= ucfirst($res['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-gray-500 italic">There are no reservations in the next 7 days.</p>
    <?php endif; ?>
  </div>

  <!-- Upcoming Check-Outs -->
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold text-indigo-700 mb-6">Upcoming Check-Outs</h2>
    <?php if (mysqli_num_rows($upcoming_checkouts) > 0): ?>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-indigo-100 text-indigo-800 uppercase text-xs">
            <tr>
              <th class="py-3 px-4">Guest</th>
              <th class="py-3 px-4">Room</th>
              <th class="py-3 px-4">Check-out</th>
              <th class="py-3 px-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php while($check = mysqli_fetch_assoc($upcoming_checkouts)): ?>
              <?php
                $isToday = $check['check_out'] == $today;
                $badgeText = $isToday ? "Today" : "Tomorrow";
                $badgeColor = $isToday ? "bg-yellow-100 text-yellow-700" : "bg-blue-100 text-blue-700";
              ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($check['first_name'] . ' ' . $check['last_name']) ?></td>
                <td class="px-4 py-3 text-gray-700">Room <?= $check['room_number'] ?></td>
                <td class="px-4 py-3 text-gray-600">
                  <?= date("d M Y", strtotime($check['check_out'])) ?>
                  <span class="ml-2 px-2 py-1 text-xs rounded-full font-semibold <?= $badgeColor ?>">
                    <?= $badgeText ?>
                  </span>
                </td>
                <td class="px-4 py-3">
                  <span class="px-2 py-1 text-xs rounded-full font-semibold
                    <?= match(strtolower($check['status'])) {
                      'confirmed' => 'bg-green-100 text-green-700',
                      'pending' => 'bg-yellow-100 text-yellow-700',
                      'cancelled' => 'bg-red-100 text-red-700',
                      default => 'bg-gray-100 text-gray-600'
                    } ?>">
                    <?= ucfirst($check['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-gray-500 italic">No guests are checking out today or tomorrow.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Chart Script -->
<script>
  const ctx = document.getElementById('reservationChart').getContext('2d');
  const reservationChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($months) ?>,
      datasets: [{
        label: 'Reservations',
        data: <?= json_encode($reservations) ?>,
        fill: true,
        backgroundColor: 'rgba(99, 102, 241, 0.2)',
        borderColor: 'rgba(99, 102, 241, 1)',
        borderWidth: 2,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        }
      }
    }
  });



// Revenue Chart
const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctxRevenue, {
    type: 'bar',
    data: {
      labels: <?= json_encode($rev_labels) ?>,
      datasets: [{
        label: 'Revenue (€)',
        data: <?= json_encode($rev_data) ?>,
        backgroundColor: 'rgba(34,197,94,0.4)',
        borderColor: 'rgba(34,197,94,1)',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: value => '€' + value
          }
        }
      }
    }
  });

  function toggleDropdown() {
    const dropdown = document.getElementById("profileDropdown");
    dropdown.classList.toggle("hidden");
  }

  // Optional: close dropdown if clicking outside
  window.addEventListener("click", function(e) {
    const button = e.target.closest("button");
    const dropdown = document.getElementById("profileDropdown");
    if (!e.target.closest("#profileDropdown") && !button) {
      dropdown?.classList.add("hidden");
    }
  });


  
</script>

</body>
</html>
