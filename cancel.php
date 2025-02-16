<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $day = $_POST["day"];
    $time_slot = $_POST["time_slot"];

    // ตรวจสอบว่ามีการจองห้องด้วยรหัสนักศึกษานี้หรือไม่
    $check_sql = "SELECT * FROM bookings WHERE day='$day' AND time_slot='$time_slot' AND student_id='$student_id' AND status='จองแล้ว'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // ถ้าพบข้อมูลตรงกัน ให้ยกเลิกการจอง
        $sql = "UPDATE bookings SET status='ว่าง', student_id=NULL WHERE day='$day' AND time_slot='$time_slot' AND student_id='$student_id'";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('ยกเลิกการจองสำเร็จ!'); window.location.href='index.php';</script>";
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    } else {
        echo "<script>alert('ยกเลิกไม่สำเร็จ! กรุณาตรวจสอบรหัสนักศึกษา');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยกเลิกการจอง</title>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0B1622;
            color: white;
            font-family: 'Mitr', sans-serif;
            text-align: center;
            padding-top: 50px;
            margin: 0;
        }

        h1 {
    font-size: 3em;
    margin-top: 50px; /* ลดลงจาก 80px เป็น 50px */
}


        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            margin-top: 40px; /* ปรับให้ข้อมูลขยับขึ้น */
            padding: 20px;
            box-sizing: border-box;
        }

        label, select, input, button {
            width: 100%;
            margin: 8px 0; /* ปรับระยะห่างให้แคบลงเล็กน้อย */
            box-sizing: border-box;
            font-family: 'Mitr', sans-serif; /* ใช้ฟอนต์ Mitr */
        }

        input, select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
            background-color: #6D6D6D;
            color: #1E1E1E;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-submit {
            background-color: #F65C78;
            color: white;
        }

        .btn-submit:hover {
            background-color: #F72C5B;
        }

        .btn-back {
            background-color: rgb(47, 46, 46);
            color: white;
        }

        .btn-back:hover {
            background-color: #1E1E1E;
        }
    </style>
</head>
<body>

<h1>ยกเลิกการจองห้อง</h1>

<div class="form-container">
    <form action="" method="POST">
        <label for="student_id">รหัสนักศึกษา:</label>
        <input type="text" name="student_id" id="student_id" required>

        <label for="day">เลือกวัน:</label>
        <select name="day" id="day" required>
            <option value="จันทร์">จันทร์</option>
            <option value="อังคาร">อังคาร</option>
            <option value="พุธ">พุธ</option>
            <option value="พฤหัสบดี">พฤหัสบดี</option>
            <option value="ศุกร์">ศุกร์</option>
            <option value="เสาร์">เสาร์</option>
            <option value="อาทิตย์">อาทิตย์</option>
        </select>

        <label for="time_slot">เลือกเวลา:</label>
        <select name="time_slot" id="time_slot" required>
            <option value="เช้า">เช้า</option>
            <option value="บ่าย">บ่าย</option>
        </select>

        <button class="btn-submit" type="submit">ยกเลิกการจอง</button>
        <button class="btn-back" type="button" onclick="history.back()">ย้อนกลับ</button>
    </form>
</div>

</body>
</html>
