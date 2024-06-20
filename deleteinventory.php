<?php
require('connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM inventory WHERE invenID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: inventory.php?status=delete_success");
    } else {
        header("Location: inventory.php?status=delete_failure");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: inventory.php?status=delete_failure");
}
