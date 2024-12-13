<?php 
session_start();
if ($_SESSION['token']) {
    header('Location: /company/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cairo:wght@200..1000&family=Changa:wght@200..800&family=El+Messiri:wght@400..700&family=Fustat:wght@200..800&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Lalezar&family=Rakkas&family=Readex+Pro:wght@160..700&family=Rubik:ital,wght@0,300..900;1,300..900&family=Scheherazade+New:wght@400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        * {
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            box-sizing: border-box;
            font-family: "Changa", sans-serif;
        }
        h1,h3{
            font-family: "Cairo", sans-serif;
            font-style: normal;
        }
        body {
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <section class="d-flex align-items-center justify-content-center" style="width: 100%; min-height:100vh;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-3 shadow-sm">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="text-center mb-3">
                                <a href="#" class="disabled">
                                    <img src="https://img.icons8.com/?size=100&id=120525&format=png&color=000000">
                                </a>
                            </div>
                            <h2 class="text-center text-dark fs-5 mb-3">تسجيل الدخول إلى حسابك</h2>
                            <form action="<?php echo htmlspecialchars('/company/controllers/UserControllers/user_login.php')?>" method="POST">
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="username" id="username" placeholder="اسم المستخدم" required>
                                            <label for="username" class="form-label">اسم المستخدم</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="كلمة المرور" required>
                                            <label for="password" class="form-label">كلمة المرور</label>
                                        </div>
                                        <?php
                                        if($_REQUEST['error']){
                                            echo '<p class="text-danger text-center">'.$_REQUEST['error'].'</p>';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex gap-2 justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" name="rememberMe" id="rememberMe">
                                                <label class="form-check-label text-secondary" for="rememberMe">
                                                    إبقني مسجلاً
                                                </label>
                                            </div>
                                            <a href="#" class="link-primary text-decoration-none">هل نسيت كلمة المرور؟</a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit">تسجيل الدخول</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="m-0 text-secondary text-center">ليس لديك حساب؟ <a href="/company/registration.php" class="link-primary text-decoration-none">إنشاء حساب</a></p>
                                    </div>
                                    <div class="form-check d-flex justify-content-center mt-3">
                                        <button class="btn btn-sm border rounded">
                                            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Logo">
                                            <span class="font-monospace text-dark">متابعة باستخدام جوجل</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
