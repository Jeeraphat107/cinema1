<?php
// ============================== PHP Section ==============================
session_start();

// ถ้าไม่มีการเข้าสู่ระบบ (ไม่มีอีเมลผู้ใช้ใน session) ให้เปลี่ยนหน้าไปที่หน้า login
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// เชื่อมต่อฐานข้อมูลและดึงข้อมูลการจอง
$servername = "localhost";
$username = "root";
$password = "";
$database = "cinema_booking";

$conn = new mysqli($servername, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลการจอง
$sql = "SELECT * FROM bookings";
$result = $conn->query($sql);
$schedule = [];

// แปลงข้อมูลให้เป็นแผนที่ตามวันและช่วงเวลา
while ($row = $result->fetch_assoc()) {
    $schedule[$row["day"]][$row["time_slot"]] = $row["status"];
}

$conn->close();
// ============================== End PHP Section ==============================

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Booking</title>
    
    <!-- ============================== Google Font (Mrit) ============================== -->
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- ============================== End Google Font ============================== -->

    <!-- ============================== CSS Section ============================== -->
    <style>
        /* ใช้ฟอนต์ Mitr */
        body {
            font-family: 'Mitr', sans-serif;
            background-color:#0B1622;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container ที่รวม sidebar และ content */
        .container {
    width: 70%; /* ลดขนาด container ลง */
    max-width: 1000px; /* จำกัดความกว้างสูงสุด */
}

.sidebar {
    width: 20%;
    padding: 2rem; /* เพิ่มพื้นที่ padding */
    display: flex;
    flex-direction: column;
    align-items: center; /* จัดแนวกลาง */
    justify-content: center; /* จัดให้อยู่กึ่งกลาง */
}

.sidebar h2 {
    font-size: 2rem;
    margin-bottom: 2rem; /* เพิ่มระยะห่างจากปุ่ม */
    color: rgb(255, 255, 255);
    white-space: nowrap;
    text-align: center; /* ทำให้ข้อความอยู่กลาง */
}

.main-content {
    width: 75%; /* ลดขนาด Main Content ลง */
    padding: 1.5rem; /* ลด padding ให้เล็กลง */
}

        .sidebar .btn {
            display: block;
            padding: 1rem;
            margin-bottom: 1rem;
            text-decoration: none;
            border-radius: 1rem;
            font-size: 1.2rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-blue {
            background-color:#578FCA;
            color: white;
        }

        .btn-blue:hover {
            background-color: #3674B5;
        }

        .btn-red {
            background-color: #578FCA;
            color: white;
        }

        .btn-red:hover {
            background-color:#3674B5;
        }

        .schedule-table {
    width: 85%; /* ลดขนาดตารางลง */
    background-color:#6D6D6D; /* เพิ่มพื้นหลังสีอ่อนให้กับตาราง */
}

.schedule-table td, 
.schedule-table th {
    padding: 0.6rem; /* ลดระยะห่างในช่องตาราง */
    height: 45px; /* ลดความสูงของช่องลง */
    min-width: 90px; /* ลดความกว้างของช่องลง */
}

.schedule-table td {
    padding: 1.2rem; /* เพิ่มพื้นที่รอบตัวหนังสือในช่อง */
    font-weight: bold; /* ทำให้ตัวหนังสือหนา */
    font-size: 1rem; /* ขนาดฟอนต์ปกติ */
    vertical-align: middle; /* จัดแนวตั้งให้ตรงกลาง */
    text-align: center; /* จัดแนวนอนให้ตรงกลาง */
}

.schedule-header th {
    padding: 0.8rem;  
    background-color:rgb(255, 255, 255); /* สีพื้นหลังของช่องวัน */
    font-size: 1rem; /* ปรับขนาดตัวหนังสือให้พอดี */
    white-space: nowrap; /* ป้องกันข้อความขึ้นบรรทัดใหม่ */
    color: #1E1E1E; 
}
/* สีพื้นหลังสำหรับสถานะการจอง */
.bg-green {
    background-color: #6ECB63; /* สีสำหรับช่องที่ว่าง */
}

.bg-red {
    background-color: #F72C5B; /* สีสำหรับช่องที่จองแล้ว */
}

        .sidebar h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: rgb(255, 255, 255);
    white-space: nowrap; /* ป้องกันการตัดข้อความให้อยู่บรรทัดเดียว */
}
    </style>
    <!-- ============================== End CSS Section ============================== -->

</head>
<body>

<!-- ============================== HTML Section ============================== -->
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>🎬 Cinema Booking</h2>
        <a href="booking.php" class="btn btn-blue">จองห้องดูหนัง</a>
        <a href="cancel.php" class="btn btn-red">ยกเลิกการจอง</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <table class="schedule-table">
            <tr class="schedule-header">
                <th></th>
                <th>จันทร์</th>
                <th>อังคาร</th>
                <th>พุธ</th>
                <th>พฤหัสบดี</th>
                <th>ศุกร์</th>
                <th>เสาร์</th>
                <th>อาทิตย์</th>
            </tr>
            <tr>
                <th>เช้า</th>
                <?php
                $days = ["จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์", "อาทิตย์"];
                foreach ($days as $day) {
                    $status = $schedule[$day]['เช้า'] ?? 'ว่าง';
                    $bgColor = ($status == 'จองแล้ว') ? 'bg-red' : 'bg-green';
                    echo "<td class='$bgColor'>$status</td>";
                }
                ?>
            </tr>
            <tr>
                <th>บ่าย</th>
                <?php
                foreach ($days as $day) {
                    $status = $schedule[$day]['บ่าย'] ?? 'ว่าง';
                    $bgColor = ($status == 'จองแล้ว') ? 'bg-red' : 'bg-green';
                    echo "<td class='$bgColor'>$status</td>";
                }
                ?>
            </tr>
        </table>
    </div>
</div>
<!-- ============================== End HTML Section ============================== -->

</body>
</html>