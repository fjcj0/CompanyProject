<?php
declare(strict_types=1);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
$mylocalhost = "localhost";
$mypassword = "";
$myusername = "root";
$mydbname = "company";
$conn = new mysqli($mylocalhost, $myusername, $mypassword, $mydbname);
$res=0;
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
function EncryptData($param) {
    $param = trim($param);
    $param = htmlspecialchars($param);
    $param = stripslashes($param);
    return $param;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = EncryptData($_POST['name'] ?? '');
    $quantity = EncryptData($_POST['quantity'] ?? '');
    $created_at = EncryptData($_POST['date'] ?? '');
    $branch_id = EncryptData($_POST['branch_id'] ?? '');
    if (!is_numeric($quantity) || $quantity <= 0) {
        header("Location: /company/navigationsgoods/results/deliverresult.php?error=" . urlencode("Invalid quantity"));
        exit;
    }
    $good_info_query = $conn->prepare("SELECT image, place, quantity, price FROM goods WHERE name = ?");
    $good_info_query->bind_param("s", $name);
    $good_info_query->execute();
    $result = $good_info_query->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $result_good_image = $row['image'];
        $result_place = $row['place'];
        $result_quantity = $row['quantity'];
        $result_price = $row['price'];
        if (!is_numeric($result_price)) {
            header("Location: /company/navigationsgoods/results/deliverresult.php?error=" . urlencode("Invalid price"));
            exit;
        }
        $res = $result_price * $quantity;
        if ($result_quantity - $quantity < 0 || $result_quantity < $quantity) {
            header("Location: /company/navigationsgoods/results/deliverresult.php?error=" . urlencode("Not enough stock"));
            exit;
        }
    } else {
        header("Location: /company/navigationsgoods/results/deliverresult.php?error=" . urlencode("Product not found"));
        exit;
    }
    $insert_query = $conn->prepare("INSERT INTO exports (name, image, quantity, created_at, branch_id, place, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insert_query->bind_param("ssisisd", $name, $result_good_image, $quantity, $created_at, $branch_id, $result_place, $result_price);
    if ($insert_query->execute()) {
        $resquantity = $result_quantity - $quantity;
        $update_quantity_goods = $conn->prepare("UPDATE goods SET quantity = ? WHERE name = ?");
        $update_quantity_goods->bind_param("is", $resquantity, $name);
        $update_quantity_goods->execute();
        $update_quantity_goods->close();
        header("Location: /company/navigationsgoods/results/deliverresult.php?success=" . urlencode("Export successful") . "&branch_id=" . urlencode($branch_id) . "&res=" . urlencode((string)$res));
        exit;
    } else {
        header("Location: /company/navigationsgoods/results/deliverresult.php?error=" . urlencode("Export failed"));
        exit;
    }
}
$conn->close();
?>
