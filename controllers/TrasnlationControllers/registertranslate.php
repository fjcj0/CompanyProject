<?php 
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_SESSION['language'] = $_POST['language'];
}
header('Location: /company/registration.php');
exit;
?>