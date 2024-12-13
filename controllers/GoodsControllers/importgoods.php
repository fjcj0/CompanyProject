<?php
declare(strict_types=1);
session_start();

$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function EncryptData($param) {
        $param = trim($param);
        $param = htmlspecialchars($param);
        $param = stripslashes($param);
        return $param;
    }

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/company/GoodsPictures/';
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileName = EncryptData($fileName);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileName = uniqid('img_', true) . '.' . $fileExtension;
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileMimeType = mime_content_type($fileTmpPath);

        if (!in_array($fileMimeType, $allowedMimeTypes) || !in_array($fileExtension, $allowedExtensions)) {
            header("Location: /company/navigationsgoods/results/addgoodsresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
            exit;
        } else {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $destinationPath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                $image = '/company/GoodsPictures/' . $fileName;
            } else {
                header("Location: /company/navigationsgoods/results/addgoodsresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
                exit;
            }
        }
    } else {
        header("Location: /company/navigationsgoods/results/addgoodsresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
        exit;
    }
    $name = EncryptData($_POST['name'] ?? '');
    $quantity = EncryptData($_POST['quantity'] ?? '');
    $price = EncryptData($_POST['price'] ?? '');
    $place = EncryptData($_POST['place'] ?? '');
    $created_at = EncryptData($_POST['date'] ?? '');
    $token = $_SESSION['token'] ?? '';
    $stmt = $conn->prepare('SELECT name FROM users WHERE token = ?');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->bind_result($exporter);
    $stmt->fetch();
    $stmt->close();
    $goodsStmt = $conn->prepare('INSERT INTO goods (name, quantity, price, place, image, created_at, exporter) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $goodsStmt->bind_param('sisssss', $name, $quantity, $price, $place, $image, $created_at, $exporter);
    if ($goodsStmt->execute()) {
        header("Location: /company/navigationsgoods/results/addgoodsresult.php?success=" . urlencode("تم إضافة السلعة بنجاح"));
        exit;
    } else {
        header("Location: /company/navigationsgoods/results/addgoodsresult.php?error=" . urlencode("فشل في إضافة السلعة"));
        exit;
    }
    $goodsStmt->close();
}
$conn->close();
?>
