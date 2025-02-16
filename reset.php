<?php
include 'config.php';

$day = date("l"); // ตรวจสอบว่าวันนี้คือวันอาทิตย์ไหม
if ($day == "Sunday") {
    $sql = "UPDATE bookings SET status='ว่าง'";
    $conn->query($sql);
}

header("Location: index.php");
?>
