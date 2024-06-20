<?php
session_start();
require('connect.php');

// ตรวจสอบสิทธิ์ผู้ใช้
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'SuperAdmin') {
    echo "Unauthorized access.";
    exit();
}

// รับค่าจากฟอร์ม
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']); // แก้ไขเป็น 'password' แทน 'Password'
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$email = mysqli_real_escape_string($conn, $_POST['email']); // แก้ไขเป็น 'email' แทน 'Email'
$role = mysqli_real_escape_string($conn, $_POST['role']);

// เพิ่มผู้ใช้ใหม่ลงในฐานข้อมูล
$sql = "INSERT INTO users (username, password, email, fullname, role) VALUES (?, ?, ?, ?, ?)"; // เปลี่ยนชื่อคอลัมน์เป็น lowercase
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $username, $password, $email, $fullname, $role);

if ($stmt->execute()) {
    header("Location: users.php?status=add_success");
} else {
    header("Location: users.php?status=add_failure");
}

$stmt->close();
$conn->close();
?>
