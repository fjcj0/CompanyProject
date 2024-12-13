<?php
declare(strict_types=1);

// Database connection
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die('Connection failed: ' . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['branch_id']) || !filter_var($_POST['branch_id'], FILTER_VALIDATE_INT)) {
        header("Location: /company/navigationsbranches/results/deletebranchresult.php?error=" . urlencode("فشل في حذف الفرع"));
        exit;
    }
    $branch_id = (int)$_POST['branch_id'];
    if ($stmt = $conn->prepare("DELETE FROM branches WHERE id = ?")) {
        $stmt->bind_param("i", $branch_id);
        if ($stmt->execute()) {
            header("Location: /company/navigationsbranches/results/deletebranchresult.php?success=" . urlencode("تمت العملية بنجاح"));
            exit;
        } else {
            header("Location: /company/navigationsbranches/results/deletebranchresult.php?error=" . urlencode("فشل في حذف الفرع"));
            exit;
        }
        $stmt->close();
    } else {
        header("Location: /company/navigationsbranches/results/deletebranchresult.php?error=" . urlencode("فشل في حذف الفرع"));
        exit;
    }
}
$conn->close();
?>
