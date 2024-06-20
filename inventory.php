<?php include 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="th">
<?php require('connect.php') ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คลังสินค้า</title>
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
                            <a class="nav-link active" aria-current="page" href="inventory.php">คลังสินค้า</a>
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
    <!-- Modal for Adding Inventory -->
    <div class="modal fade" id="addInventoryModal" data-bs-backdrop="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addInventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInventoryModalLabel">เพิ่มคลังสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addinventory.php" method="POST">
                        <div class="mb-3">
                            <label for="invenAddress" class="form-label">ที่อยู่</label>
                            <textarea class="form-control" name="invenAddress" id="invenAddress" rows="3"></textarea>
                            <small id="helpId" class="form-text text-muted">กรอกรายละเอียดที่อยู่*</small>
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
    <!-- Edit Inventory Modal -->
    <div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInventoryModalLabel">แก้ไขคลังสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editinventory.php" method="POST">
                        <div class="mb-3">
                            <label for="edit_invenAddress" class="form-label">ที่อยู่</label>
                            <textarea class="form-control" name="edit_invenAddress" id="edit_invenAddress" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="edit_invenID" id="edit_invenID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal for Adding Product Category -->
    <div class="modal fade" id="addProductCategoryModal" data-bs-backdrop="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductCategoryModalLabel">เพิ่มประเภทสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="addproductcategory.php" method="POST">
                        <div class="mb-3">
                            <label for="ProductCategory" class="form-label">ประเภทสินค้า</label>
                            <input type="text" class="form-control" name="ProductCategory" id="ProductCategory" aria-describedby="helpId" required />
                            <small id="helpId" class="form-text text-muted">กรอกประเภทสินค้า*</small>
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
    <!-- Edit Category Modal -->
    <div class="modal fade" id="editProductCategoryModal" tabindex="-1" aria-labelledby="editProductCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInventoryModalLabel">แก้ไขประเภทสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="editproductcategory.php" method="POST">
                        <div class="mb-3">
                            <label for="editProductCategory" class="form-label">ชื่อประเภท</label>
                            <input type="text" class="form-control" name="editProductCategory" id="editProductCategory" required />
                        </div>
                        <input type="hidden" name="edit_invenID" id="edit_invenID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <?php
    $sql = "SELECT * FROM inventory";
    $result = $conn->query($sql);
    ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="mt-4">คลังสินค้า</h2>
            <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                เพิ่มคลังสินค้า
            </button>
        </div>
        <table id="inventoryTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>รหัสคลังสินค้า</th>
                    <th>ที่อยู่</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <th><?php echo $row["invenID"] ?></th>
                        <td><?php echo $row["invenAddress"] ?></td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm edit-inventory-btn" data-id="<?php echo $row['invenID']; ?>" data-address="<?php echo $row['invenAddress']; ?>" data-bs-toggle="modal" data-bs-target="#editInventoryModal">แก้ไข</button>
                            <button class="btn btn-outline-danger btn-sm delete-inventory-btn" data-id="<?php echo $row['invenID']; ?>">ลบ</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
    $sql = "SELECT * FROM productcategory";
    $result = $conn->query($sql);
    ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="mt-4">ประเภทสินค้า</h2>
            <button type="button" class="btn btn-custom data-bs-toggle=modal" data-bs-target="#addProductCategoryModal">
                เพิ่มประเภทสินค้า
            </button>
        </div>
        <table id="productCategoryTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>รหัสประเภทสินค้า</th>
                    <th>ประเภทสินค้า</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <th><?php echo $row["CategoryID"] ?></th>
                        <td><?php echo $row["ProductCategory"] ?></td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm edit-category-btn" data-id="<?php echo $row['CategoryID']; ?>" data-name="<?php echo $row['ProductCategory']; ?>" data-bs-toggle="modal" data-bs-target="#editProductCategoryModal">แก้ไข</button>
                            <button class="btn btn-outline-danger btn-sm delete-category-btn" data-id="<?php echo $row['CategoryID']; ?>">ลบ</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-inventory-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const invenID = button.getAttribute('data-id');
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
                            window.location.href = `deleteinventory.php?id=${invenID}`;
                        }
                    });
                });
            });

            const editButtons = document.querySelectorAll('.edit-inventory-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = button.getAttribute('data-id');
                    const address = button.getAttribute('data-address');
                    document.getElementById('edit_invenID').value = id;
                    document.getElementById('edit_invenAddress').value = address;
                });
            });

            const deleteCategoryButtons = document.querySelectorAll('.delete-category-btn');
            deleteCategoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const categoryID = button.getAttribute('data-id');
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
                            window.location.href = `deleteproductcategory.php?id=${categoryID}`;
                        }
                    });
                });
            });

            const editCategoryButtons = document.querySelectorAll('.edit-category-btn');
            editCategoryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const categoryid = button.getAttribute('data-categoryid');
                    const name = button.getAttribute('data-name');
                    document.getElementById('edit_ProductCategoryID').value = categoryid;
                    document.getElementById('edit_ProductCategory').value = name;
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
                    'บันทึกข้อมูลเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'failure') {
                Swal.fire(
                    'ล้มเหลว!',
                    'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                    'error'
                );
            } else if (status === 'delete_success') {
                Swal.fire(
                    'สำเร็จ!',
                    'ลบข้อมูลเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'delete_failure') {
                Swal.fire(
                    'ล้มเหลว!',
                    'เกิดข้อผิดพลาดในการลบข้อมูล',
                    'error'
                );
            } else if (status === 'edit_success') {
                Swal.fire(
                    'สำเร็จ!',
                    'แก้ไขข้อมูลเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'edit_failure') {
                Swal.fire(
                    'ล้มเหลว!',
                    'เกิดข้อผิดพลาดในการแก้ไขข้อมูล',
                    'error'
                );
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#inventoryTable').DataTable();
            $('#productCategoryTable').DataTable();
        });
    </script>
</body>

</html>