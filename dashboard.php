<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
</head>
<body>
    <h2>ยินดีต้อนรับ <?= $_SESSION['user_email']; ?> </h2>
    <a href="logout.php">ออกจากระบบ</a>
</body>
</html>
