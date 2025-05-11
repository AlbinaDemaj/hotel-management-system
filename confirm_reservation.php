<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$room_id = $_POST['room_id'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];

$room_result = mysqli_query($conn, "SELECT * FROM rooms WHERE id = '$room_id' AND status = 'available'");
$room = mysqli_fetch_assoc($room_result);

if ($room) {
    
    $total_price = $room['price']; 
    $status = 'pending';

    $insert_query = "INSERT INTO reservations (user_id, room_id, check_in, check_out, total_price, status)
                     VALUES ('$user_id', '$room_id', '$check_in', '$check_out', '$total_price', '$status')";
    mysqli_query($conn, $insert_query);
    mysqli_query($conn, "UPDATE rooms SET status = 'booked' WHERE id = '$room_id'");
    header("Location: my_reservations.php");
    exit;
} else {
    echo "Dhomë e papërftuar ose rezervimi nuk mund të bëhet.";
}
?>
