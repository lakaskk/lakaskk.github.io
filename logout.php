<?php
// เริ่ม session
session_start();

// ลบ session ทั้งหมด
session_destroy();

// ส่งผู้ใช้กลับไปยังหน้าหลักหรือหน้า login
header("Location: index.php"); // หรือให้เป็นหน้าอื่นที่ต้องการส่งผู้ใช้ไป
exit();
?>