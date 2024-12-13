<?php

declare(strict_types=1);
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function EncryptData($param)
    {
        $param = trim($param);
        $param = htmlspecialchars($param);
        $param = stripslashes($param);
        return $param;
    }
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/company/BranchesPictures/';
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
            header("Location: /company/navigationsbranches/results/addbranchesresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
            exit;
        } else {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $destinationPath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                $image = '/company/BranchesPictures/' . $fileName;
            } else {
                header("Location: /company/navigationsbranches/results/addbranchesresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
                exit;
            }
        }
    } else {
        header("Location: /company/navigationsbranches/results/addbranchesresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
        exit;
    }
    $name = EncryptData($_POST['name'] ?? '');
    $place = EncryptData($_POST['place'] ?? '');
    $branchesStmt = $conn->prepare('INSERT INTO branches (name,place,image) VALUES (?, ?, ?)');
    $branchesStmt->bind_param('sss',$name,$place,$image);
    if ($branchesStmt->execute()) {
        header("Location: /company/navigationsbranches/results/addbranchesresult.php?success=" . urlencode("تم اضافة الفرع بنجاح"));
        exit;
    } else {
        header("Location: /company/navigationsbranches/results/addbranchesresult.php?error=" . urlencode("فشل في اضافة الفرع"));
        exit;
    }
    $branchesStmt->close();
    $conn->close();
}
