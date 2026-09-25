<?php
session_start();// Verificar sesión
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

// Los envios son una operacion de oficina/admin
if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
}

include_once '../model/InventarioDB.php';
include_once '../model/Envio.php';
include_once '../model/Devolucion.php';

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

$active = 'envio';
$activeSubmenu = '';

$slotsTienda = Envio::slotsTienda();

// A dónde volver tras una acción: si viene ?volver=<idTienda>, a la ficha de la tienda.
function destinoVuelta()
{
    if (isset($_POST['volver']) && (int)$_POST['volver'] > 0) {
        return 'tienda.php?action=ver&id=' . (int)$_POST['volver'];
    }
    if (isset($_GET['volver']) && (int)$_GET['volver'] > 0) {
        return 'tienda.php?action=ver&id=' . (int)$_GET['volver'];
    }
    return 'envio.php';
}

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

// Crear un envio (preparando)
if ($action == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto']) && isset($_POST['id_tienda'])) {
    $motivo = '';
    if (isset($_POST['motivo'])) {
        $motivo = trim($_POST['motivo']);
    }
    Envio::crear($conexion, (int)$_POST['id_producto'], (int)$_POST['id_tienda'], $motivo, $_SESSION['usuario']);
    $destino = destinoVuelta();
    $conexion = null;
    header('location:' . $destino);
    exit;
}

// Marcar enviado
if ($action == 'enviado' && isset($_GET['id'])) {
    Envio::marcarEnviado($conexion, (int)$_GET['id'], $_SESSION['usuario']);
    $destino = destinoVuelta();
    $conexion = null;
    header('location:' . $destino);
    exit;
}

// Marcar llegado (POST: id, slot_destino, devolver, motivo_devolucion)
if ($action == 'llegado' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $slotDestino = '';
    if (isset($_POST['slot_destino'])) {
        $slotDestino = trim($_POST['slot_destino']);
    }
    $devolver = false;
    if (isset($_POST['devolver']) && $_POST['devolver'] == '1') {
        $devolver = true;
    }
    $motivoDev = '';
    if (isset($_POST['motivo_devolucion'])) {
        $motivoDev = trim($_POST['motivo_devolucion']);
    }
    $resultado = Envio::marcarLlegado($conexion, (int)$_POST['id'], $slotDestino, $devolver, $motivoDev, $_SESSION['usuario']);
    $destino = destinoVuelta();
    if ($resultado == 'ocupado') {
        $sep = '?';
        if (strpos($destino, '?') !== false) { $sep = '&'; }
        $destino = $destino . $sep . 'envio_ocupado=1';
    }
    $conexion = null;
    header('location:' . $destino);
    exit;
}

// Cancelar un envio en curso
if ($action == 'cancelar' && isset($_GET['id'])) {
    Envio::cancelar($conexion, (int)$_GET['id']);
    $destino = destinoVuelta();
    $conexion = null;
    header('location:' . $destino);
    exit;
}

// Listado (panel global). Por defecto solo en curso.
$verTodos = false;
if (isset($_GET['ver']) && $_GET['ver'] == 'todos') {
    $verTodos = true;
}

$envios = Envio::listar($conexion, !$verTodos);
$productosDisponibles = Envio::listarDisponibles($conexion);
$tiendasActivas = Envio::listarTiendasActivas($conexion);

include_once '../view/envio_view.php';
exit;
