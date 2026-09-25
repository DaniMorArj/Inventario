<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
  header('location:login.php');
  exit;
}

$rol = $_SESSION['rol'];

// Menú Roles
$menuDashboard  = true;
$menuTienda     = true;
$menuOficina    = ($rol == 'admin');
$menuAlmacen    = ($rol == 'admin');
$menuSerigrafia = ($rol == 'admin');
$menuStock      = ($rol == 'admin');
$menuUsuarios      = ($rol == 'admin');
$menuConfiguracion = ($rol == 'admin');
$menuSoporte    = true;

// Conexión a la base de datos
include_once '../model/InventarioDB.php';
$conexion = InventarioDB::connectDB();

require_once '../model/TrabajadorListado.php';
require_once '../model/TrabajadorCrud.php';

$id = 0;
if (isset($_GET['id'])) {
  $id = (int)$_GET['id'];
}

$trabajador = TrabajadorListado::obtenerTrabajador($conexion, $id);

// Cargar el numero real de la SIM si tiene asignada
$simNumero = '';
if (isset($trabajador->id_producto_numero_movil) && $trabajador->id_producto_numero_movil > 0) {
  $idSim = $trabajador->id_producto_numero_movil;
  $consultaSim = $conexion->query("
    SELECT pa.valor AS numero
    FROM producto_atributo pa
    WHERE pa.id_producto = $idSim
    AND pa.id_atributo = 14
    LIMIT 1
  ");
  $simData = $consultaSim->fetchObject();
  if ($simData && isset($simData->numero)) {
    $simNumero = $simData->numero;
  }
}

if (!$trabajador) {
  $conexion = null;
  die("Trabajador no encontrado");
}

$asignaciones = TrabajadorListado::listarAsignacionesTrabajador($conexion, $id);

// histórico combinado (datos + asignaciones)
$historial = TrabajadorCrud::listarHistorial($conexion, $id, 80);

// Menú activo
$active = $trabajador->centro_tipo;

$submenuOficina = false;
$submenuAlmacen = false;
$submenuSerigrafia = false;

if ($active == 'oficina') {
  $submenuOficina = true;
}

if ($active == 'almacen') {
  $submenuAlmacen = true;
}

if ($active == 'serigrafia') {
  $submenuSerigrafia = true;
}
// si quieres marcar submenú activo:
$activeSubmenuOficina = '';
$activeSubmenuAlmacen = '';
$activeSubmenuSerigrafia = '';

if ($submenuOficina) {
  $activeSubmenuOficina = 'alta_trabajador';
}

if ($submenuAlmacen) {
  $activeSubmenuAlmacen = 'alta_trabajador';
}

if ($submenuSerigrafia) {
  $activeSubmenuSerigrafia = 'alta_trabajador';
}

$tituloPagina = 'Detalle trabajador';

include_once '../view/trabajador_detalle_view.php';
$conexion = null;
exit;