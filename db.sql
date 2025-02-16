CREATE DATABASE cinema_booking;
USE cinema_booking_booking;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 🔹 เพิ่มข้อมูลตัวอย่าง (ใช้รหัสผ่าน Hash ไว้ล่วงหน้า)
INSERT INTO students (email, student_id, password) VALUES 
('65041308127@udru.ac.th', '65041308127', SHA2('65041308127', 256)),  
('65041308138@udru.ac.th', '65041308138', SHA2('65041308138', 256)),
('65041308107@udru.ac.th', '65041308107', SHA2('65041308107', 256));
