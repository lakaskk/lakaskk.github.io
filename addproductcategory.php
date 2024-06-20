<?php 
require("connect.php");

$ProductCategory=$_POST["ProductCategory"];
$InventoryName=$_POST["InventoryName"];

$sql = "INSERT INTO productcategory (ProductCategory, InventoryName)
VALUES ('$ProductCategory', '$InventoryName')";

if ($conn->query($sql) === TRUE) {
  $success = true;
} else {
  $success = false . $sql . $conn->error;
}
if ($success) {
  // redirect กลับไปยังหน้าหลักพร้อมกับ parameter ที่บ่งบอกว่าการดำเนินการสำเร็จ
  header("Location: inventory.php?status=success");
} else {
  // redirect กลับไปยังหน้าหลักพร้อมกับ parameter ที่บ่งบอกว่าการดำเนินการล้มเหลว
  header("Location: inventory.php?status=failure");
}

$conn->close();
?>