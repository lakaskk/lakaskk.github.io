<?php
require('connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: product.php?status=delete_success");
    } else {
        header("Location: product.php?status=delete_failure");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: product.php?status=delete_failure");
}
