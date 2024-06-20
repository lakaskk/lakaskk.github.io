<?php 
require("connect.php");

$CustomerName=$_POST["CustomerName"];
$CustomerAddress=$_POST["CustomerAddress"];
$CustomerTel=$_POST["CustomerTel"];


$sql = "INSERT INTO customer (CustomerName, CustomerAddress, CustomerTel)
VALUES ('$CustomerName', '$CustomerAddress', '$CustomerTel')";

if ($conn->query($sql) === TRUE) {
  $success = true;
} else {
  $success = false . $sql . $conn->error;
}
if ($success) {
  // redirect กลับไปยังหน้าหลักพร้อมกับ parameter ที่บ่งบอกว่าการดำเนินการสำเร็จ
  header("Location: customer.php?status=success");
} else {
  // redirect กลับไปยังหน้าหลักพร้อมกับ parameter ที่บ่งบอกว่าการดำเนินการล้มเหลว
  header("Location: customer.php?status=failure");
}

$conn->close();
?>