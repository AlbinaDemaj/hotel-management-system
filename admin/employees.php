<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "INSERT INTO employees (first_name, last_name, position, email, phone) 
            VALUES ('$first_name', '$last_name', '$position', '$email', '$phone')";

    if (mysqli_query($conn, $sql)) {
        header("Location: employees.php");
        exit;
    } else {
        $error = "Error while adding employee!";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_employee'])) {
    $id = $_POST['employee_id'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "UPDATE employees SET first_name='$first_name', last_name='$last_name', position='$position', email='$email', phone='$phone' 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: employees.php");
        exit;
    } else {
        $error = "Error while updating employee!";
    }
}

if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $employee = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM employees WHERE id = $id"));
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM employees WHERE id = $id");
    header("Location: employees.php");
    exit;
}

$employees = mysqli_query($conn, "SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Employees</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex">
  <aside class="w-64 bg-white shadow-lg min-h-screen p-6">
    <h2 class="text-2xl font-extrabold text-indigo-600 mb-8">VistaLuxe</h2>
    <nav class="space-y-4 text-gray-700">
      <a href="dashboard.php" class="block hover:text-indigo-600 font-semibold">Dashboard</a>
      <a href="rooms.php" class="block hover:text-indigo-600">Manage Rooms</a>
      <a href="reservations.php" class="block hover:text-indigo-600">Manage Reservations</a>
      <a href="employees.php" class="block hover:text-indigo-600 font-semibold">Manage Employees</a>
      <a href="../logout.php" class="block text-red-500 hover:text-red-600">Logout</a>
    </nav>
  </aside>

  <main class="flex-1 p-10">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold text-gray-800">Employee Management</h2>
      <button id="openModalBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow">+ Add Employee</button>
    </div>

    <div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-8 relative">
        <h3 class="text-2xl font-semibold mb-6 text-center text-indigo-700">
          <?= isset($employee) ? 'Edit Employee' : 'Add New Employee' ?>
        </h3>

        <?php if (isset($error)): ?>
          <div class="bg-red-100 text-red-600 p-2 mb-4 rounded text-center text-sm font-medium">
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
          <?php if (isset($employee)): ?>
            <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
          <?php endif; ?>
          <input type="text" name="first_name" placeholder="First Name" required value="<?= isset($employee) ? htmlspecialchars($employee['first_name']) : '' ?>" class="w-full px-4 py-2 border rounded">
          <input type="text" name="last_name" placeholder="Last Name" required value="<?= isset($employee) ? htmlspecialchars($employee['last_name']) : '' ?>" class="w-full px-4 py-2 border rounded">
          <input type="text" name="position" placeholder="Position" required value="<?= isset($employee) ? htmlspecialchars($employee['position']) : '' ?>" class="w-full px-4 py-2 border rounded">
          <input type="email" name="email" placeholder="Email" required value="<?= isset($employee) ? htmlspecialchars($employee['email']) : '' ?>" class="w-full px-4 py-2 border rounded">
          <input type="text" name="phone" placeholder="Phone" required value="<?= isset($employee) ? htmlspecialchars($employee['phone']) : '' ?>" class="w-full px-4 py-2 border rounded">
          <div class="flex justify-between items-center mt-4">
            <?php if (isset($employee)): ?>
              <button type="submit" name="update_employee" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
            <?php else: ?>
              <button type="submit" name="add_employee" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Save</button>
            <?php endif; ?>
            <button type="button" onclick="closeModal()" class="text-red-600 hover:underline">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-indigo-100 text-indigo-800 uppercase text-xs">
          <tr>
            <th class="py-3 px-6">Name</th>
            <th class="py-3 px-6">Position</th>
            <th class="py-3 px-6">Email</th>
            <th class="py-3 px-6">Phone</th>
            <th class="py-3 px-6">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php while ($emp = mysqli_fetch_assoc($employees)): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium text-gray-800"><?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?></td>
              <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($emp['position']) ?></td>
              <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($emp['email']) ?></td>
              <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($emp['phone']) ?></td>
              <td class="px-6 py-4">
                <a href="?edit_id=<?= $emp['id'] ?>" class="text-blue-600 hover:underline mr-4">Edit</a>
                <a href="?delete_id=<?= $emp['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<script>
  const modal = document.getElementById('addEmployeeModal');
  const openModalBtn = document.getElementById('openModalBtn');

  openModalBtn.onclick = () => modal.classList.remove('hidden');
  function closeModal() { modal.classList.add('hidden'); }

 
  <?php if (isset($employee)): ?>
    window.addEventListener('DOMContentLoaded', () => {
      modal.classList.remove('hidden');
    });
  <?php endif; ?>
</script>

</body>
</html>
