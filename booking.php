<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $day = $_POST["day"];
    $time_slot = $_POST["time_slot"];

    // ดึงสัปดาห์ปัจจุบันของปีนี้
    $current_week = date("oW"); // "oW" จะให้ค่าเป็น "ปี+เลขสัปดาห์" เช่น 202407

    // รีเซ็ตข้อมูลหากขึ้นสัปดาห์ใหม่
    $reset_sql = "UPDATE bookings SET status='ว่าง', student_id=NULL, week_number='$current_week' WHERE week_number < '$current_week'";
    $conn->query($reset_sql);

    // ตรวจสอบว่านักศึกษาคนนี้เคยจองแล้วในสัปดาห์นี้หรือไม่
    $check_sql = "SELECT * FROM bookings WHERE student_id='$student_id' AND week_number = '$current_week'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('คุณจองไปแล้วในสัปดาห์นี้! ไม่สามารถจองซ้ำได้');</script>";
    } else {
        // ถ้ายังไม่เคยจอง ให้ดำเนินการจอง
        $sql = "UPDATE bookings SET status='จองแล้ว', student_id='$student_id', week_number='$current_week' WHERE day='$day' AND time_slot='$time_slot' AND status='ว่าง'";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('จองสำเร็จ!'); window.location.href='index.php';</script>";
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองห้อง</title>
    <link href="https://fonts.googleapis.com/css2?family=Mitr&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0B1622;
            color: white;
            font-family: 'Mitr', sans-serif;
            text-align: center;
            padding: 0;
            margin: 0;
        }

        h1 {
            font-size: 3em;
            color: white;
            margin-top: 80px; /* คงระยะห่างเดิม */
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            margin-top: 40px; /* ลดลงจาก 80px เป็น 40px */
            padding: 20px;
            box-sizing: border-box;
        }

        label, select, input, button {
            width: 100%;
            margin: 10px 0;
            box-sizing: border-box;
        }

        input, select {
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 1em;
    font-family: 'Mitr', sans-serif; /* เพิ่มฟอนต์ Mitr */
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
            background-color: #6ECB63;
            color: black;
        }

        .btn-submit:hover {
            background-color: #06D001;
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

<h1>จองห้องดูหนัง</h1>

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

        <button class="btn-submit" type="submit">จอง</button>
        <button class="btn-back" type="button" onclick="history.back()">ย้อนกลับ</button>
    </form>
</div>

</body>
</html>
