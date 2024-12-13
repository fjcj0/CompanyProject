<?php
declare(strict_types=1);
session_start();
$errors = [];
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
function EncryptData(string $param): string {
    $param = trim($param);
    $param = htmlspecialchars($param);
    $param = stripslashes($param);
    return $param;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = EncryptData($_POST['username'] ?? '');
    $password = EncryptData($_POST['password'] ?? '');
    $stmt = $conn->prepare('SELECT password, token FROM users WHERE username = ?');
    if ($stmt === false) {
        header("Location: /company/login.php?error=" . urlencode("خطأ في تسجيل الدخول"));
        $conn->close();
        exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($storedPassword, $storedToken);
    $stmt->fetch();
    $stmt->close();
    if ($storedPassword) {
        if (password_verify($password, $storedPassword)) {
            $newToken = bin2hex(random_bytes(32));
            $updateStmt = $conn->prepare('UPDATE users SET token = ? WHERE username = ?');
            if ($updateStmt) {
                $updateStmt->bind_param('ss', $newToken, $username);
                $updateStmt->execute();
                $updateStmt->close();
            }
            session_regenerate_id(true);
            $_SESSION['token'] = $newToken;
            $conn->close();
            header("Location: /company/dashboard.php");
            exit;
        } else {
            header("Location: /company/login.php?error=" . urlencode("كلمة السر غير صحيحة"));
            $conn->close();
            exit;
        }
    } else {
        header("Location: /company/login.php?error=" . urlencode("اسم المستخدم غير موجود"));
        $conn->close();
        exit;
    }
}
?>
