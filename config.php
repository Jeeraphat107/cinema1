<?php
$host = "localhost";
$user = "root";  // เปลี่ยนถ้าใช้ user อื่น
$pass = "";  // ตั้งรหัสผ่านถ้ามี
$dbname = "cinema_booking";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}
?>

