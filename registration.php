<?php
session_start();
if ($_SESSION['token']) {
    header('Location: /company/dashboard.php');
    exit;
}
function CheckLogin(): bool
{
    return isset($_SESSION['token']);
}
?>
<!DOCTYPE html>
<html lang="<?php
            if ($_SESSION['language'] == 'en') {
                echo "en";
            } else {
                echo "ar";
            }
            ?>" dir="<?php
                        if ($_SESSION['language'] == 'en') {
                            echo "ltr";
                        } else {
                            echo "rtl";
                        }
                        ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حساب جديد</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="icon" href="https://img.icons8.com/?size=100&id=23279&format=png&color=000000">
    <style>
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

        body {
            min-height: 100vh;
        }

        html[dir="rtl"] .form-check {

            display: flex;
            align-items: center;
        }

        html[dir="ltr"] .form-check {
            display: flex;
            align-items: center;
        }

        html[dir="ltr"] .form-check-input {
            margin-left: 10px;
        }

        html[dir="rtl"] .form-check-input {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <header class="py-3 bg-dark mx-auto mt-3 rounded-5" style="width: 90% ;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="dropdown">
                    <a href="#" class="d-block text-white text-decoration-none dropdown-toggle"
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
                            <form action="/company/controllers/TrasnlationControllers/registertranslate.php" method="POST" enctype="multipart/form-data">
                                <button type="submit" name="language" value="en" class="dropdown-item">
                                    <?php echo ($_SESSION['language'] == 'en' ? 'English' : 'الانجليزية'); ?>
                                </button>
                                <button type="submit" name="language" value="ar" class="dropdown-item">
                                    <?php echo ($_SESSION['language'] == 'en' ? 'Arabic' : 'العربية'); ?>
                                </button>
                            </form>


                        </li>
                    </ul>
                </div>
                <div>
                    <?php
                    if (!CheckLogin()) {
                        echo '
        <a href="/company/login.php" class="btn btn-outline-light me-2 mx-2">' .
                            ($_SESSION['language'] == 'en' ? 'Sign In' : 'تسجيل الدخول') .
                            '</a>
        <a href="/company/registration.php" class="btn btn-warning mx-2">' . ($_SESSION['language'] == 'en' ? 'Sign Up' : 'انشاء حساب') . '</a>';
                    }
                    ?>

                    <img class="rounded-circle" style="width:3rem; display:inline-block;" src="https://scontent.fjrs29-1.fna.fbcdn.net/v/t1.6435-9/53160215_983514671859230_3418556035017736192_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=a5f93a&_nc_ohc=M8loIWrGuHUQ7kNvgFco5sE&_nc_zt=23&_nc_ht=scontent.fjrs29-1.fna&_nc_gid=AS40DYteTKcwXxaBaSHwqg2&oh=00_AYD11IxWIAN2iPhdeN1bJ5fGeYkjkka6ursmSlbxOF5zbg&oe=6783B60E" alt="">
                </div>
            </div>
        </div>
    </header>
    <section class="">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11 my-5">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4"> <?php echo ($_SESSION['language'] == 'en' ? 'Create Account' : 'انشاء حساب'); ?></p>
                                    <form action="<?php echo htmlspecialchars('/company/controllers/UserControllers/user_register.php') ?>" method="POST" class="form-sign-in" enctype="multipart/form-data">
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fa-regular fa-id-badge me-3 fa-fw mx-2"
                                                style="position:relative; top:0.8rem;"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="name"> <?php echo ($_SESSION['language'] == 'en' ? 'name' : 'الاسم'); ?></label>
                                                <input type="text" name="name" id="name" class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw mx-2"
                                                style="position:relative; top:0.8rem;"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="username"> <?php echo ($_SESSION['language'] == 'en' ? 'username' : 'اسم المستخدم'); ?></label>
                                                <input type="text" name="username" id="username" class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw mx-2"
                                                style="position:relative; top:0.8rem;"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="email"> <?php echo ($_SESSION['language'] == 'en' ? 'email' : 'البريد الالكتروني'); ?></label>
                                                <input type="email" name="email" id="email" class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw mx-2"
                                                style="position:relative; top:0.8rem;"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="password"> <?php echo ($_SESSION['language'] == 'en' ? 'password' : 'كلمة المرور'); ?></label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-key fa-lg me-3 fa-fw mx-2"
                                                style="position:relative; top:0.8rem;"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="password_confirmation"> <?php echo ($_SESSION['language'] == 'en' ? 'confirmation password' : 'تاكيد كلمة المرور'); ?>
                                                </label>
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw mx-2"
                                                style="position:relative; top:0.8rem;"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <label class="form-label" for="image"> <?php echo ($_SESSION['language'] == 'en' ? 'profile picture' : 'صورة الملف الشخصي'); ?></label>
                                                <input type="file" name="image" id="image"
                                                    class="form-control" required />
                                            </div>
                                        </div>
                                        <div class="form-check d-flex justify-content-center mb-5">
                                            <label class="form-check-label" for="term">
                                                <?php echo ($_SESSION['language'] == 'en' ? 'I accept all terms' : 'اواف على شروط الخدمة'); ?>
                                                <a href="#"> <?php echo ($_SESSION['language'] == 'en' ? 'terms' : 'شروط الخدمة'); ?></a>
                                            </label>
                                            <input class="form-check-input me-2" type="checkbox" name="term" id="term" required />
                                        </div>

                                        <?php
                                        if ($_REQUEST['error']) {
                                            echo '<p class="text-danger text-center">' . $_REQUEST['error'] . '</p>';
                                        }
                                        ?>
                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button type="submit" class="btn btn-dark btn-lg"
                                                style="font-weight: bolder;">إنشاء حساب</button>
                                        </div>
                                        <div class="form-check d-flex justify-content-center">
                                            <button class="btn btn-sm border rounded">
                                                <img src="https://img.icons8.com/color/48/000000/google-logo.png"
                                                    alt="Google Logo">
                                                <span class="font-monospace text-dark">التسجيل باستخدام Google</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div
                                    class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2 d-none">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp"
                                        class="img-fluid" alt="صورة نموذجية">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>