<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_input_password = hash('sha256', $password); //เข้ารหัสรหัสผ่านที่รับมาก่อนเปรียบเทียบ

    $stmt = $conn->prepare("SELECT student_id, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($student_id, $hashed_password);
        $stmt->fetch();

        //เปรียบเทียบค่าที่เข้ารหัสแล้ว
        if ($hashed_password === $hashed_input_password) {
            $_SESSION['user_email'] = $email;
            $_SESSION['student_id'] = $student_id;
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        $error = "❌ อีเมลนี้ไม่มีอยู่ในระบบ!";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ล็อกอิน - ระบบจองห้องดูหนัง</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>ระบบจองห้องดูหนังหอสมุด</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="รหัสนักศึกษา" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>