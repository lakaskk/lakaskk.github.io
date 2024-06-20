<?php include 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php require('connect.php') ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/style.css">
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
                            <a class="nav-link active" aria-current="page" href="customer.php">รายชื่อลูกค้า</a>
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
                        if (isset($_SESSION['role']) || $_SESSION['role'] === 'Dev' && $_SESSION['role'] === 'SuperAdmin') {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="users.php">จัดการผู้ใช้</a>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                    <?php
                    if(isset($_SESSION['username'])){
                        echo '<form action="logout.php" method="post" class="d-flex">
                        <p class="nav-link mb-0 me-3">Welcome, '.$_SESSION['fullname'].'!</p>
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
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">เพิ่มรายชื่อลูกค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addcustomer.php" method="POST">
                        <div class="mb-3">
                            <label for="CustomerName" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" name="CustomerName" id="CustomerName" aria-describedby="helpId" required />
                            <small id="helpId" class="form-text text-muted">กรอกชื่อลูกค้า*</small>
                        </div>
                        <div class="mb-3">
                            <label for="CustomerAddress" class="form-label">ที่อยู่</label>
                            <textarea class="form-control" name="CustomerAddress" id="CustomerAddress" rows="3"></textarea>
                            <small id="helpId" class="form-text text-muted">กรอกรายละเอียดที่อยู่*</small>
                        </div>
                        <div class="mb-3">
                            <label for="CustomerTel" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control" name="CustomerTel" id="CustomerTel" min="9" max="10" />
                            <small id="helpId" class="form-text text-muted">กรอกเบอร์โทรศัพท์*</small>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูลลูกค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editcustomer.php" method="POST">
                        <div class="mb-3">
                            <label for="edit_CustomerName" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" name="edit_CustomerName" id="edit_CustomerName" aria-describedby="helpId" required />
                            <small id="helpId" class="form-text text-muted">กรอกชื่อลูกค้า*</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_CustomerAddress" class="form-label">ที่อยู่</label>
                            <textarea class="form-control" name="edit_CustomerAddress" id="edit_CustomerAddress" rows="3"></textarea>
                            <small id="helpId" class="form-text text-muted">กรอกรายละเอียดที่อยู่*</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_CustomerTel" class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control" name="edit_CustomerTel" id="edit_CustomerTel" min="9" max="10" />
                            <small id="helpId" class="form-text text-muted">กรอกเบอร์โทรศัพท์*</small>
                        </div>
                        <input type="hidden" name="edit_CustomerID" id="editCustomerID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <?php

    $sql = "SELECT * FROM customer";
    $result = $conn->query($sql);

    ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-custom my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                เพิ่มรายชื่อลูกค้า
            </button>
        </div>
        <table id="CustomerTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>รหัสลูกค้า</th>
                    <th>ชื่อลูกค้า</th>
                    <th>ที่อยู่</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    while ($row = $result->fetch_assoc()) { ?>
                        <th><?php echo $row["CustomerID"] ?></th>
                        <td><?php echo $row["CustomerName"] ?></td>
                        <td><?php echo $row["CustomerAddress"] ?></td>
                        <td><?php echo $row["CustomerTel"] ?></td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm edit-btn" data-id="<?php echo $row['CustomerID']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">แก้ไข</button>
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?php echo $row['CustomerID']; ?>">ลบ</button>
                        </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        src = "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#CustomerTable').DataTable();
        });
    </script>
    <script>
    // JavaScript code for populating edit modal with customer data
    const editButtons = document.querySelectorAll('.edit-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const customerId = button.getAttribute('data-id');
            // ดึงข้อมูลรายการที่ต้องการแก้ไขจากฐานข้อมูล
            fetch(`getcustomer.php?CustomerID=${customerId}`)
                .then(response => response.json())
                .then(data => {
                    // นำข้อมูลไปแสดงใน Modal แก้ไข
                    document.getElementById('editCustomerID').value = data.CustomerID;
                    document.getElementById('edit_CustomerName').value = data.CustomerName;
                    document.getElementById('edit_CustomerAddress').value = data.CustomerAddress;
                    document.getElementById('edit_CustomerTel').value = data.CustomerTel;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
    </script>
    <script>
        // JavaScript code for deleting a customer
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const customerId = button.getAttribute('data-id');
                    Swal.fire({
                        title: 'คุณแน่ใจหรือไม่?',
                        text: 'คุณต้องการลบคลังสินค้านี้?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'ลบ!',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `deletecustomer.php?id=${customerId}`;
                        }
                    });
                });
            });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                Swal.fire(
                    'สำเร็จ!',
                    'รายการถูกบันทึกเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'failure') {
                Swal.fire(
                    'ล้มเหลว!',
                    'เกิดข้อผิดพลาดในการบันทึกรายการ',
                    'error'
                );
            } else if (status === 'delete_success') {
                Swal.fire(
                    'สำเร็จ!',
                    'ลบรายการเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'delete_failure') {
                Swal.fire(
                    'ล้มเหลว!',
                    'มีข้อผิดพลาดเกิดขึ้นในการลบรายการ',
                    'error'
                );
            } else if (status === 'edit_success') {
                Swal.fire(
                    'สำเร็จ!',
                    'แก้ไขรายการเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'edit_failure') {
                Swal.fire(
                    'ล้มเหลว!',
                    'มีข้อผิดพลาดเกิดขึ้นในการแก้ไขรายการ',
                    'error'
                );
            }
        });
    </script>
</body>

</html>