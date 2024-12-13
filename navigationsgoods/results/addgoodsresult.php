<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتيجة الاستيراد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://lottie.host/ec4359c4-5efb-4283-8d11-d09784ddbd48/L7aADkVCV8.lottie">
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
    .g-3{
        display: none;
    }
</style>
</head>
<body class="d-flex align-items-center justify-content-center flex-column">
    <?php
    if($_REQUEST['success']){
    echo '<h1 class="text-success" id="result"><span class="spinner-grow text-success"></span> تمت العملية بنجاح</h1>
    <form action="" class="row g-3 mx-3">
        <div class="col-md-6">
            <label for="name" class="form-label">الاسم</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="quantity" class="form-label">الكمية</label>
            <input type="number" name="quantity" class="form-control">
        </div>
        <div class="col-12">
            <label for="price" class="form-label">السعر</label>
            <input type="number" name="price" class="form-control">
        </div>
        <div class="col-12">
            <label for="place" class="form-label">المكان</label>
            <input type="text" name="place" class="form-control">
        </div>
        <div class="col-12">
            <label for="place" class="form-label">موعد الاستيراد</label>
            <input type="date" name="date" class="form-control p-3" style="height: 10rem;" id="importDate">
        </div>
        <div class="col-12">
            <label for="place" class="form-label">الصورة</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-warning">اضافة للبضائع</button>
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
    </script>';
    }
    else if($_REQUEST['error']){
        echo '<h1 class="text-danger text-center"><span class="spinner-grow text-danger"></span> خطا لم يصدر المنتج</h1>';
    }
    ?>
</body>
</html>