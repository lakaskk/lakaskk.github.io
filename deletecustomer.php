<?php
require('connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM customer WHERE CustomerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: customer.php?status=delete_success");
    } else {
        header("Location: customer.php?status=delete_failure");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: customer.php?status=delete_failure");
}

