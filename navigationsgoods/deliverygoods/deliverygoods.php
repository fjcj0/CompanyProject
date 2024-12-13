<?php
declare(strict_types=1);
session_start();
if (!isset($_SESSION['token'])) {
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
$search_name = isset($_GET['name']) ? $_GET['name'] : '';
$records_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;
$total_query = "SELECT COUNT(*) AS total FROM branches WHERE name LIKE '%$search_name%'";
$result = $conn->query($total_query);
$total_rows = $result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $records_per_page);
$branches_stmt = "SELECT * FROM branches WHERE name LIKE '%$search_name%' LIMIT $records_per_page OFFSET $offset";
$branches_result = $conn->query($branches_stmt);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسليم بضاعات للفروع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://lottie.host/ec4359c4-5efb-4283-8d11-d09784ddbd48/L7aADkVCV8.lottie">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cairo:wght@200..1000&family=Changa:wght@200..800&family=El+Messiri:wght@400..700&family=Fustat:wght@200..800&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Lalezar&family=Rakkas&family=Readex+Pro:wght@160..700&family=Rubik:ital,wght@0,300..900;1,300..900&family=Scheherazade+New:wght@400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        * {
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            box-sizing: border-box;
            font-family: "Changa", sans-serif;
            text-align: right;
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
    </style>
</head>
<body class="d-flex align-items-center justify-content-center flex-column">
    <h1>كافة الفروع</h1>
    <form action="" class="mt-3">
        <div class="input-group">
            <input type="search" class="form-control rounded" name="name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="بحث" aria-label="Search" aria-describedby="search-addon"/>
            <button type="submit" class="btn btn-outline-primary" style="border-top-left-radius: 15px;border-bottom-left-radius: 15px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; position: relative; left: 1.3px;">بحث</button>
        </div>
    </form>

    <div class="mt-4 cards d-flex flex-wrap align-items-center justify-content-center gap-5">
        <?php if ($branches_result && $branches_result->num_rows > 0): ?>
        <?php while ($branch = $branches_result->fetch_assoc()): ?>
            <div class="card" style="width: 18rem;">
                <img src="<?php echo htmlspecialchars($branch['image']); ?>" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($branch['name']); ?></h5>
                    <p class="card-text">مكان الفرع يقع في: <?php echo htmlspecialchars($branch['place']); ?></p>
                    <a href="<?php echo htmlspecialchars("/company/navigationsgoods/deliver/deliver.php?branch_id=".$branch['id'])?>" class="btn btn-primary">تسليم بضاعة للفرع</a>
                </div>
            </div>
        <?php endwhile;?>
        <?php else: ?>
            <p>لا توجد نتائج</p>
        <?php endif;?>
    </div>
    <nav aria-label="Page navigation example" class="mt-3">
        <ul class="pagination">
            <?php if ($page > 1):?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&name=<?php echo urlencode($search_name); ?>" style="border-top-right-radius: 15px; border-bottom-right-radius: 15px; border-top-left-radius: 0px; border-bottom-left-radius: 0px;">السابق</a>
                </li>
            <?php endif;?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&name=<?php echo urlencode($search_name); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor;?>
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&name=<?php echo urlencode($search_name); ?>" style="border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-top-left-radius: 15px; border-bottom-left-radius: 15px;">التالي</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>
