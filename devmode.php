<?php include 'check_session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php require('connect.php') ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Mode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* กำหนด CSS สำหรับปุ่มแบบใหม่ */
        .btn-custom {
            align-items: center;
            background-image: linear-gradient(135deg, #6f42c1 40%, #0056b3);
            border: 0;
            border-radius: 10px;
            box-sizing: border-box;
            color: #fff;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            font-family: "Codec cold", sans-serif;
            font-size: 16px;
            font-weight: 700;
            height: 54px;
            justify-content: center;
            letter-spacing: .4px;
            line-height: 1;
            max-width: 100%;
            padding-left: 20px;
            padding-right: 20px;
            padding-top: 3px;
            text-decoration: none;
            text-transform: uppercase;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .btn-custom:active {
            outline: 0;
        }

        .btn-custom:hover {
            outline: 0;
        }

        .btn-custom span {
            transition: all 200ms;
        }

        .btn-custom:hover span {
            transform: scale(.9);
            opacity: .75;
        }

        @media screen and (max-width: 991px) {
            .btn-custom {
                font-size: 15px;
                height: 50px;
            }

            .btn-custom span {
                line-height: 50px;
            }
        }
    </style>
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
                            echo '<a class="nav-link active" aria-current="page" href="devmode.php">Dev Mode</a>';
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

    <div class="container">
        <h1>Developer Mode</h1>
        <p>ยินดีต้อนรับสู่โหมดนักพัฒนา! ใช้หน้านี้ในการทดสอบสิ่งใหม่ๆ ที่คุณต้องการเพิ่มในอนาคต.</p>

        <!-- เพิ่มเนื้อหาสำหรับการทดลองที่นี่ -->
        <div class="row">
            <button class="btn-custom"><span>Click Me</span></button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>