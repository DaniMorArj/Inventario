<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

// Solo admin gestiona packs de apertura
if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
}

include_once '../model/InventarioDB.php';
include_once '../model/Pack.php';
include_once '../model/TiendaAlta.php';

$conexion = InventarioDB::connectDB();

$rol = $_SESSION['rol'];
$menuDashboard  = true;
$menuTienda     = true;
$menuOficina    = true;
$menuSoporte    = true;
$menuAlmacen    = ($rol == 'admin');
$menuSerigrafia = ($rol == 'admin');
$menuStock      = ($rol == 'admin');
$menuUsuarios      = ($rol == 'admin');
$menuConfiguracion = ($rol == 'admin');

$active = 'pack';
$activeSubmenu = '';

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$errores = array();

// ---- Crear pack (crea la tienda en apertura y el pack) ----
if ($action == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero = '';
    $nombre = '';
    $idSociedad = '';
    if (isset($_POST['numero'])) { $numero = trim($_POST['numero']); }
    if (isset($_POST['nombre'])) { $nombre = trim($_POST['nombre']); }
    if (isset($_POST['sociedad'])) { $idSociedad = trim($_POST['sociedad']); }

    if ($numero == '') { $errores[] = 'El número de tienda es obligatorio.'; }
    if ($nombre == '') { $errores[] = 'El nombre de la tienda es obligatorio.'; }
    if ($idSociedad == '') { $errores[] = 'Debes seleccionar una sociedad.'; }

    // Evitar tiendas duplicadas: comprobar si ya existe una con ese número.
    $idTienda = 0;
    if (count($errores) == 0) {
        $existente = Pack::tiendaPorNumero($conexion, (int)$numero);
        if ($existente) {
            if ($existente->estado == 'en_apertura' && Pack::tienePackVigente($conexion, $existente->id) == false) {
                // Tienda en apertura libre: la reutilizamos
                $idTienda = $existente->id;
            } else {
                $errores[] = 'Ya existe una tienda con el número ' . $numero . '.';
            }
        } else {
            TiendaAlta::insertar($conexion, (int)$numero, $nombre, $idSociedad, '', '', '', 'Tienda en apertura');
            $idTienda = $conexion->lastInsertId();
            $conexion->prepare("UPDATE tienda SET estado = 'en_apertura' WHERE id = ?")->execute(array($idTienda));
        }
    }

    if (count($errores) == 0) {
        $idPack = Pack::crearPack($conexion, $idTienda, $_SESSION['usuario']);
        $conexion = null;
        header('location:pack.php?action=ver&id=' . $idPack);
        exit;
    }
}

// ---- Añadir producto al pack ----
if ($action == 'anadir' && isset($_GET['pack']) && isset($_GET['producto'])) {
    Pack::anadirProducto($conexion, (int)$_GET['pack'], (int)$_GET['producto']);
    $conexion = null;
    header('location:pack.php?action=ver&id=' . (int)$_GET['pack']);
    exit;
}

// ---- Quitar producto del pack ----
if ($action == 'quitar' && isset($_GET['pack']) && isset($_GET['producto'])) {
    Pack::quitarProducto($conexion, (int)$_GET['pack'], (int)$_GET['producto']);
    $conexion = null;
    header('location:pack.php?action=ver&id=' . (int)$_GET['pack']);
    exit;
}

// ---- Marcar enviado (solo si completo) ----
if ($action == 'enviar' && isset($_GET['id'])) {
    $idPack = (int)$_GET['id'];
    $chk = Pack::checklist($conexion, $idPack);
    if ($chk['completo']) {
        Pack::cambiarEstado($conexion, $idPack, 'enviado');
    }
    $conexion = null;
    header('location:pack.php?action=ver&id=' . $idPack);
    exit;
}

// ---- Abrir tienda (materializar el pack) ----
if ($action == 'abrir' && isset($_GET['id'])) {
    Pack::abrirTienda($conexion, (int)$_GET['id']);
    $conexion = null;
    header('location:pack.php?action=ver&id=' . (int)$_GET['id']);
    exit;
}

// ---- Cancelar pack (libera el material) ----
if ($action == 'cancelar' && isset($_GET['id'])) {
    Pack::cancelar($conexion, (int)$_GET['id']);
    $conexion = null;
    header('location:pack.php');
    exit;
}

// ---- Detalle ----
if ($action == 'ver' && isset($_GET['id'])) {
    $modo = 'detalle';
    $pack = Pack::obtener($conexion, (int)$_GET['id']);
    $checklist = Pack::checklist($conexion, (int)$_GET['id']);
    $lineas = Pack::lineas($conexion, (int)$_GET['id']);

    // Productos disponibles para cada linea del checklist (para el selector de añadir)
    $disponiblesPorLinea = array();
    foreach ($checklist['lineas'] as $indice => $ln) {
        $disponiblesPorLinea[$indice] = Pack::disponiblesPara($conexion, $ln['id_categoria'], $ln['subtipo']);
    }

    include_once '../view/pack_view.php';
    exit;
}

// ---- Listado (por defecto) ----
$modo = 'lista';
$packs = Pack::listar($conexion);
$sociedades = TiendaAlta::listarSociedades($conexion);

include_once '../view/pack_view.php';
exit;
