<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

include_once '../model/Dashboard.php';

$rol = $_SESSION['rol'];

// Visibilidad del menú
$menuDashboard  = true;
$menuTienda     = true;
$menuOficina    = true;
$menuSoporte    = true;
$menuCerrar     = true;

// Solo ADMIN
$menuAlmacen    = ($rol == 'admin');
$menuSerigrafia = ($rol == 'admin');
$menuStock      = ($rol == 'admin');
$menuUsuarios      = ($rol == 'admin');
$menuConfiguracion = ($rol == 'admin');

$verTareasPendientes = ($rol == 'admin');

$active = 'dashboard';

// Datos del dashboard
$resumenStock        = Dashboard::getResumenStock();
$resumenTrabajadores = Dashboard::getResumenTrabajadores();
$totalTiendas        = Dashboard::getTotalTiendas();
$packsPendientes     = Dashboard::getPacksPendientes();
$recepcionesPendientes = Dashboard::getRecepcionesPendientes();
$ultimasAsignaciones = Dashboard::getUltimasAsignaciones();

include_once '../view/dashboard_view.php';
exit;