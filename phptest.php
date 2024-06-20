<?php 
require('connect.php');
$OrID = "SELECT OrID FROM ordetail WHERE OrID = 671";
$editOrQuantity = 20;
$currentOrQuantity = "SELECT OrQuantity FROM ordetail WHERE OrID = '$OrID'";
$intValue = intval($editOrQuantity);

if($editOrQuantity > $currentOrQuantity){
        $diffOrQuantity = $currentOrQuantity - $editOrQuantity;
    } else if($editOrQuantity < $currentOrQuantity) {
        $diffOrQuantity = $currentOrQuantity - $editOrQuantity;
    } else{
        $diffOrQuantity = 0;
    }

    // คำนวณหาค่าใหม่ของ ReorderQuantity โดยลบหรือบวกจำนวนสินค้าที่เปลี่ยนแปลงไปจากค่าปัจจุบัน
    $newReorderQuantity = $currentReorderQuantity + $diffOrQuantity;
echo "$newReorderQuantity";
    ?>