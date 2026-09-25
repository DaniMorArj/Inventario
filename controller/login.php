<?php
session_start(); // Iniciamos Sesion
require_once __DIR__ . '/../lib/AccessGate.php';

include_once '../model/Usuario.php'; // Incluimos el modelo usuario

// Comprobamos si la respuesta es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Si los campos estan vacios mostramos el mensaje
    if ($email == "" || $password == "") {
        $error = "Rellena email y contraseña";
        include_once '../view/login_view.php';
        exit;
    }

    //Comprobamos las credenciales
    $usuario = Usuario::getUsuarioByEmailPass($email, $password);

    // Si el usuario es correcto
    if ($usuario != false) {

        // Guardamos en sesión
        $_SESSION['usuario'] = $usuario->getEmail();
        $_SESSION['rol'] = $usuario->getRol();
        $_SESSION['departamento'] = $usuario->getDepartamento();

        // Entrar al dashboard
        header('location:index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
        include_once '../view/login_view.php';
        exit;
    }
} else {
    // GET: mostrar el formulario
    include_once '../view/login_view.php';
}
