<?php
session_start();
require('connect.php');

// ตรวจสอบสิทธิ์ผู้ใช้
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'SuperAdmin') {
    echo "Unauthorized access.";
    exit();
}

// รับค่าจากฟอร์ม
$userID = intval($_POST['edit_userID']);
$username = mysqli_real_escape_string($conn, $_POST['edit_username']);
$fullname = mysqli_real_escape_string($conn, $_POST['edit_fullname']);
$email = mysqli_real_escape_string($conn, $_POST['edit_email']);
$role = mysqli_real_escape_string($conn, $_POST['edit_role']);

// เตรียมคำสั่ง SQL สำหรับการอัพเดทข้อมูลผู้ใช้
if (!empty($password)) {
    // ถ้ามีการเปลี่ยนรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET username=?, fullname=?, email=?, role=? WHERE UserID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $fullname, $email, $role, $userID);
} else {
    // ถ้าไม่มีการเปลี่ยนรหัสผ่าน
    $sql = "UPDATE users SET username=?, fullname=?, email=?, role=? WHERE UserID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $fullname, $email, $role, $userID);
}

if ($stmt->execute()) {
    header("Location: users.php?status=edit_success");
} else {
    header("Location: users.php?status=edit_failure");
}

$stmt->close();
$conn->close();
?>