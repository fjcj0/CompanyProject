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
function EncryptData($param) {
    $param = trim($param);
    $param = htmlspecialchars($param);
    $param = stripslashes($param);
    return $param;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_id = (int) $_REQUEST['branch_id']; 
    $name = EncryptData($_POST['name'] ?? '');
    $place = EncryptData($_POST['place'] ?? '');
    if (empty($name) || empty($place)) {
        header("Location: /company/navigationsbranches/results/editbranchresult.php?error=" . urlencode("يرجى ملء جميع الحقول"));
        exit;
    }
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
            header("Location: /company/navigationsbranches/results/editbranchresult.php?error=" . urlencode("فشل في تحميل الصورة ربما ليست صورة"));
            exit;
        } else {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/company/BranchesPictures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destinationPath = $uploadDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                $image = '/company/BranchesPictures/' . $fileName;
            } else {
                header("Location: /company/navigationsbranches/results/editbranchresult.php?error=" . urlencode("فشل في تحميل الصورة"));
                exit;
            }
        }
    }
    if ($image !== '') {
        $update_branch_query = "UPDATE branches SET name = ?, place = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($update_branch_query);
        $stmt->bind_param('sssi', $name, $place, $image, $branch_id);
    } else {
        $update_branch_query = "UPDATE branches SET name = ?, place = ? WHERE id = ?";
        $stmt = $conn->prepare($update_branch_query);
        $stmt->bind_param('ssi', $name, $place, $branch_id);
    }
    if ($stmt->execute()) {
        header("Location: /company/navigationsbranches/results/editbranchresult.php?success=" . urlencode("تمت العملية بنجاح"));
        exit;
    } else {
        header("Location: /company/navigationsbranches/results/editbranchresult.php?error=" . urlencode("فشل في التحديث"));
        exit;
    }
}
$conn->close();
?>
