<?php
declare(strict_types=1);
session_start();
if (!$_SESSION['token']) {
    header('Location: /company/login.php');
    exit;
}
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
$token = $_SESSION['token'];
$query_info = $conn->prepare('SELECT * FROM users WHERE token = ?');
$query_info->bind_param('s', $token);
$query_info->execute();
$user_info = $query_info->get_result();
$user_data = [];
if ($user_info->num_rows > 0) {
    $user_data = $user_info->fetch_assoc();
}
$query_info->close();
function CheckLogin(): bool
{
    return isset($_SESSION['token']);
}

$query_goods = 'SELECT * FROM goods';
$goods = $conn->query($query_goods);

$query_disadvantage = 'SELECT SUM(quantity * price) AS total_value FROM goods;';
$result_disadvantage = $conn->query($query_disadvantage);

if ($result_disadvantage && $row_disadvantage = $result_disadvantage->fetch_assoc()) {
    $disadvantage = $row_disadvantage['total_value'] ?? 0;
} else {
    $disadvantage = 0;
}
$percent_disadvantage = $disadvantage * 0.01;

$query_total_branches = 'SELECT COUNT(id) AS total_branches FROM branches';
$result_total_branches = $conn->query($query_total_branches);
$row_total_branches = $result_total_branches->fetch_assoc();
$total_branches = $row_total_branches['total_branches'] ?? 0;

$query_four_records = 'SELECT * FROM goods LIMIT 4;';
$four_records = $conn->query($query_four_records);

$query_four_branches = 'SELECT * FROM branches LIMIT 4;';
$four_brnaches = $conn->query($query_four_branches);

$advantage = $conn->query('SELECT SUM(price*quantity) AS advantage FROM exports;')->fetch_assoc();
if ($advantage && $advantage['advantage'] !== null) {
    $advantage_value = $advantage['advantage'];
} else {
    $advantage_value = 0;
}
$percent_advantage  = $advantage_value * 0.01;

$total_quantity_quere = $conn->query('SELECT SUM(quantity) AS total_quantity FROM goods;')->fetch_assoc();
if ($total_quantity_quere && $total_quantity_quere !== null) {
    $total_quantity_goods = $total_quantity_quere['total_quantity'];
} else {
    $total_quantity_goods = 0;
}
$percent_total_quantity = $total_quantity_goods * 0.01;

$exports_query = $conn->query('SELECT * FROM exports;');

$count_exports = $conn->query('SELECT COUNT(id) AS total_count_export FROM exports;');
$total_exports = $count_exports->fetch_assoc();
if ($total_exports && $total_exports !== null) {
    $total_quantity_export =  $total_exports['total_count_export'];
} else {
    $total_quantity_export = 0;
}
$percent_total_quantity_export = $total_quantity_export * 0.01;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://lottie.host/ec4359c4-5efb-4283-8d11-d09784ddbd48/L7aADkVCV8.lottie">
    <link rel="icon" href="https://img.icons8.com/?size=100&id=jqexjjILFgDS&format=png&color=000000">
    <style>
        /*
        1- font-family: "Scheherazade New", serif;
        2- font-family: "Fustat", sans-serif;
        3-font-family: "Rakkas", serif;
        4-font-family: "Lalezar", system-ui;
        5-  font-family: "El Messiri", sans-serif;
        6- font-family: "Readex Pro", sans-serif;
        7-  font-family: "Changa", sans-serif;
        8-  font-family: "Amiri", serif;
        9-  font-family: "Almarai", sans-serif;
        10-font-family: "IBM Plex Sans Arabic", sans-serif;
        11-  font-family: "Tajawal", sans-serif;
        12- font-family: "Cairo", sans-serif;
        13-font-family: "Rubik", sans-serif;
        */
        @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cairo:wght@200..1000&family=Changa:wght@200..800&family=El+Messiri:wght@400..700&family=Fustat:wght@200..800&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Lalezar&family=Rakkas&family=Readex+Pro:wght@160..700&family=Rubik:ital,wght@0,300..900;1,300..900&family=Scheherazade+New:wght@400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            box-sizing: border-box;
            font-family: "Changa", sans-serif;
        }

        h1,
        h3 {
            font-family: "Cairo", sans-serif;
            font-style: normal;
        }

        button {
            cursor: pointer;
        }

        body {
            min-height: 100vh;
        }

        .links-dashboard li {
            margin: 0.6rem 0rem;
            width: 90%;
            margin-right: 0.5rem;
        }

        .content-slot-dashboard {
            position: absolute;
            width: calc(100% - 290px);
            left: 0;
        }

        .silder-nav {
            height: 95%;
            position: fixed;
            border-radius: 15px;
            right: 10px;
            top: 2.1%;
            z-index: 1000;
        }

        .silder-nav button {
            width: 100%;
            display: flex;
            justify-content: start;
        }

        .silder-nav button img {
            margin-left: 0.5rem;
        }

        .button-slider {
            display: none;
        }

        .product-buy,
        .exported-goods,
        .our-branches,
        .probalibilty,
        .container-dashboard-profile,
        .setting-dashboard {
            display: none;
        }

        #goods-in-store {
            display: none;
        }

        #branches-in-store {
            display: none;
        }

        #silder-nav {
            transition: transform 0.3s ease-in-out;
        }

        @media only screen and (max-width: 881px) {
            .silder-nav {
                transform: translateX(23rem);
            }

            .content-slot-dashboard {
                width: 100%;
            }

            .button-slider {
                display: inline-block;
            }
        }
    </style>
</head>

<body class="<?php
                if ($_SESSION['mode'] == 'dark') {
                    echo "bg-white";
                } else {
                    echo "bg-dark";
                }
                ?>">
    <!-- SLIDER NAV -->
    <div class="content-dashboard d-flex">
        <div class="d-flex flex-column <?php echo ($_SESSION['mode'] == 'dark') ? 'bg-dark' : 'bg-white'; ?> text-white silder-nav" id="silder-nav" style="width: 280px;">

            <!-- Header Section -->
            <div class="d-flex align-items-center text-white">
                <img src="https://img.icons8.com/?size=100&id=117508&format=png&color=000000" alt="" class="bi me-2" style="width:4rem;">
                <span class="fs-4 <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white' : 'text-dark'; ?>">لوحة التحكم</span>
            </div>
            <hr>

            <!-- Navigation Buttons -->
            <ul class="nav nav-pills flex-column links-dashboard">
                <li class="nav-item">
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-home" id="button-home">
                        <img src="https://img.icons8.com/?size=100&id=41651&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative; bottom: 0.2rem;">
                        الرئيسية
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-buy" id="button-product-buy">
                        <img src="https://img.icons8.com/?size=100&id=VRbCAbUTUPby&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative;">
                        بضائع مستوردة
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-export" id="button-exported-goods">
                        <img src="https://img.icons8.com/?size=100&id=8chNl15hy6jY&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative;">
                        بضائع مصدرة الى الافرع
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-branch" id="button-our-branches">
                        <img src="https://img.icons8.com/?size=100&id=Hn4L8YBSZXh5&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative;">
                        فروعنا
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-probality" id="button-probality">
                        <img src="https://img.icons8.com/?size=100&id=y8GdDkGlpWoA&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative;">
                        احصائية
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-setting" id="button-setting-dashboard">
                        <img src="https://img.icons8.com/?size=100&id=13108&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative;">
                        الإعدادات
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-link btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-light text-white' : 'btn-dark text-dark'; ?> button-profile" id="button-profile">
                        <img src="https://img.icons8.com/?size=100&id=SS5zeu5HKnnG&format=png&color=000000" alt="" class="bi me-1" style="width:2.3rem; position:relative;">
                        الملف الشخصي
                    </button>
                </li>
            </ul>
            <hr>

            <!-- External Links -->
            <div class="d-flex flex-column justify-content-start align-items-start">
                <a href="https://porfile-ips.netlify.app" class="btn btn-warning <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white' : 'text-dark'; ?> mx-2">صفحة المبرمج</a>
                <a href="https://beprogrammer.org" class="btn btn-outline-info <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white' : 'text-dark'; ?> mx-2 my-2">صفحة الشركة</a>
            </div>
        </div>
    </div>
    <!-- END SLIDER NAV -->

    <div class="content-slot-dashboard">
        <!--HEADER-->
        <header class="py-3 <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white bg-dark' : 'bg-white text-dark'; ?> mx-auto mt-3 rounded-5" style="width: 90% ;">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="dropdown">
                        <a href="#" class="d-block <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white' : 'text-dark'; ?> text-decoration-none dropdown-toggle"
                            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src=<?php
                                        if (CheckLogin()) {
                                            echo '../' . $user_data['image'];
                                        } else {
                                            echo "https://img.icons8.com/?size=100&id=108652&format=png&color=000000";
                                        }
                                        ?>
                                alt="mdo" width="32" height="32"
                                class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                            <li>
                                <form action="/company/controllers/UserControllers/user_logout.php">
                                    <button class="dropdown-item <?php echo ($_SESSION['mode'] == 'dark') ? 'btn btn-dark' : 'btn btn-white'; ?>" type="submit">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <?php
                        if (!CheckLogin()) {
                            echo '
                                <a href="/company/login.php" class="btn btn-outline-light me-2">تسجيل الدخول</a>
                                <a href="/company/registration.php" class="btn btn-warning">إنشاء حساب</a>
                                ';
                        }
                        ?>
                        <img class="rounded-circle" style="width:3rem; display:inline-block;" src="https://scontent.fjrs29-1.fna.fbcdn.net/v/t1.6435-9/53160215_983514671859230_3418556035017736192_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=a5f93a&_nc_ohc=M8loIWrGuHUQ7kNvgFco5sE&_nc_zt=23&_nc_ht=scontent.fjrs29-1.fna&_nc_gid=AS40DYteTKcwXxaBaSHwqg2&oh=00_AYD11IxWIAN2iPhdeN1bJ5fGeYkjkka6ursmSlbxOF5zbg&oe=6783B60E" alt="">
                        <button type="button" class="btn btn-success button-slider mx-2" id="button-slider"><i
                                class="fa-solid fa-list"></i></button>
                    </div>
                </div>
            </div>
        </header>
        <!--END HEADER-->
        <!--HOME-->
        <div class="home container-fluid section mt-3" id="home">
            <h1 class="">الرئيسية</h1>
            <div class="cards-info-home-dashboard mt-3 container-sm">
                <div class="container">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="card-home-dashboard d-flex justify-content-between align-items-center border <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white bg-dark' : 'bg-white text-dark'; ?>"
                                style="height: 9rem; border-radius:5px;">
                                <div class="icon-card-home mx-3">
                                    <i class="fa fa-list-alt rounded-circle bg-white text-dark p-3"
                                        aria-hidden="true" style="font-size:2rem;"></i>
                                </div>
                                <div class="texts-card-home d-flex flex-column mx-3">
                                    <h3>الخسائر</h3>
                                    <p class="text-start">
                                        <?php
                                        echo "$" . $disadvantage;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-home-dashboard d-flex justify-content-between align-items-center border <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white bg-dark' : 'bg-white text-dark'; ?>"
                                style="height: 9rem; border-radius:5px;">
                                <div class="icon-card-home mx-3">
                                    <i class="fa fa-credit-card-alt rounded-circle bg-white text-dark p-3"
                                        aria-hidden="true" style="font-size:2rem;"></i>
                                </div>
                                <div class="texts-card-home d-flex flex-column mx-3">
                                    <h3>عدد الافرع</h3>
                                    <p class="text-start">
                                        <?php
                                        echo $total_branches;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-lg-6">
                            <div class="card-home-dashboard d-flex justify-content-between align-items-center border <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white bg-dark' : 'bg-white text-dark'; ?>"
                                style="height: 9rem; border-radius:5px;">
                                <div class="icon-card-home mx-3">
                                    <i class="fa fa-tasks rounded-circle bg-white text-dark p-3" aria-hidden="true"
                                        style="font-size:2rem;"></i>
                                </div>
                                <div class="texts-card-home d-flex flex-column mx-3">
                                    <h3>كمية الربح الكلي</h3>
                                    <p class="text-start">$<?php echo $advantage_value; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card-home-dashboard d-flex justify-content-between align-items-center border <?php echo ($_SESSION['mode'] == 'dark') ? 'text-white bg-dark' : 'bg-white text-dark'; ?>"
                                style="height: 9rem; border-radius:5px;">
                                <div class="icon-card-home mx-3">
                                    <i class="fa fa-heart rounded-circle bg-white text-dark p-3" aria-hidden="true"
                                        style="font-size:2rem;"></i>
                                </div>
                                <div class="texts-card-home d-flex flex-column mx-3">
                                    <h3>كميات البضائع</h3>
                                    <p class="text-start">
                                        <?php
                                        if ($total_quantity_goods) {
                                            echo  $total_quantity_goods;
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <!-- Add Circle Chart and Default Chart -->
            <div class="container mt-4">
                <div class="d-flex flex-wrap justify-content-center" style="width: 100%;">
                    <div class="m-3">
                        <canvas id="myChart" style="width:25rem; height: 25rem;"></canvas>
                    </div>
                    <div class="m-3">
                        <canvas id="circleChart" style="width: 25rem; height: 25rem;"></canvas>
                    </div>
                </div>
            </div>
            <!-- End Chart -->
            <!-- Table Goods -->
            <div class="container-sm mt-3">
                <h1 class="text-center">البضائع</h1>
                <div class="table mt-3">
                    <table class="table <?php echo ($_SESSION['mode'] == 'dark') ? 'table-dark' : 'table-white'; ?> table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>اسم البضاعة</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>المكان</th>
                                <th>اسم المضيف</th>
                                <th>تاريخ الاضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($goods && $goods->num_rows > 0) {
                                while ($rowgoods = $goods->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($rowgoods['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowgoods['quantity']) . "</td>";
                                    echo "<td>" . "$" . htmlspecialchars($rowgoods['price']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowgoods['place']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowgoods['exporter']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowgoods['created_at']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>لا توجد بيانات لعرضها</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Table Goods -->
        </div>
        <!--END HOME-->
        <!--GOODS WE BUY-->
        <div class="product-buy container-sm mt-3 rounded-3 section" id="product-buy">
            <h1>البضائع المستوردة</h1>
            <!--INPUT FORM GOODS-->
            <form method="POST" action="<?php echo htmlspecialchars('/company/controllers/GoodsControllers/importgoods.php') ?>" class="row g-3 my-2" enctype="multipart/form-data">
                <div class="col-md-6">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="quantity" class="form-label">الكمية</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>
                <div class="col-12">
                    <label for="price" class="form-label">السعر</label>
                    <input type="number" name="price" class="form-control" step="0.01" required>
                </div>
                <div class="col-12">
                    <label for="place" class="form-label">المكان</label>
                    <input type="text" name="place" class="form-control" required>
                </div>
                <div class="col-12">
                    <label for="place" class="form-label">موعد الاستيراد</label>
                    <input type="date" name="date" class="form-control p-3" style="height: 10rem;" id="importDate" required>
                </div>
                <div class="col-12">
                    <label for="place" class="form-label">الصورة</label>
                    <input type="file" name="image" class="form-control" id="image" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-warning">اضافة للبضائع</button>
                </div>
            </form>
        </div>
        <!--END GOODS BUY-->
        <!--EXPORTED GOODS TO COMPANIES-->
        <div class="exported-goods container-sm mt-3 section" id="exported-goods">
            <h1>بضائع مصدرة الى الافرع</h1>
            <div class="buttons-exported-goods mt-3">
                <button type="button" class="btn btn-info display-goods-button text-white my-2" id="display-goods-button">عرض البضائع المتاحة في المستودع الرئيسي <i
                        class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn <?php echo ($_SESSION['mode'] == 'dark') ?  'btn-dark text-white' : 'btn-light text-dark'; ?> display-branches-button my-2" id="display-branches-button">مكان التوزيع <i class="fa fa-caret-down"
                        aria-hidden="true"></i>
                </button>
                <a href="/company/navigationsexports/exports/exports.php" class="btn btn-warning text-white my-2">التصديرات</a>
            </div>
            <div class="goods mt-5" id="goods-in-store">
                <hr>
                <h1>البضائع في المستودع الرئيسي</h1>
                <div class="mt-4 cards d-flex flex-wrap align-items-center justify-content-center gap-5">
                    <?php
                    while ($row_four_goods = $four_records->fetch_assoc()):
                    ?>
                        <div class="card" style="width: 18rem;">
                            <img src="<?php echo '../' . $row_four_goods['image']; ?>" class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title">الاسم: <?php echo $row_four_goods['name']; ?></h5>
                                <p class="card-text">المكان: <?php echo $row_four_goods['place']; ?></p>
                                <p class="card-text">السعر: $<?php echo $row_four_goods['price']; ?></p>
                                <p class="card-text">الكمية: <?php echo $row_four_goods['quantity']; ?></p>
                                <p class="card-text">المصدر: <?php echo $good['exporter']; ?></p>
                                <a href="<?php echo htmlspecialchars("/company/navigationsgoods/Informationgood/informationgood.php?good_id=" . $row_four_goods['id']); ?>"
                                    class="btn btn-primary">عرض معلومات البضاعة</a>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    ?>
                </div>
                <a href="/company/navigationsgoods/goods/goods.php" class="btn btn-outline-info my-3">عرض المزيد</a>
            </div>
            <div class="place-goods mt-5" id="branches-in-store">
                <hr>
                <h1>مكان التوزيع</h1>
                <div class="mt-4 cards d-flex flex-wrap align-items-center justify-content-center gap-5">
                    <?php while ($row_four_branches = $four_brnaches->fetch_assoc()): ?>
                        <div class="card" style="width: 18rem;">
                            <img src="<?php echo '../' . $row_four_branches['image']; ?>" class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title">اسم الفرع: <?php echo $row_four_branches['name']; ?></h5>
                                <p class="card-text">مكان الفرع يقع في: <?php echo $row_four_branches['place']; ?></p>
                                <a href="<?php echo htmlspecialchars("/company/navigationsgoods/deliver/deliver.php?branch_id=" . $row_four_branches['id']) ?>" class="btn btn-primary">تسليم بضاعة للفرع</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <a href="<?php echo htmlspecialchars('/company/navigationsgoods/deliverygoods/deliverygoods.php'); ?>" class="btn btn-outline-info my-3">عرض المزيد</a>
            </div>
        </div>
        <!--END EXPORTED GOODS TO COMPANIES-->
        <!--BRANCHES-->
        <div class="our-branches container-sm mt-3 section" id="our-branches">
            <h1>فروعنا</h1>
            <div class="list-group list-group-item-danger">
                <li
                    class="list-group-item list-group-item-action list-group-item-info d-flex align-items-center justify-content-between">
                    <a href="/company/navigationsbranches/editbranches/editbranches.php" class="text-decoration-none text-dark">التعديل على معلومات الفروع</a>
                    <span class="badge"><i class="fa fa-id-card" aria-hidden="true" style="font-size: 20px;"></i>
                    </span>
                </li>
                <li
                    class="list-group-item list-group-item-action list-group-item-info d-flex align-items-center justify-content-between">
                    <a href="/company/navigationsbranches/deletebranches/deletebranches.php" class="text-decoration-none text-dark">حذف فروع</a>
                    <span class="badge"><i class="fa fa-ban" aria-hidden="true" style="font-size: 20px;"></i>
                    </span>
                </li>
                <li
                    class="list-group-item list-group-item-action list-group-item-info d-flex align-items-center justify-content-between">
                    <a href="/company/navigationsbranches/branches/branches.php" class="text-decoration-none text-dark">عرض الفروع كافة</a>
                    <span class="badge"><i class="fa fa-television" aria-hidden="true" style="font-size: 20px;"></i>
                    </span>
                </li>
            </div>
            <hr>
            <h1>اضافة فرع</h1>
            <form method="POST" action="<?php echo htmlspecialchars('/company/controllers/BranchesControllers/addbranch.php'); ?>" class="row g-3" style="z-index:995;" enctype="multipart/form-data">
                <div class="col-md-6">
                    <label for="name" class="form-label">اسم الفرع</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="place" class="form-label">مكان الفرع</label>
                    <input type="text" name="place" class="form-control">
                </div>
                <div class="col-12">
                    <label for="place" class="form-label">الصورة</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-warning">اضافة الفرع</button>
                </div>
            </form>
            <hr>
            <div class="branch-animation d-flex align-items-center justify-content-center mt-5" style="width: 100%; height: 5rem; z-index: -1">
                <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                <dotlottie-player src="https://lottie.host/ec4359c4-5efb-4283-8d11-d09784ddbd48/L7aADkVCV8.lottie" background="transparent" speed="1" style="width: 100%; height:35rem; z-index: -1;" loop autoplay></dotlottie-player>
            </div>
        </div>
        <!--END BRANCHES-->
        <!--PROBALAITY-->
        <div class="probalibilty container mt-3 section" id="probality">
            <h1>الاحصائية</h1>
            <div class="container">
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    <div class="col">
                        <div class="card h-100 text-center shadow <?php echo ($_SESSION['mode'] == 'dark') ? 'bg-dark text-white' : 'bg-light text-dark'; ?>">
                            <div class="card-body">
                                <div class="display-4 <?php echo ($_SESSION['mode'] == 'dark') ? 'text-success' : 'text-success'; ?> mb-2">
                                    <i class="fa-solid fa-hand"></i>
                                </div>
                                <h2 class="card-title mb-3"><?php echo $percent_total_quantity; ?>%</h2>
                                <p class="card-text">نسبة الاستيراد</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 text-center shadow <?php echo ($_SESSION['mode'] == 'dark') ? 'bg-dark text-white' : 'bg-light text-dark'; ?>">
                            <div class="card-body">
                                <div class="display-4 <?php echo ($_SESSION['mode'] == 'dark') ? 'text-warning' : 'text-warning'; ?> mb-2">
                                    <i class="fa fa-expand" aria-hidden="true"></i>
                                </div>
                                <h2 class="card-title mb-3"><?php echo $percent_total_quantity_export; ?>%</h2>
                                <p class="card-text">نسبة التصدير</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 text-center shadow <?php echo ($_SESSION['mode'] == 'dark') ? 'bg-dark text-white' : 'bg-light text-dark'; ?>">
                            <div class="card-body">
                                <div class="display-4 <?php echo ($_SESSION['mode'] == 'dark') ? 'text-danger' : 'text-danger'; ?> mb-2">
                                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                                </div>
                                <h2 class="card-title mb-3"><?php echo $percent_advantage; ?>%</h2>
                                <p class="card-text">صافي الربح</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card h-100 text-center shadow <?php echo ($_SESSION['mode'] == 'dark') ? 'bg-dark text-white' : 'bg-light text-dark'; ?>">
                            <div class="card-body">
                                <div class="display-4 <?php echo ($_SESSION['mode'] == 'dark') ? 'text-danger' : 'text-danger'; ?> mb-2">
                                    <i class="fa fa-credit-card-alt text-primary" aria-hidden="true"></i>
                                </div>
                                <h2 class="card-title mb-3"><?php echo $percent_disadvantage; ?>%</h2>
                                <p class="card-text">نسبة الخسائر</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h1 class="text-center">التصديرات</h1>
                <div class="table mt-3">
                    <table class="table <?php echo ($_SESSION['mode'] == 'dark') ? 'table-dark' : 'table-white'; ?>  table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>اسم البضاعة</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>المكان</th>
                                <th>تاريخ الاضافة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($exports_query && $exports_query->num_rows > 0) {
                                while ($rowexport = $exports_query->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($rowexport['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowexport['quantity']) . "</td>";
                                    echo "<td>" . "$" . htmlspecialchars($rowexport['price']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowexport['place']) . "</td>";
                                    echo "<td>" . htmlspecialchars($rowexport['created_at']) . "</td>";
                                    echo "</tr>";
                                }
                                echo "<tfoot><tr><td colspan='6'>الربح: " . $advantage['advantage'] . "$" . "</td></tr></tfoot>";
                            } else {
                                echo "<tr><td colspan='6'>لا توجد بيانات لعرضها</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--END PROBALAITY-->
        <!--SETTING-->
        <div class="setting-dashboard container-sm mt-3 section" id="setting-dashboard">
            <form action="<?php echo htmlspecialchars('/company/controllers/UserControllers/account_setting.php'); ?>" method="POST" enctype="multipart/form-data">
                <h1>تعديل الملف الشخصي</h1>
                <div class="row g-2">
                    <div class="col">
                        <label for="name" class="mb-2">الاسم</label>
                        <input type="text" name="name" id="name" class="form-control" aria-label="name" required>
                    </div>
                    <div class="col">
                        <label for="username" class="mb-2">اسم المستخدم</label>
                        <input type="text" name="username" id="username" class="form-control" aria-label="username" required>
                    </div>
                </div>
                <div class="row g-1 mt-2">
                    <div class="col">
                        <label for="image" class="mb-2">الصورة</label>
                        <input type="file" name="image" id="image" class="form-control" aria-label="image">
                    </div>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col">
                        <label for="email" class="mb-2">البريد الإلكتروني</label>
                        <input type="text" name="email" id="email" class="form-control" aria-label="email" required>
                    </div>
                    <div class="col">
                        <label for="password" class="mb-2">كلمة المرور</label>
                        <input type="password" name="password" id="password" class="form-control"
                            aria-label="password" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <label for="password_confirmation" class="mb-2">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" aria-label="confirmationpassword" required>
                    </div>
                </div>
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($user_data['image']); ?>">
                <button type="submit" class="btn btn-dark btn-md px-4 py-2 mt-2">تحديث</button>
            </form>
            <hr>
            <div class="interface-settings my-2">
                <h1>إعدادات الواجهة</h1>
                <div class="row">
                    <select name="language" id="language" class="form-select p-3 mx-2 text-start"
                        style="width: 95%; background: <?php echo ($_SESSION['mode'] == 'dark') ? 'black; color: white;' : 'white; color: black;'; ?> ">
                        <div class="col">
                            <option value="0">اختر اللغة</option>
                            <option value="ar">العربية</option>
                            <option value="en">الإنجليزية</option>
                        </div>
                    </select>
                    <button type="button" class="btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-dark' : 'btn-light'; ?> btn-md px-4 py-2 mt-2" style="width:9rem;">حفظ</button>
                </div>
                <form class="row mt-2" action="/company/controllers/ModeControllers/choosemode.php" method="POST" enctype="multipart/form-data">
                    <select name="mode" id="mode" class="form-select p-3 mx-2 text-start" style="width: 95%; background: <?php echo ($_SESSION['mode'] == 'dark') ? 'black; color: white;' : 'white; color: black;'; ?>">
                        <option value="0">اختر الوضع</option>
                        <option value="dark">وضع الظلام</option>
                        <option value="light">وضع النور</option>
                    </select>
                    <button type="submit" class="btn <?php echo ($_SESSION['mode'] == 'dark') ? 'btn-dark' : 'btn-light'; ?> btn-md px-4 py-2 mt-2" style="width:9rem;">تغيير الوضع</button>
                </form>
            </div>
        </div>
        <!--END SETTINGS-->
        <!--PROFILE-->
        <div class="container-dashboard-profile container-sm mt-3 section" id="container-dashboard-profile">
            <div class="information-user" style="width: 100%;">
                <h1>معلومات الملف الشخصي</h1>
                <div class="image-profile" id="image-profile">
                    <img src=<?php
                                if (CheckLogin()) {
                                    echo '../' . $user_data['image'];
                                } else {
                                    echo "https://img.icons8.com/?size=100&id=108652&format=png&color=000000";
                                }
                                ?> alt="" class="rounded-circle border d-block mx-auto" style="width:20rem;">
                </div>
                <ul class="list-group text-white mt-3">
                    <li
                        class="list-group-item list-group-item-dark list-group-item-action d-flex align-items-center justify-content-between">
                        الاسم: <span class="badge bg-warning px-5 py-3">
                            <?php
                            if (CheckLogin()) {
                                echo $user_data['name'];
                            } else {
                                echo 'غير متوفر';
                            }
                            ?>
                        </span>
                    </li>
                    <li
                        class="list-group-item list-group-item-dark list-group-item-action d-flex align-items-center justify-content-between">
                        البريد الإلكتروني: <span class="badge bg-warning px-5 py-3">
                            <?php
                            if (CheckLogin()) {
                                echo $user_data['email'];
                            } else {
                                echo 'غير متوفر';
                            }
                            ?>
                        </span>
                    </li>
                    <li
                        class="list-group-item list-group-item-dark list-group-item-action d-flex align-items-center justify-content-between">
                        اسم المستخدم: <span class="badge bg-warning px-5 py-3">
                            <?php
                            if (CheckLogin()) {
                                echo $user_data['username'];
                            } else {
                                echo 'غير متوفر';
                            }
                            ?>
                        </span>
                    </li>
                    <li
                        class="list-group-item list-group-item-dark list-group-item-action d-flex align-items-center justify-content-between">
                        كلمة المرور: <span class="badge bg-warning px-5 py-3">
                            <?php
                            if (CheckLogin()) {
                                echo "*****";
                            } else {
                                echo 'غير متوفر';
                            }
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
            <hr>
            <h1 class="text-center">معلوماتك هنا بامان <span class="spinner-grow text-success"></span></h1>
        </div>
        <!--END PROFILE-->
    </div>
    </div>
    <!--SCRIPTS-->
    <script src="./js/display.js"></script>
    <?php
    $sql = "
    SELECT 
        YEAR(created_at) AS year,
        MONTH(created_at) AS month,
        SUM(price * quantity) AS total
    FROM exports
    WHERE created_at >= CURDATE() - INTERVAL 4 MONTH
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY YEAR(created_at) DESC, MONTH(created_at) DESC
";
    $result = $conn->query($sql);
    $monthlyData = [];
    while ($row = $result->fetch_assoc()) {
        $monthlyData[$row['year']][$row['month']] = $row['total'];
    }
    $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    $lastFourMonths = array_slice($months, -4);
    $chartData = [];
    foreach ($lastFourMonths as $month) {
        $year = date('Y');
        if (isset($monthlyData[$year][$month])) {
            $chartData[] = $monthlyData[$year][$month];
        } else {
            $chartData[] = 0;
        }
    }
    ?>
    <script>
        var monthlyData = <?php echo json_encode($chartData); ?>;
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['الشهر 1', 'الشهر 2', 'الشهر 3', 'الشهر 4'],
                datasets: [{
                    label: 'الربح الكلي اخر ٤ اشهر',
                    data: monthlyData,
                    backgroundColor: "<?php echo ($_SESSION['mode'] == 'dark') ? 'black' : 'white'; ?>",
                    borderColor: "<?php echo ($_SESSION['mode'] == 'dark') ? 'black' : 'white'; ?>",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        var advantageValue = <?php echo $advantage_value; ?>;
        var disadvantageValue = <?php echo   $disadvantage; ?>;
        var ctx = document.getElementById('circleChart').getContext('2d');
        var circleChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['الربح الكلي', 'الخسارة الكلية'],
                datasets: [{
                    label: 'النسبة',
                    data: [advantageValue, disadvantageValue],
                    backgroundColor: ['#be3144', '#ffc93c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    tooltip: {
                        enabled: true
                    },
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
    <script>
        document.getElementById('importDate').value = new Date().toISOString().split('T')[0];
    </script>
    <script>
        flaggoods = false;
        document.getElementById('display-goods-button').addEventListener('click', () => {
            if (flaggoods == false) {
                document.getElementById('goods-in-store').style.display = 'block';
                flaggoods = true;
            } else {
                document.getElementById('goods-in-store').style.display = 'none';
                flaggoods = false;
            }
        });
    </script>
    <script>
        flagbranches = false;
        document.getElementById('display-branches-button').addEventListener('click', () => {
            if (flagbranches == false) {
                document.getElementById('branches-in-store').style.display = 'block';
                flagbranches = true;
            } else {
                document.getElementById('branches-in-store').style.display = 'none';
                flagbranches = false;
            }
        });
    </script>
    <script>
        let flagnav = true;
        document.getElementById('button-slider').addEventListener('click', () => {
            if (flagnav == true) {
                document.getElementById('silder-nav').style.transform = 'translateX(0rem)';
                flagnav = false;
            } else {
                document.getElementById('silder-nav').style.transform = 'translateX(23rem)';
                flagnav = true;
            }
        });
    </script>
    <script>
        document.querySelectorAll('h1').forEach(function(h1) {
            h1.style.color = "<?php echo ($_SESSION['mode'] == 'dark') ? 'black' : 'white'; ?>";
        });
        document.querySelectorAll('label').forEach(function(label) {
            label.style.color = "<?php echo ($_SESSION['mode'] == 'dark') ? 'black' : 'white'; ?>";
        });
        document.querySelectorAll('hr').forEach(function(hr) {
            hr.style.borderColor = "<?php echo ($_SESSION['mode'] == 'dark') ? 'black' : 'white'; ?>";
        });
    </script>
    <!--END SCRIPTS-->
</body>

</html>