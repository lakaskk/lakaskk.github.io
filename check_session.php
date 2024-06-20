<?php
session_start();

// ตรวจสอบว่าไม่มี session 'username' (หมายถึงผู้ใช้ไม่ได้ล็อกอิน)
if (!isset($_SESSION['username'])) {
    // ส่งผู้ใช้ไปยังหน้า login.php
    header("Location: login.php");
    exit(); // ออกจากการทำงานของสคริปต์
}
?>