<?php
declare(strict_types=1);
session_start();

// Check if the session token exists
if (!isset($_SESSION['token'])) {
    header('Location: /company/login.php');
    exit;
}

// Check if the success and branch_id parameters are set
if($_REQUEST['success']){
    if(!isset($_REQUEST['branch_id'])){
        header('Location: /company/navigationsgoods/deliverygoods/deliverygoods.php');
        exit;
    }
}

// Database connection details
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";

// Create connection
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch goods from the database
$goods_query = 'SELECT id, name FROM goods';
$goods = $conn->query($goods_query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة التصدير</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://lottie.host/ec4359c4-5efb-4283-8d11-d09784ddbd48/L7aADkVCV8.lottie">
    <link rel="icon" href="https://img.icons8.com/?size=100&id=112158&format=png&color=000000">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cairo:wght@200..1000&family=Changa:wght@200..800&family=El+Messiri:wght@400..700&family=Fustat:wght@200..800&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Lalezar&family=Rakkas&family=Readex+Pro:wght@160..700&family=Rubik:ital,wght@0,300..900;1,300..900&family=Scheherazade+New:wght@400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            box-sizing: border-box;
            font-family: "Changa", sans-serif;
        }

        h1, h3 {
            font-family: "Cairo", sans-serif;
            font-style: normal;
        }

        button {
            cursor: pointer;
        }

        body {
            min-height: 100vh;
        }

        .g-3 {
            display: none;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center flex-column">
    <?php if($_REQUEST['success']): ?>
        <h1 class="text-success" id="result"><span class="spinner-grow text-success"></span> تمت العملية بنجاح</h1>
        <h1>الربح الزائد: <?php echo $_REQUEST['res']."$";?></h1>
        <form action="/company/controllers/GoodsControllers/exportgoods.php" method="POST" class="row g-3 mt-2" enctype="multipart/form-data">
        <h1>التوصيل للفرع</h1>
            <div class="col-md-6">
                <label for="name" class="form-label">البضاعة</label>
                <select name="name" id="name" class="form-select">
                    <?php while($row_goods = $goods->fetch_assoc()): ?>
                    <option value="<?php echo $row_goods['name']; ?>"><?php echo $row_goods['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="hidden" name="branch_id" value="<?php echo $_REQUEST['branch_id']; ?>">
            <div class="col-md-6">
                <label for="quantity" class="form-label">الكمية</label>
                <input type="number" name="quantity" class="form-control">
            </div>
            <div class="col-12">
                <label for="created_at" class="form-label">تاريخ التسليم</label>
                <input type="date" name="date" id="date" class="form-control">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-warning">توصيل</button>
            </div>
        </form>
        <div class="buttons mt-3">
            <button type="button" class="btn btn-dark" id="btn-display-form">اضف بضائع اخرى</button>
            <a href="/company/dashboard.php" class="btn btn-outline-info">العودة لشاشة الرئيسية </a>
        </div>
        <script>
            document.getElementById("btn-display-form").addEventListener("click", () => {
                const formRows = document.getElementsByClassName("g-3");
                Array.from(formRows).forEach(row => {
                    row.style.display = "flex";
                });
            });
        </script>
    <?php elseif($_REQUEST['error']): ?>
        <h1 class="text-danger text-center"><span class="spinner-grow text-danger"></span> خطا لم يصدر البضاعة</h1>
    <?php endif; ?>
</body>
</html>
