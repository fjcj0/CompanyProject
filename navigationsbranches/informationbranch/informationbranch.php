<?php 
declare(strict_types=1);
session_start();
if (!isset($_SESSION['token'])) {
    header('Location: /company/login.php');
    exit;
}
if (!isset($_REQUEST['branch_id'])) {
    header('Location: company/navigationsbranches/branches/branches.php');
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
$branch_id = (int) $_REQUEST['branch_id'];
$stmt = $conn->prepare("SELECT * FROM branches WHERE id = ?");
$stmt->bind_param("i", $branch_id); 
$stmt->execute();
$data_branch = $stmt->get_result();
$branch = $data_branch->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معلومات الفرع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://lottie.host/ec4359c4-5efb-4283-8d11-d09784ddbd48/L7aADkVCV8.lottie">
    <link rel="icon" href="https://img.icons8.com/?size=100&id=4G2DeaJvYebI&format=png&color=000000">
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
    </style>
</head>
<body class="d-flex align-items-center justify-content-center flex-column">
    <h1>معلومات الفرع</h1>
    <div class="branch-info">
        <?php
        if ($branch) {
            if (!empty($branch['image'])) {
                echo '<img src="' . htmlspecialchars($branch['image']) . '" alt="Branch Image">';
            } else {
                echo '<p>لا توجد صورة للفرع</p>';
            }
            echo "<p>اسم الفرع: " . htmlspecialchars($branch['name']) . "</p>";
            echo "<p>المكان: " . htmlspecialchars($branch['place']) . "</p>";
        } else {
            echo "<p>الفرع غير موجود</p>";
        }
        ?>
    </div>
</body>
</html>
