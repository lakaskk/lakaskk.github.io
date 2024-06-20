<?php include 'check_session.php'; ?>

<?php
// ตรวจสอบว่าผู้ใช้มี role เป็น SuperAdmin หรือไม่
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'SuperAdmin' && $_SESSION['role'] !== 'Dev')) {
    // หากไม่ใช่ SuperAdmin ให้ redirect ไปยังหน้าอื่น
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require('connect.php') ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
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
                            echo '<a class="nav-link active" aria-current="page" href="users.php">จัดการผู้ใช้</a>';
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
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="true" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">เพิ่มผู้ใช้</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_user.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" name="username" id="username" required />
                        </div>
                        <div class="mb-3">
                            <label for="fullname" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" name="fullname" id="fullname" required />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="text" class="form-control" name="email" id="email" required />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" name="password" id="password" required />
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">บทบาท</label>
                            <select class="form-select" name="role" id="role" required>
                                <option value="Admin">Admin</option>
                                <option value="SuperAdmin">SuperAdmin</option>
                                <option value="Dev">Dev</option>
                            </select>
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
                    <h5 class="modal-title" id="editModalLabel">แก้ไขข้อมูลผู้ใช้</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="edit_user.php" method="POST">
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control" name="edit_username" id="edit_username" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_fullname" class="form-label">ชื่อ-สกุล</label>
                            <input type="text" class="form-control" name="edit_fullname" id="edit_fullname" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">อีเมล</label>
                            <input type="text" class="form-control" name="edit_email" id="edit_email" required />
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">บทบาท</label>
                            <select class="form-select" name="edit_role" id="edit_role" required>
                                <option value="Admin">Admin</option>
                                <option value="SuperAdmin">SuperAdmin</option>
                                <option value="Dev">Dev</option>
                            </select>
                        </div>
                        <input type="hidden" name="edit_userID" id="editUserID">
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

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center my-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-custom my-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                เพิ่มผู้ใช้
            </button>
        </div>
        <table id="UserTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>รหัสผู้ใช้</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-สกุล</th>
                    <th>อีเมล</th>
                    <th>บทบาท</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    while ($row = $result->fetch_assoc()) { ?>
                        <th><?php echo $row["UserID"] ?></th>
                        <td><?php echo $row["username"] ?></td>
                        <td><?php echo $row["fullname"] ?></td>
                        <td><?php echo $row["email"] ?></td>
                        <td><?php echo $row["role"] ?></td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm edit-btn" data-id="<?php echo $row['UserID']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">แก้ไข</button>
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?php echo $row['UserID']; ?>">ลบ</button>
                        </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7O5L3c3cgx4Cg6AcXr5fBthC10iQlP2R9UO" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#UserTable').DataTable();
        });
    </script>
    <script>
        // JavaScript code for populating edit modal with user data
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-id');
                // ดึงข้อมูลรายการที่ต้องการแก้ไขจากฐานข้อมูล
                fetch(`get_user.php?UserID=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        // นำข้อมูลไปแสดงใน Modal แก้ไข
                        document.getElementById('editUserID').value = data.UserID;
                        document.getElementById('edit_username').value = data.username;
                        document.getElementById('edit_fullname').value = data.fullname;
                        document.getElementById('edit_email').value = data.email;
                        document.getElementById('edit_role').value = data.role;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
    <script>
        // JavaScript code for deleting a user
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const UserID = button.getAttribute('data-id');
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
                        window.location.href = `delete_user.php?id=${UserID}`;
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'add_success') {
                Swal.fire(
                    'สำเร็จ!',
                    'รายการถูกบันทึกเรียบร้อยแล้ว',
                    'success'
                );
            } else if (status === 'add_failure') {
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