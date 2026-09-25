<?php
session_start();// Verificar sesión
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

include_once '../model/InventarioDB.php';
include_once '../model/Devolucion.php';
include_once '../model/StockAlta.php';

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

$active = 'devolucion';
$activeSubmenu = '';

// Desenlaces posibles al recibir el material averiado en oficina
$condicionesDesenlace = array(
    'averiado' => 'Averiado',
    'en_garantia' => 'En garantía',
    'en_reparacion' => 'En reparación',
    'para_piezas' => 'Para piezas',
    'desechado' => 'Desechado'
);

// Acción a realizar
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

// Recibir un item: libera a stock y fija la condicion de desenlace
if ($action == 'recibir' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $condicionFinal = 'averiado';
    if (isset($_POST['condicion_final']) && isset($condicionesDesenlace[$_POST['condicion_final']])) {
        $condicionFinal = $_POST['condicion_final'];
    }
    Devolucion::marcarRecibido($conexion, (int)$_POST['id'], $_SESSION['usuario'], $condicionFinal);
    $conexion = null;
    header('location:devolucion.php');
    exit;
}

// Marcar un item con incidencia (POST con observaciones)
if ($action == 'incidencia' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $observaciones = '';
    if (isset($_POST['observaciones'])) {
        $observaciones = trim($_POST['observaciones']);
    }
    Devolucion::marcarIncidencia($conexion, (int)$_POST['id'], $_SESSION['usuario'], $observaciones);
    $conexion = null;
    header('location:devolucion.php');
    exit;
}

// Filtro: por defecto solo devoluciones abiertas (pendientes o con incidencia)
$verTodas = false;
if (isset($_GET['ver']) && $_GET['ver'] == 'todas') {
    $verTodas = true;
}

$devoluciones = Devolucion::listar($conexion, !$verTodas);

include_once '../view/devolucion_view.php';
exit;
