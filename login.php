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
$languages = [
    "en" => [
        "SIGN_IN" => "SIGN IN TO YOUR ACCOUNT",
        "USERNAME" => "username",
        "PASSWORD" => "password",
        "SAVE_LOGIN" => "save login",
        "FOREGT_PASS" => "forget password?",
        "SIGN_IN_BUTTON" => "SIGN IN",
        "DONT_HAVE" => "dont have account? ",
        "CREATE_ACCOUNT" => "create account",
        "GOOGLE" => "SIGN IN USING GOOGLE",
        "LOGOUT" => "LOGOUT"
    ],
    "ar" => [
        "SIGN_IN" => "تسجيل الدخول الى حسابك",
        "USERNAME" => "اسم المستخدم",
        "PASSWORD" => "كلمة السر",
        "SAVE_LOGIN" => "ابقني مسجلا",
        "FOREGT_PASS" => "نسية كلمة المرور؟",
        "SIGN_IN_BUTTON" => "تسجيل الدخول",
        "DONT_HAVE" => "ليس لديك حسابك؟ ",
        "CREATE_ACCOUNT" => "انشاء حساب",
        "GOOGLE" => "تسجيل الدخول باستخدام جوجل",
        "LOGOUT" => "تسجيل الخروج"
    ],
];
$en = $languages['en'];
$ar = $languages['ar'];
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
    <title><?php
            if ($_SESSION['language'] == 'en') {
                echo "Sign In";
            } else {
                echo "تسجيل الدخول";
            }
            ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="icon" href="https://img.icons8.com/?size=100&id=13042&format=png&color=000000">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cairo:wght@200..1000&family=Changa:wght@200..800&family=El+Messiri:wght@400..700&family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&family=Lalezar&family=Rakkas&family=Readex+Pro:wght@160..700&family=Rubik:ital,wght@0,300..900;1,300..900&family=Scheherazade+New:wght@400;500;600;700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Carter+One&family=Caveat+Brush&family=Cherry+Cream+Soda&family=Fredericka+the+Great&family=Hachi+Maru+Pop&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Karla:ital,wght@0,200..800;1,200..800&family=Lacquer&family=Luckiest+Guy&family=Matemasie&family=Parkinsans:wght@300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Protest+Revolution&family=Quicksand:wght@300..700&family=Rammetto+One&family=Signika+Negative:wght@300..700&family=Sriracha&family=Yuji+Mai&display=swap');

        * {
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
            box-sizing: border-box;
        }

        html[dir="ltr"] *:not(i) {
            font-family: "Josefin Sans", sans-serif;
        }

        html[dir="rtl"] *:not(i) {
            font-family: "Changa", sans-serif;
        }

        h1,
        h3 {
            font-family: "Cairo", sans-serif;
            font-style: normal;
        }

        html[dir="ltr"] h1,
        html[dir="ltr"] h3 {
            font-family: "Signika Negative", sans-serif;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php

    ?>
    <!--HEADER-->
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
                            <form action="/company/controllers/TrasnlationControllers/translation.php" method="POST" enctype="multipart/form-data">
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
    <!--END HEADER-->
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
                            <h2 class="text-center text-dark fs-5 mb-3">
                                <?php
                                if ($_SESSION['language'] == 'en') {
                                    echo $en['SIGN_IN'];
                                } else {
                                    echo $ar['SIGN_IN'];
                                }
                                ?>
                            </h2>
                            <form action="<?php echo htmlspecialchars('/company/controllers/UserControllers/user_login.php') ?>" method="POST" enctype="multipart/form-data">
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="username" id="username" placeholder="<?php echo ($_SESSION['language'] == 'en') ? 'Username' : 'اسم المستخدم'; ?>" required>
                                            <label for="username" class="form-label"><?php echo ($_SESSION['language'] == 'en') ? 'Username' : 'اسم المستخدم'; ?></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password" value="" placeholder="<?php echo ($_SESSION['language'] == 'en') ? 'Password' : 'كلمة المرور'; ?>" required>
                                            <label for="password" class="form-label"><?php echo ($_SESSION['language'] == 'en') ? 'Password' : 'كلمة المرور'; ?></label>
                                        </div>
                                        <?php
                                        if ($_REQUEST['error']) {
                                            echo '<p class="text-danger text-center">' . $_REQUEST['error'] . '</p>';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex gap-2 justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" name="rememberMe" id="rememberMe">
                                                <label class="form-check-label text-secondary" for="rememberMe">
                                                    <?php echo ($_SESSION['language'] == 'en') ? 'Keep me signed in' : 'ابقني مسجلاً'; ?>
                                                </label>
                                            </div>
                                            <a href="#" class="link-primary text-decoration-none"><?php echo ($_SESSION['language'] == 'en') ? 'Forgot password?' : 'هل نسيت كلمة المرور؟'; ?></a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-lg" type="submit"><?php echo ($_SESSION['language'] == 'en') ? 'Sign In' : 'تسجيل الدخول'; ?></button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <p class="m-0 text-secondary text-center"><?php echo ($_SESSION['language'] == 'en') ? "Don't have an account? " : "ليس لديك حساب؟ "; ?><a href="/company/registration.php" class="link-primary text-decoration-none"><?php echo ($_SESSION['language'] == 'en') ? 'Create account' : 'إنشاء حساب'; ?></a></p>
                                    </div>
                                    <div class="form-check d-flex justify-content-center mt-3">
                                        <button class="btn btn-sm border rounded">
                                            <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google Logo">
                                            <span class="font-monospace text-dark"><?php echo ($_SESSION['language'] == 'en') ? 'Sign in with Google' : 'متابعة باستخدام جوجل'; ?></span>
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