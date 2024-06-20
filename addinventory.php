<?php
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $invenAddress = mysqli_real_escape_string($conn, $_POST['invenAddress']);

    // สร้างรหัสคลังสินค้าแบบสุ่ม 6 ตัว
    $invenID = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);

    // ตรวจสอบว่า invenID ไม่ซ้ำในฐานข้อมูล
    $sql_check = "SELECT * FROM inventory WHERE invenID='$invenID'";
    $result_check = $conn->query($sql_check);

    while ($result_check->num_rows > 0) {
        $invenID = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        $result_check = $conn->query($sql_check);
    }

    // เพิ่มข้อมูลในฐานข้อมูล
    $sql = "INSERT INTO inventory (invenID, invenAddress) VALUES ('$invenID', '$invenAddress')";

    if ($conn->query($sql) === TRUE) {
        header('Location: inventory.php?status=success');
    } else {
        header('Location: inventory.php?status=failure');
    }

    $conn->close();
}
?>
