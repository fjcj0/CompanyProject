<?php
declare(strict_types=1);
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
                $image = '/company/UsersProfilePictures/' . $fileName;
            } else {
                header("Location: /company/registration.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
                exit;
            }
        }
    } else {
        header("Location: /company/registration.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
        exit;
    }
    $name = EncryptData($_POST['name'] ?? '');
    $username = EncryptData($_POST['username'] ?? '');
    $email = EncryptData($_POST['email'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);
    $confirmationpassword = EncryptData($_POST['password_confirmation'] ?? '');
    if (!password_verify($confirmationpassword, $password)) {
        header("Location: /company/registration.php?error=" . urlencode("كلمة السر غير مطابقة"));
        exit;
    }
    if (checkIfExists($conn, 'username', $username)) {
        header("Location: /company/registration.php?error=" . urlencode("اسم المستخدم او الايميل موجود بالفعل"));
        exit;
    }
    if (checkIfExists($conn, 'email', $email)) {
        header("Location: /company/registration.php?error=" . urlencode("اسم المستخدم او الايميل موجود بالفعل"));
        exit;
    }
    $token = bin2hex(random_bytes(16));
    $created_at = date('Y-m-d H:i:s');
    $insertStmt = $conn->prepare('INSERT INTO users (name, username, email, password, token, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
    if ($insertStmt) {
        $insertStmt->bind_param('sssssss', $name, $username, $email, $password, $token, $image, $created_at);
        if ($insertStmt->execute()) {
            header("Location: /company/navigationsregister/results/registerresult.php?success=1");
            exit;
        } else {
            $errors[] = "Database error: " . $insertStmt->error;
        }
        $insertStmt->close();
    } else {
        $errors[] = "Failed to prepare the SQL statement: " . $conn->error;
    }
    if (!empty($errors)) {
        $queryString = http_build_query(['errors' => $errors]);
        header("Location: /company/registration.php?$queryString");
        exit;
    }
}
$conn->close();
?>
