<?php
session_start();// Verificar sesión
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

include_once '../model/InventarioDB.php';
include_once '../model/Recepcion.php';

$conexion = InventarioDB::connectDB();

$rol = $_SESSION['rol'];

// Visibilidad del menú
$menuDashboard  = true;
$menuTienda     = true;
$menuOficina    = true;
$menuSoporte    = true;
$menuAlmacen    = ($rol == 'admin');
$menuSerigrafia = ($rol == 'admin');
$menuStock      = ($rol == 'admin');
$menuUsuarios      = ($rol == 'admin');
$menuConfiguracion = ($rol == 'admin');

$active = 'recepcion';
$activeSubmenu = '';

// Acción a realizar
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

// Marcar un item como recibido
if ($action == 'recibir' && isset($_GET['id'])) {
    Recepcion::marcarRecibido($conexion, (int)$_GET['id'], $_SESSION['usuario']);
    $conexion = null;
    header('location:recepcion.php');
    exit;
}

// Marcar un item con incidencia (POST con observaciones)
if ($action == 'incidencia' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $observaciones = '';
    if (isset($_POST['observaciones'])) {
        $observaciones = trim($_POST['observaciones']);
    }
    Recepcion::marcarIncidencia($conexion, (int)$_POST['id'], $_SESSION['usuario'], $observaciones);
    $conexion = null;
    header('location:recepcion.php');
    exit;
}

// Marcar todo un lote como recibido
if ($action == 'recibir_lote' && isset($_GET['lote'])) {
    Recepcion::marcarLoteRecibido($conexion, $_GET['lote'], $_SESSION['usuario']);
    $conexion = null;
    header('location:recepcion.php');
    exit;
}

// Filtro: por defecto solo lotes abiertos (con pendientes o incidencias)
$verTodos = false;
if (isset($_GET['ver']) && $_GET['ver'] == 'todos') {
    $verTodos = true;
}

$lotes = Recepcion::listar($conexion, !$verTodos);

include_once '../view/recepcion_view.php';
exit;
