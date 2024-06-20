<?php include 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php require('connect.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้า</title>
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
                            <a class="nav-link active" aria-current="page" href="product.php">สินค้า</a>
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
    <!-- Add Product Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">เพิ่มสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addproduct.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="ProductName" class="form-label">ชื่อสินค้า</label>
                            <input type="text" class="form-control" name="ProductName" id="ProductName" aria-describedby="helpId" placeholder="ProductName" required />
                            <small id="helpId" class="form-text text-muted">กรอกชื่อสินค้า*</small>
                        </div>
                        <div class="mb-3">
                            <label for="ProductDescription" class="form-label">รายละเอียดสินค้า</label>
                            <textarea class="form-control" name="ProductDescription" id="ProductDescription" rows="3" required></textarea>
                            <small id="helpId" class="form-text text-muted">กรอกรายละเอียดสินค้า*</small>
                        </div>
                        <div class="mb-3">
                            <label for="ProductPrice" class="form-label">ราคาสินค้า</label>
                            <input type="number" class="form-control" name="ProductPrice" id="ProductPrice" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="ProductCategory" class="form-label">ประเภทสินค้า</label>
                            <select class="form-select form-select-sm" name="ProductCategory" id="ProductCategory" required>
                                <option selected disabled>เลือกประเภทสินค้า</option>
                                <?php
                                require("connect.php");
                                $sql = mysqli_query($conn, "SELECT ProductCategory FROM productcategory");
                                $data = $sql->fetch_all(MYSQLI_ASSOC);

                                foreach ($data as $row) {
                                ?>
                                    <option value="<?php echo $row['ProductCategory']; ?>"><?php echo $row['ProductCategory']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <small id="helpId" class="form-text text-muted">โปรดเลือกประเภทสินค้า*</small>
                        </div>
                        <div class="mb-3">
                            <label for="ReorderQuantity" class="form-label">จำนวน</label>
                            <input type="number" class="form-control" name="ReorderQuantity" id="ReorderQuantity" min="1" max="999" required />
                            <small id="helpId" class="form-text text-muted">กรอกจำนวนสินค้า*</small>
                        </div>
                        <div class="mb-3">
                            <label for="invenID" class="form-label">คลังสินค้า</label>
                            <select class="form-select form-select-sm" name="invenID" id="invenID" required>
                                <option selected disabled>เลือกคลังสินค้า</option>
                                <?php
                                $sql = mysqli_query($conn, "SELECT invenID FROM inventory");
                                $data = $sql->fetch_all(MYSQLI_ASSOC);

                                foreach ($data as $row) {
                                ?>
                                    <option value="<?php echo $row['invenID']; ?>"><?php echo $row['invenID']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ProductImageFile" class="form-label">รูปภาพสินค้า</label>
                            <input type="file" class="form-control" name="ProductImageFile" id="ProductImageFile" accept="image/*" required>
                            <div id="imagePreviewContainer" style="display: none;">
                                <label for="imagePreview" class="form-label"></label>
                                <div id="imagePreview"></div>
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
    </div>
    <!-- Edit Product Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editproduct.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="edit_product_id" name="edit_product_id" value="">
                        <div class="mb-3">
                            <label for="edit_ProductName" class="form-label">ชื่อสินค้า</label>
                            <input type="text" class="form-control" name="edit_ProductName" id="edit_ProductName" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_ProductPrice" class="form-label">ราคาสินค้า</label>
                            <input type="number" class="form-control" name="edit_ProductPrice" id="edit_ProductPrice" min="0" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ProductDescription" class="form-label">รายละเอียดสินค้า</label>
                            <textarea class="form-control" name="edit_ProductDescription" id="edit_ProductDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ProductCategory" class="form-label">ประเภทสินค้า</label>
                            <select class="form-select form-select-sm" name="edit_ProductCategory" id="edit_ProductCategory" required>
                                <option selected disabled>เลือกประเภทสินค้า</option>
                                <?php
                                require("connect.php");
                                $sql = mysqli_query($conn, "SELECT ProductCategory FROM productcategory");
                                $data = $sql->fetch_all(MYSQLI_ASSOC);

                                foreach ($data as $row) {
                                ?>
                                    <option value="<?php echo $row['ProductCategory']; ?>"><?php echo $row['ProductCategory']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ReorderQuantity" class="form-label">จำนวน</label>
                            <input type="number" class="form-control" name="edit_ReorderQuantity" id="edit_ReorderQuantity" min="1" max="999" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_invenID" class="form-label">คลังสินค้า</label>
                            <select class="form-select form-select-sm" name="edit_invenID" id="edit_invenID" required>
                                <option selected disabled>เลือกคลังสินค้า</option>
                                <?php
                                $sql = mysqli_query($conn, "SELECT invenID FROM inventory");
                                $data = $sql->fetch_all(MYSQLI_ASSOC);

                                foreach ($data as $row) {
                                ?>
                                    <option value="<?php echo $row['invenID']; ?>"><?php echo $row['invenID']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_ProductImageFile" class="form-label">รูปภาพสินค้า</label>
                            <input type="file" class="form-control" name="edit_ProductImageFile" id="edit_ProductImageFile" accept="image/*">
                            <div id="edit_imagePreviewContainer" style="display: none;">
                                <label for="edit_imagePreview" class="form-label"></label>
                                <div id="edit_imagePreview"></div>
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
    </div>

    <?php

    $sql = "SELECT * FROM product";
    $result = mysqli_query($conn, $sql);

    $count = mysqli_num_rows($result);
    ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <button type="button" class="btn btn-custom my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                เพิ่มสินค้า
            </button>
            <button type="button" class="btn btn-success my-2" id="downloadExcel">ดาวน์โหลด .xlsx</button>
        </div>
        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>รายละเอียดสินค้า</th>
                    <th>ราคาสินค้า</th>
                    <th>ประเภทสินค้า</th>
                    <th>จำนวนสินค้า</th>
                    <th>คลังสินค้า</th>
                    <th>รูปภาพสินค้า</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    for ($i = 0; $i < $count; $i++) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC); ?>
                        <th><?php echo $row["ProductID"] ?></th>
                        <td><?php echo $row["ProductName"] ?></td>
                        <td><?php echo $row["ProductDescription"] ?></td>
                        <td><?php echo $row["ProductPrice"]; ?></td>
                        <td><?php echo $row["ProductCategory"] ?></td>
                        <td><?php echo $row["ReorderQuantity"] ?></td>
                        <td><?php echo $row["invenID"] ?></td>
                        <td><img src="<?php echo $row["ProductImage"]; ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;"></td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm edit-btn" data-id="<?php echo $row['ProductID']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">แก้ไข</button>
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?php echo $row['ProductID']; ?>">ลบ</button>
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
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-id');
                const productName = button.parentElement.parentElement.querySelector('td:nth-child(2)').innerText;
                const productDescription = button.parentElement.parentElement.querySelector('td:nth-child(3)').innerText;
                const productPrice = button.parentElement.parentElement.querySelector('td:nth-child(4)').innerText;
                const productCategory = button.parentElement.parentElement.querySelector('td:nth-child(5)').innerText;
                const reorderQuantity = button.parentElement.parentElement.querySelector('td:nth-child(6)').innerText;
                const invenID = button.parentElement.parentElement.querySelector('td:nth-child(7)').innerText;
                const productImageFile = button.parentElement.parentElement.querySelector('td:nth-child(8)').innerText;

                document.getElementById('edit_product_id').value = productId;
                document.getElementById('edit_ProductName').value = productName;
                document.getElementById('edit_ProductDescription').value = productDescription;
                document.getElementById('edit_ProductPrice').value = productPrice;
                document.getElementById('edit_ProductCategory').value = productCategory;
                document.getElementById('edit_ReorderQuantity').value = reorderQuantity;
                document.getElementById('edit_invenID').value = invenID;
                document.getElementById('edit_ProductImageFile').file = productImageFile;
            });
        });
        // JavaScript code for deleting a product
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = button.getAttribute('data-id');
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
                        window.location.href = `deleteproduct.php?id=${productId}`;
                    }
                });
            });
        });
        document.getElementById('downloadExcel').addEventListener('click', function() {
            window.location.href = 'export_excel.php';
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
            $('#productTable').DataTable();
        });
    </script>
    <script>
        document.getElementById('ProductImageFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('imagePreviewContainer');
            const preview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.style.display = 'block';
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Image preview" class="img-fluid">';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                preview.innerHTML = '';
            }
        });
    </script>
    <script>
        document.getElementById('edit_ProductImageFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('edit_imagePreviewContainer');
            const preview = document.getElementById('edit_imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.style.display = 'block';
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Image preview" class="img-fluid">';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                preview.innerHTML = '';
            }
        });
    </script>
</body>

</html>