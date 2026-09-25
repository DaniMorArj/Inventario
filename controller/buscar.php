<?php
session_start();// Verificar si el usuario ha iniciado sesión
require_once __DIR__ . '/../lib/AccessGate.php';

// Si no hay sesión, redirigir a login
if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

include_once '../model/Buscador.php';// Llamamos al modelo para usar sus métodos

$rol = $_SESSION['rol'];// Obtener el rol del usuario para mostrar el menú adecuado

// Visibilidad del menú
$menuDashboard  = true;// Todos ven el Dashboard
$menuTienda     = true;
$menuOficina    = true;
$menuSoporte    = true;
$menuAlmacen    = ($rol == 'admin');// Solo admin ve Almacén
$menuSerigrafia = ($rol == 'admin');
$menuStock      = ($rol == 'admin');
$menuUsuarios      = ($rol == 'admin');
$menuConfiguracion = ($rol == 'admin');

$active = '';

// Submenú Buscar
$texto = '';
if (isset($_GET['q']) && $_GET['q'] != '') {
    $texto = $_GET['q'];
}

// Variables para la vista (para mostrar el menú activo)
$trabajadores = array();
$tiendas      = array();
$productos    = array();

// Solo buscamos si hay texto
if ($texto != '') {
    $trabajadores = Buscador::buscarTrabajadores($texto);
    $tiendas      = Buscador::buscarTiendas($texto);
    $productos    = Buscador::buscarProductos($texto);
}

include_once '../view/buscar_view.php';
exit;