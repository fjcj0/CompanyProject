<?php
declare(strict_types=1);
session_start();
$token = $_SESSION['token'];
$errors = [];
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function EncryptData($param) {
        $param = trim($param);
        $param = htmlspecialchars($param);
        $param = stripslashes($param);
        return $param;
    }
    function checkIfExists($conn, $column, $value) {
        $query = "SELECT COUNT(*) FROM users WHERE $column = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count > 0;
    }
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/company/UsersProfilePictures/';
    $image = '';
    $noimage = true;
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
            header("Location: /company/registration.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
            exit;
        } else {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $destinationPath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                $noimage = true;
                $image = '/company/UsersProfilePictures/' . $fileName;
            } else {
                header("Location: /company/navigationsregister/results/edituserresult.php?error=" . urlencode("فشل في تحميل الصورة"));
                exit;
            }
        }
    }else{
        $noimage = false;
    }
    if ($noimage==false) {
        $image = $_POST['current_image'] ?? '';
    }
    $name = EncryptData($_POST['name'] ?? '');
    $username = EncryptData($_POST['username'] ?? '');
    $email = EncryptData($_POST['email'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);
    $confirmationpassword = EncryptData($_POST['password_confirmation'] ?? '');
    if (!password_verify($confirmationpassword, $password)) {
        header("Location: /company/navigationsregister/results/edituserresult.php?error=" . urlencode("كلمة السر غير مطابقة"));
        exit;
    }
    if (checkIfExists($conn, 'username', $username)) {
        header("Location: /company/navigationsregister/results/edituserresult.php?error=" . urlencode("اسم المستخدم موجود بالفعل"));
        exit;
    }
    if (checkIfExists($conn, 'email', $email)) {
        header("Location: /company/navigationsregister/results/edituserresult.php?error=" . urlencode("الإيميل موجود بالفعل"));
        exit;
    }
    $updated_at = date('Y-m-d H:i:s');
    if ($image !== '') {
        $updateStmt = $conn->prepare('UPDATE users SET name = ?, username = ?, email = ?, password = ?, image = ?, token = ?, updated_at = ? WHERE token = ?');
        $newtoken = bin2hex(random_bytes(16));
        $updateStmt->bind_param('ssssssss', $name, $username, $email, $password, $image, $newtoken, $updated_at, $token);
        $_SESSION['token'] = $newtoken;
    } else {
        $updateStmt = $conn->prepare('UPDATE users SET name = ?, username = ?, email = ?, password = ?, token = ?, updated_at = ? WHERE token = ?');
        $newtoken = bin2hex(random_bytes(16));
        $updateStmt->bind_param('sssssss', $name, $username, $email, $password,$newtoken , $updated_at, $token);
        $_SESSION['token'] = $newtoken;
    }
    if ($updateStmt->execute()) {
        header("Location: /company/navigationsregister/results/edituserresult.php?success=1");
        $updateStmt->close();
        exit;
    } else {
        header("Location: /company/navigationsregister/results/edituserresult.php?error=" . urlencode("فشل في تحديث البيانات"));
        $updateStmt->close();
        exit;
    }
}
$conn->close();
?>