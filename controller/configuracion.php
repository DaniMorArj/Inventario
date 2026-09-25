<?php
session_start();// Verificar si el usuario ha iniciado sesión
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

// Solo admin puede acceder
if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
}

include_once '../model/Configuracion.php';

$rol = $_SESSION['rol'];

$menuDashboard  = true;
$menuTienda     = true;
$menuOficina    = true;
$menuSoporte    = true;
$menuAlmacen    = true;
$menuSerigrafia = true;
$menuStock      = true;
$menuUsuarios   = true;
$menuConfiguracion = true;

$active = 'configuracion';

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$seccion = 'sociedades';
if (isset($_GET['seccion'])) {
    $seccion = $_GET['seccion'];
}

$errores = array();
$ok = '';

// ============================================================
// SOCIEDADES
// ============================================================

// Alta sociedad
if ($action == 'nueva_sociedad' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = '';
    $cif = '';
    if (isset($_POST['nombre'])) { $nombre = $_POST['nombre']; }// Asegurarse de que el nombre no es solo espacios
    if (isset($_POST['cif'])) { $cif = $_POST['cif']; }

    if ($nombre == '') { $errores[] = 'El nombre es obligatorio.'; }
    if ($cif == '') { $errores[] = 'El CIF es obligatorio.'; }

    if (!isset($errores) || count($errores) == 0) {
        Configuracion::insertarSociedad($nombre, $cif);
        header('location:configuracion.php?seccion=sociedades&ok=sociedad_creada');
        exit;
    }
    $seccion = 'sociedades';
}

// Editar sociedad
if ($action == 'editar_sociedad' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = 0;
    $nombre = '';
    $cif = '';
    if (isset($_POST['id'])) { $id = $_POST['id']; }// Asegurarse de que el ID es un número entero
    if (isset($_POST['nombre'])) { $nombre = $_POST['nombre']; }
    if (isset($_POST['cif'])) { $cif = $_POST['cif']; }

    if ($nombre == '') { $errores[] = 'El nombre es obligatorio.'; }
    if ($cif == '') { $errores[] = 'El CIF es obligatorio.'; }

    if (!isset($errores) || count($errores) == 0) {
        Configuracion::actualizarSociedad($id, $nombre, $cif);
        header('location:configuracion.php?seccion=sociedades&ok=sociedad_editada');
        exit;
    }
    $seccion = 'sociedades';
}

// Eliminar sociedad
if ($action == 'eliminar_sociedad' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = Configuracion::eliminarSociedad($id);
    if ($resultado == false) {
        header('location:configuracion.php?seccion=sociedades&error=sociedad_con_tiendas');
    } else {
        header('location:configuracion.php?seccion=sociedades&ok=sociedad_eliminada');
    }
    exit;
}

// ============================================================
// DEPARTAMENTOS
// ============================================================

// Alta departamento
if ($action == 'nuevo_departamento' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = '';
    if (isset($_POST['nombre'])) { $nombre = $_POST['nombre']; }

    if ($nombre == '') { $errores[] = 'El nombre es obligatorio.'; }

    if (!isset($errores) || count($errores) == 0) {
        Configuracion::insertarDepartamento($nombre);
        header('location:configuracion.php?seccion=departamentos&ok=departamento_creado');
        exit;
    }
    $seccion = 'departamentos';
}

// Editar departamento
if ($action == 'editar_departamento' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = 0;
    $nombre = '';
    if (isset($_POST['id'])) { $id = $_POST['id']; }
    if (isset($_POST['nombre'])) { $nombre = $_POST['nombre']; }

    if ($nombre == '') { $errores[] = 'El nombre es obligatorio.'; }

    if (!isset($errores) || count($errores) == 0) {
        Configuracion::actualizarDepartamento($id, $nombre);
        header('location:configuracion.php?seccion=departamentos&ok=departamento_editado');
        exit;
    }
    $seccion = 'departamentos';
}

// Eliminar departamento
if ($action == 'eliminar_departamento' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = Configuracion::eliminarDepartamento($id);
    if ($resultado == false) {
        header('location:configuracion.php?seccion=departamentos&error=departamento_con_trabajadores');
    } else {
        header('location:configuracion.php?seccion=departamentos&ok=departamento_eliminado');
    }
    exit;
}

// ============================================================
// PLANTILLA DE PACKS
// ============================================================

if ($action == 'nueva_plantilla' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $idCategoria = 0;
    $subtipo = '';
    $cantidad = 1;
    if (isset($_POST['id_categoria'])) { $idCategoria = (int)$_POST['id_categoria']; }
    if (isset($_POST['subtipo'])) { $subtipo = trim($_POST['subtipo']); }
    if (isset($_POST['cantidad'])) { $cantidad = (int)$_POST['cantidad']; }

    if ($idCategoria == 0) { $errores[] = 'Debes seleccionar una categoría.'; }
    if ($cantidad < 1) { $cantidad = 1; }

    if (count($errores) == 0) {
        Configuracion::insertarPlantillaLinea($idCategoria, $subtipo, $cantidad);
        header('location:configuracion.php?seccion=plantilla&ok=plantilla_creada');
        exit;
    }
}

if ($action == 'editar_plantilla' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = 0;
    $idCategoria = 0;
    $subtipo = '';
    $cantidad = 1;
    if (isset($_POST['id'])) { $id = (int)$_POST['id']; }
    if (isset($_POST['id_categoria'])) { $idCategoria = (int)$_POST['id_categoria']; }
    if (isset($_POST['subtipo'])) { $subtipo = trim($_POST['subtipo']); }
    if (isset($_POST['cantidad'])) { $cantidad = (int)$_POST['cantidad']; }

    if ($idCategoria == 0) { $errores[] = 'Debes seleccionar una categoría.'; }
    if ($cantidad < 1) { $cantidad = 1; }

    if (count($errores) == 0) {
        Configuracion::actualizarPlantillaLinea($id, $idCategoria, $subtipo, $cantidad);
        header('location:configuracion.php?seccion=plantilla&ok=plantilla_editada');
        exit;
    }
}

if ($action == 'eliminar_plantilla' && isset($_GET['id'])) {
    Configuracion::eliminarPlantillaLinea((int)$_GET['id']);
    header('location:configuracion.php?seccion=plantilla&ok=plantilla_eliminada');
    exit;
}

// ============================================================
// CARGAR DATOS PARA LA VISTA
// ============================================================

$sociedades    = Configuracion::listarSociedades();
$departamentos = Configuracion::listarDepartamentos();
$plantillaPack = Configuracion::listarPlantillaPack();
$categorias    = Configuracion::listarCategorias();

// Línea de plantilla a editar
$plantillaEditar = null;
if ($action == 'editar_plantilla' && isset($_GET['id'])) {
    $plantillaEditar = Configuracion::obtenerPlantillaLinea($_GET['id']);
}

// Sociedad a editar
$sociedadEditar = null;
if ($action == 'editar_sociedad' && isset($_GET['id'])) {
    $sociedadEditar = Configuracion::obtenerSociedad($_GET['id']);
}

// Departamento a editar
$departamentoEditar = null;
if ($action == 'editar_departamento' && isset($_GET['id'])) {
    $departamentoEditar = Configuracion::obtenerDepartamento($_GET['id']);
}

// Mensajes ok/error desde GET
if (isset($_GET['ok'])) { $ok = $_GET['ok']; }
$errorGet = '';
if (isset($_GET['error'])) { $errorGet = $_GET['error']; }

include_once '../view/configuracion_view.php';
exit;