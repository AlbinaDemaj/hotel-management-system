<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['password_error'] = "All fields are required.";
    header("Location: user_profile.php?tab=change_password");
    exit;
}

if ($new_password !== $confirm_password) {
    $_SESSION['password_error'] = "New passwords do not match.";
    header("Location: user_profile.php?tab=change_password");
    exit;
}

$result = mysqli_query($conn, "SELECT password FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($result);

if (!$user || !password_verify($old_password, $user['password'])) {
    $_SESSION['password_error'] = "Old password is incorrect.";
    header("Location: user_profile.php?tab=change_password");
    exit;
}


$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
mysqli_query($conn, "UPDATE users SET password = '$hashed_new_password' WHERE id = '$user_id'");

$_SESSION['password_success'] = "Password updated successfully!";
header("Location: user_profile.php?tab=change_password");
exit;
?>
