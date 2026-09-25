<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
  header('location:login.php');
  exit;
}

$rol = $_SESSION['rol'];

// Menú Roles
$menuDashboard     = true;
$menuTienda        = true;
$menuOficina       = ($rol == 'admin');
$menuAlmacen       = ($rol == 'admin');
$menuSerigrafia    = ($rol == 'admin');
$menuStock         = ($rol == 'admin');
$menuUsuarios      = ($rol == 'admin');
$menuConfiguracion = ($rol == 'admin');
$menuSoporte       = true;

$active = 'almacen';

// Submenú Almacen
$submenuAlmacen = true;
$activeSubmenuAlmacen = ''; // ejemplo: 'alta_trabajador'

// (Opcional) por compatibilidad si tu vista espera estas variables:
$submenuStock = false;
$activeSubmenu = '';

// Conexión BD
include_once '../model/InventarioDB.php';
$conexion = InventarioDB::connectDB();

require_once '../model/TrabajadorListado.php';//Llamamos al modelo para usar sus métodos

//Variables para la vista
$tipoCentro = 'almacen';
$tituloPagina = 'Almacén';

//Filtros de búsqueda
$buscar = '';
if (isset($_GET['buscar'])) {
  $buscar = trim($_GET['buscar']);
}

// Filtro por departamento
$idDepto = 0;
if (isset($_GET['depto'])) {
  $idDepto = (int)$_GET['depto'];
}

// Filtro de orden
$orden = '';
if (isset($_GET['orden'])) {
  $orden = trim($_GET['orden']);
}

//Paginacion
$porPagina = 8;
$pagina = 1;

if (isset($_GET['pagina'])) {
  $pagina = (int)$_GET['pagina'];
}

if ($pagina < 1) {
  $pagina = 1;
}

$inicio = ($pagina - 1) * $porPagina;

// Contar total de filas para calcular paginación
// Ver activos (por defecto) o dados de baja
$estadoFiltro = 'ACTIVO';
$verBajas = false;
if (isset($_GET['ver']) && $_GET['ver'] == 'bajas') {
  $estadoFiltro = 'BAJA';
  $verBajas = true;
}

$totalFilas = TrabajadorListado::contarTrabajadores($conexion, $tipoCentro, $buscar, $idDepto, $estadoFiltro);
$totalPaginas = (int)ceil($totalFilas / $porPagina);

if ($totalPaginas < 1) {
  $totalPaginas = 1;
}

if ($pagina > $totalPaginas) {
  $pagina = $totalPaginas;
}

$departamentos = TrabajadorListado::listarDepartamentos($conexion);
$trabajadores = TrabajadorListado::listarTrabajadores($conexion, $tipoCentro, $buscar, $idDepto, $orden, $inicio, $porPagina, $estadoFiltro);

include_once '../view/centro_trabajadores_view.php';
$conexion = null;// Cerrar conexión
exit;