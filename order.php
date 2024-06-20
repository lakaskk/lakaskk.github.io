<?php include 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php require('connect.php') ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คำสั่งซื้อ</title>
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
                            <a class="nav-link" aria-current="page" href="product.php">สินค้า</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="inventory.php">คลังสินค้า</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="order.php">รายการสั่งซื้อ</a>
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
                        if (isset($_SESSION['role']) || $_SESSION['role'] === 'Dev' && $_SESSION['role'] === 'SuperAdmin') {
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
    <!-- Modal -->
    <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        เพิ่มรายการสั่งซื้อ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="addorder.php" method="POST" id="orderForm">
                        <div id="productContainer">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="ProductName1" class="form-label">ชื่อสินค้า 1</label>
                                    <select class="form-select" name="ProductName[]" id="ProductName1" required>
                                        <option selected disabled>เลือกสินค้า</option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT ProductName FROM product");
                                        $data = $sql->fetch_all(MYSQLI_ASSOC);

                                        foreach ($data as $row) {
                                        ?>
                                            <option value="<?php echo $row['ProductName']; ?>"><?php echo $row['ProductName']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="OrQuantity1" class="form-label">จำนวนสินค้า 1</label>
                                    <input type="number" class="form-control" name="OrQuantity[]" id="OrQuantity1" min="1" max="999" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="addProductButton">เพิ่มสินค้า</button>
                        <div class="mb-3">
                            <label for="OrDate" class="form-label">วันที่สั่งซื้อ</label>
                            <input type="date" class="form-control" name="OrDate" id="OrDate" required max="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="CustomerName" class="form-label">ชื่อลูกค้า</label>
                            <select class="form-select form-select-sm" name="CustomerName" id="CustomerName" required>
                                <option selected disabled>ตัวเลือก</option>
                                <?php
                                $sql = mysqli_query($conn, "SELECT CustomerName FROM customer");
                                $data = $sql->fetch_all(MYSQLI_ASSOC);

                                foreach ($data as $row) {
                                ?>
                                    <option value="<?php echo $row['CustomerName']; ?>"><?php echo $row['CustomerName']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                ปิด
                            </button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขรายการสั่งซื้อ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editorder.php" method="POST">
                        <input type="hidden" name="OrID" id="editOrID">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">ชื่อสินค้า</label>
                            <select class="form-select" id="editProductName" name="editProductName">
                                <?php
                                // ดึงรายการสินค้าจากฐานข้อมูล
                                $sql = "SELECT ProductName FROM product";
                                $result = $conn->query($sql);

                                // แสดงตัวเลือกสำหรับแต่ละสินค้า
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['ProductName'] . "'>" . $row['ProductName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editOrQuantity" class="form-label">จำนวนสินค้า</label>
                            <input type="number" class="form-control" id="editOrQuantity" name="editOrQuantity" min="1" max="999">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

    $sql = "SELECT ordetail.OrID, product.ProductImage, product.ProductName, ordetail.OrQuantity, product.ProductPrice, ordetail.OrDate, customer.CustomerName
                FROM ordetail
                JOIN product ON ordetail.ProductName = product.ProductName
                JOIN customer ON ordetail.CustomerName = customer.CustomerName";
    $result = $conn->query($sql);

    ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#modalId">
                เพิ่มรายการสั่งซื้อ
            </button>
        </div>
        <table id="orderTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>เลขที่คำสั่งซื้อ</th>
                    <th>รูปสินค้า</th>
                    <th>รายการสินค้า</th>
                    <th>จำนวน</th>
                    <th>ราคา</th>
                    <th>วันที่สั่งซื้อ</th>
                    <th>ชื่อลูกค้า</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require('connect.php');

                $sql = "SELECT ordetail.OrID, product.ProductName, ordetail.OrQuantity,ordetail.ProductPrice , ordetail.OrDate, customer.CustomerName, product.ProductImage
                FROM ordetail
                JOIN product ON ordetail.ProductName = product.ProductName
                JOIN customer ON ordetail.CustomerName = customer.CustomerName";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<th scope='row'>" . $row['OrID'] . "</th>";
                        echo "<td><img src='" . $row['ProductImage'] . "' class='img-thumbnail' width='100'></td>";
                        echo "<td>" . $row['ProductName'] . "</td>";
                        echo "<td>" . $row['OrQuantity'] . "</td>";
                        echo "<td>" . $row['ProductPrice'] . "</td>";
                        echo "<td>" . $row['OrDate'] . "</td>";
                        echo "<td>" . $row['CustomerName'] . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-outline-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-orid='" . $row['OrID'] . "' data-productname='" . $row['ProductName'] . "' data-orquantity='" . $row['OrQuantity'] . "' data-ordate='" . $row['OrDate'] . "' data-customername='" . $row['CustomerName'] . "'>แก้ไข</button> ";
                        echo "<button class='btn btn-outline-danger btn-sm' onclick='deleteOrder(" . $row['OrID'] . ")'>ลบ</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
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
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const orderId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการที่จะลบรายการนี้หรือไม่?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ลบ!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งคำขอลบไปยัง deleteorder.php ด้วยการ POST และตัวแปร OrID
                        fetch('deleteorder.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: new URLSearchParams({
                                    'OrID': orderId
                                })
                            })
                            .then(response => {
                                // ลบแถวที่ถูกลบออกจากตาราง
                                button.closest('tr').remove();
                                window.location.href = 'order.php?status=delete_success';
                                return response.text();
                            })
                            .then(data => {
                                console.log(data);
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                });
            });
        });
        // ดึงข้อมูลปุ่มแก้ไขทั้งหมด
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const orderId = button.getAttribute('data-id');
                // ดึงข้อมูลรายการที่ต้องการแก้ไขจากฐานข้อมูล
                fetch(`getorder.php?OrID=${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        // นำข้อมูลไปแสดงใน Modal แก้ไข
                        document.getElementById('editOrID').value = data.OrID;
                        document.getElementById('editProductName').value = data.ProductName;
                        document.getElementById('editOrQuantity').value = data.OrQuantity;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

        document.getElementById('addProductButton').addEventListener('click', function() {
            var productContainer = document.getElementById('productContainer');
            var productCount = productContainer.children.length + 1;

            var newProduct = `
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="ProductName${productCount}" class="form-label">ชื่อสินค้า ${productCount}</label>
                    <select class="form-select" name="ProductName[]" id="ProductName${productCount}" required>
                        <option selected disabled>เลือกสินค้า</option>
                        <?php
                        $sql = mysqli_query($conn, "SELECT ProductName FROM product");
                        $data = $sql->fetch_all(MYSQLI_ASSOC);

                        foreach ($data as $row) {
                        ?>
                            <option value="<?php echo $row['ProductName']; ?>"><?php echo $row['ProductName']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="OrQuantity${productCount}" class="form-label">จำนวนสินค้า ${productCount}</label>
                    <input type="number" class="form-control" name="OrQuantity[]" id="OrQuantity${productCount}" min="1" max="999" required>
                </div>
            </div>
            `;

            productContainer.insertAdjacentHTML('beforeend', newProduct);
        });
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
        <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#orderTable').DataTable();
        });
    </script>
</body>

</html>