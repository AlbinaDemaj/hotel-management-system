<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM rooms WHERE id='$id'");

header('Location: rooms.php');
exit;
