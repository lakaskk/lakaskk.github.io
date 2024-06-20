<?php include 'check_session.php'; ?>
<?php require('connect.php'); ?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PRM Thailand - สินค้า คลังสินค้า รายการสั่งซื้อ รายชื่อลูกค้า และจัดการผู้ใช้">
    <meta name="keywords" content="PRM Thailand, สินค้า, คลังสินค้า, รายการสั่งซื้อ, รายชื่อลูกค้า, จัดการผู้ใช้">
    <title>ระบบรายงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">PRM Thailand</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="product.php">สินค้า</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="inventory.php">คลังสินค้า</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="order.php">รายการสั่งซื้อ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="customer.php">รายชื่อลูกค้า</a>
                        </li>
                        <?php
                        // แสดงลิงค์จัดการผู้ใช้ เฉพาะผู้ใช้ที่มี role เป็น Dev
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'Dev') {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="devmode.php">Dev Mode</a>';
                            echo '</li>';
                        }
                        ?>
                        <?php
                        // แสดงลิงค์จัดการผู้ใช้ เฉพาะผู้ใช้ที่มี role เป็น SuperAdmin
                        if (isset($_SESSION['role']) && ($_SESSION['role'] === 'Dev' || $_SESSION['role'] === 'SuperAdmin')) {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="users.php">จัดการผู้ใช้</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<form action="logout.php" method="post" class="d-flex">
                        <p class="nav-link mb-0 me-3">Welcome, ' . $_SESSION['fullname'] . '!</p>
                        <button class="btn btn-outline-danger" type="submit">Logout</button>
                        </form>';
                    } else {
                        echo '<form action="login.php" method="post" class="d-flex">
                        <input class="form-control me-2" type="text" name="username" placeholder="Username" required>
                        <input class="form-control me-2" type="password" name="password" placeholder="Password" required>
                        <button class="btn btn-outline-success" type="submit">Login</button>
                        </form>';
                    }
                    ?>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
        <h2 class="mb-4">รายงานการเคลื่อนไหวในคลัง</h2>

        <!-- รายงานรายเดือน -->
        <h3>รายงานรายเดือน</h3>
        <table id="month" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ปี</th>
                    <th>เดือน</th>
                    <th>รวมสินค้าเข้าคลัง</th>
                    <th>รวมสินค้าออกคลัง</th>
                    <th>รายได้รวม (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn->query("SET lc_time_names = 'th_TH'");

                $sql = "SELECT 
                            YEAR(OrDate) + 543 AS year, 
                            DATE_FORMAT(OrDate, '%M') AS month, 
                            SUM(CASE WHEN ProductImage IS NOT NULL THEN OrQuantity ELSE 0 END) AS total_in, 
                            SUM(CASE WHEN ProductImage IS NULL THEN OrQuantity ELSE 0 END) AS total_out, 
                            SUM(ProductPrice) AS total_income 
                        FROM 
                            ordetail 
                        GROUP BY 
                            YEAR(OrDate), MONTH(OrDate)
                        ORDER BY 
                            YEAR(OrDate), MONTH(OrDate)";

                $result = $conn->query($sql);
                if ($result === FALSE) {
                    echo "<tr><td colspan='5'>Error: " . $conn->error . "</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "<td>" . $row['month'] . "</td>";
                        echo "<td class='total_out'>" . $row['total_out'] . "</td>";
                        echo "<td class='total_in'>" . $row['total_in'] . "</td>";
                        echo "<td>" . $row['total_income'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        

        <!-- รายงานรายปี -->
        <h3>รายงานรายปี</h3>
        <table id="year" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ปี</th>
                    <th>รวมสินค้าเข้าคลัง</th>
                    <th>รวมสินค้าออกคลัง</th>
                    <th>รายได้รวม (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT 
                            YEAR(OrDate) + 543 AS year, 
                            SUM(CASE WHEN ProductImage IS NOT NULL THEN OrQuantity ELSE 0 END) AS total_in, 
                            SUM(CASE WHEN ProductImage IS NULL THEN OrQuantity ELSE 0 END) AS total_out, 
                            SUM(ProductPrice) AS total_income 
                        FROM 
                            ordetail 
                        GROUP BY 
                            YEAR(OrDate)
                        ORDER BY 
                            YEAR(OrDate)";

                $result = $conn->query($sql);
                if ($result === FALSE) {
                    echo "<tr><td colspan='4'>Error: " . $conn->error . "</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['year'] . "</td>";
                        echo "<td class='total_out'>" . $row['total_out'] . "</td>";
                        echo "<td class='total_in'>" . $row['total_in'] . "</td>";
                        echo "<td>" . $row['total_income'] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#month').DataTable();
            $('#year').DataTable();

            // Function to update data in DataTable
            function updateDataTable() {
                // Reload the table data
                $('#month').DataTable().ajax.reload();
                $('#year').DataTable().ajax.reload();
            }

            // Example AJAX call when updating OrQuantity or ReorderQuantity
            function updateOrQuantity(orderId, newQuantity) {
                $.ajax({
                    url: 'update_quantity.php',
                    method: 'POST',
                    data: {
                        orderId: orderId,
                        newQuantity: newQuantity
                    },
                    success: function(response) {
                        // Check the response for success or failure
                        if (response.status === 'success') {
                            Swal.fire(
                                'สำเร็จ!',
                                'อัปเดตจำนวนสินค้าเรียบร้อยแล้ว',
                                'success'
                            );
                            // Update the DataTables after successful update
                            updateDataTable();
                        } else {
                            Swal.fire(
                                'ล้มเหลว!',
                                'มีข้อผิดพลาดในการอัปเดตจำนวนสินค้า',
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'ล้มเหลว!',
                            'มีข้อผิดพลาดในการเชื่อมต่อ',
                            'error'
                        );
                    }
                });
            }

            // Example trigger for updating OrQuantity (change as per your application)
            $('.update-quantity-btn').click(function() {
                var orderId = $(this).data('order-id');
                var newQuantity = $('#quantity-input-' + orderId).val();
                // Call the update function
                updateOrQuantity(orderId, newQuantity);
            });
        });
    </script>
</body>

</html>
