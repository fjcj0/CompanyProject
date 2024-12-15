<?php 
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $mode = $_POST['mode'];
    if($mode == 0){
        header('Location: /company/dashboard.php');
        exit;
    }
    if($mode == 'dark'){
        $_SESSION['mode'] = 'dark';
    } else {
        $_SESSION['mode'] = 'light';
    }
}
header('Location: /company/dashboard.php');
exit;
?>
