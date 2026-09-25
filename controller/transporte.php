<?php
session_start();// Verificar sesión
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

include_once '../model/InventarioDB.php';
include_once '../model/Envio.php';
include_once '../model/Recepcion.php';
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

$active = 'transporte';
$activeSubmenu = '';

// Contadores
$enviosEnCurso = Envio::contarEnCurso($conexion);
$recepcionPendientes = Recepcion::contarPendientes($conexion);
$devolucionesPendientes = Devolucion::contarPendientes($conexion);

// Listas de lo que hay abierto en cada flujo
$enviosLista = Envio::listar($conexion, true);           // en curso (preparando/enviado)
$recepcionesLotes = Recepcion::listar($conexion, true);  // lotes con pendientes/incidencias
$devolucionesLista = Devolucion::listar($conexion, true);// pendientes/incidencias

include_once '../view/transporte_view.php';
exit;
