<?php
require('connect.php');

// ตรวจสอบว่ามีการส่งค่า UserID มาหรือไม่
if (isset($_GET['UserID'])) {
    $userID = intval($_GET['UserID']);

    // เตรียมคำสั่ง SQL สำหรับดึงข้อมูลผู้ใช้
    $sql = "SELECT * FROM users WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // ส่งข้อมูลผู้ใช้กลับในรูปแบบ JSON
        echo json_encode($user);
    } else {
        // หากไม่พบผู้ใช้ ส่งข้อความแจ้งว่าไม่พบ
        echo json_encode(array("message" => "User not found."));
    }

    $stmt->close();
} else {
    // หากไม่มีการส่งค่า UserID มาส่งข้อความแจ้งว่าไม่ถูกต้อง
    echo json_encode(array("message" => "Invalid request."));
}

$conn->close();
?>
