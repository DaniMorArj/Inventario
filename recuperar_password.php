<?php
$token = '';
if (isset($_GET['token']) && $_GET['token'] != '') {
    $token = $_GET['token'];
    header('location:controller/recuperar_password.php?token=' . $token);
} else {
    header('location:controller/recuperar_password.php');//Redigimos al recuperar password del controller
}