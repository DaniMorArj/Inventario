<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
  header('location:login.php');
  exit;
}

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
  header('location:index.php');
  exit;
}

$rol = $_SESSION['rol'];

/* MENÚ */
$menuDashboard  = true;
$menuTienda     = true;
$menuOficina    = true;
$menuAlmacen    = true;
$menuSerigrafia = true;
$menuStock      = true;
$menuUsuarios   = true;
$menuConfiguracion = ($rol == 'admin');
$menuSoporte    = true;

$verTareasPendientes = false;

$active = 'usuarios';
$submenuUsuarios = true;
$activeSubmenu = '';

/* Acción */
$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];
}

/* Conexión */
include_once '../model/InventarioDB.php';
$conexion = InventarioDB::connectDB();

require_once '../model/UsuarioAlta.php';


/* ========= ACCIÓN: ALTA ========= */
if ($action == 'alta') {

  $tituloPagina = 'Alta Usuario';
  $activeSubmenu = 'alta';

  $errores = array();
  $ok = false;

  if (isset($_POST['guardarUsuario'])) {

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $departamento = $_POST['departamento'];
    $rol = $_POST['rol'];

    if ($nombre == '') $errores[] = "El nombre es obligatorio";
    if ($email == '') $errores[] = "El email es obligatorio";
    if ($pass == '') $errores[] = "La contraseña es obligatoria";
    if ($departamento == '') $errores[] = "El departamento es obligatorio";
    if ($rol == '') $errores[] = "Debes seleccionar un rol";

    if (!isset($errores) || count($errores) == 0) {
      if (UsuarioAlta::emailExiste($conexion, $email)) {
        $errores[] = "El email ya existe";
      }
    }

    if (!isset($errores) || count($errores) == 0) {
      $filas = UsuarioAlta::insertar($conexion, $nombre, $email, $pass, $rol, $departamento);
      if ($filas == 1) {
        header('location:usuarios.php');
        exit;
      } else {
        $errores[] = "Error al insertar el usuario";
      }
    }
  }

  include_once '../view/usuarios_alta_view.php';
  $conexion = null;
  exit;
}


/* ========= ACCIÓN: ELIMINAR ========= */
if ($action == 'eliminar' && isset($_GET['id'])) {

  $tituloPagina = 'Eliminar Usuario';

  $id = (int)$_GET['id'];

  $usuario = UsuarioAlta::obtenerPorId($conexion, $id);

  include_once '../view/usuarios_eliminar_view.php';
  $conexion = null;
  exit;
}


/* ========= ACCIÓN: ELIMINAR CONFIRMADO ========= */
if ($action == 'eliminar_confirmado' && isset($_POST['id'])) {

  $id = (int)$_POST['id'];

  UsuarioAlta::eliminar($conexion, $id);

  $conexion = null;
  header('location:usuarios.php');
  exit;
}


/* ========= ACCIÓN: EDITAR ========= */
if ($action == 'editar' && isset($_GET['id'])) {

  $tituloPagina = 'Editar Usuario';

  $errores = array();
  $ok = false;

  $id = (int)$_GET['id'];

  $usuario = UsuarioAlta::obtenerPorId($conexion, $id);

  include_once '../view/usuarios_editar_view.php';
  $conexion = null;
  exit;
}


/* ========= ACCIÓN: EDITAR GUARDAR ========= */
if ($action == 'editar_guardar' && isset($_POST['id'])) {

  $errores = array();
  $ok = false;

  $id = (int)$_POST['id'];

  $nombre = $_POST['nombre'];
  $email = $_POST['email'];
  $pass = $_POST['pass']; // puede venir vacío
  $rol = $_POST['rol'];
  $departamento = $_POST['departamento'];

  if ($nombre == '') $errores[] = "El nombre es obligatorio";
  if ($email == '') $errores[] = "El email es obligatorio";
  if ($rol == '') $errores[] = "Debes seleccionar un rol";
  if ($departamento == '') $errores[] = "El departamento es obligatorio";

  // Si el email cambia, comprobar duplicado
  if (!isset($errores) || count($errores) == 0) {
    $actual = UsuarioAlta::obtenerPorId($conexion, $id);

    if ($actual && $actual->email != $email) {
      if (UsuarioAlta::emailExiste($conexion, $email)) {
        $errores[] = "El email ya existe";
      }
    }
  }

  if (!isset($errores) || count($errores) == 0) {

    if ($pass == '') {
      // No cambia contraseña
      UsuarioAlta::actualizar($conexion, $id, $nombre, $email, $rol, $departamento);
    } else {
      // Cambia contraseña
      UsuarioAlta::actualizarConPass($conexion, $id, $nombre, $email, $rol, $departamento, $pass);
    }

    header('location:usuarios.php');
    $conexion = null;
    exit;
  }

  // Si hay errores, volvemos a cargar el usuario para repintar
  $usuario = new stdClass();
  $usuario->id = $id;
  $usuario->nombre = $nombre;
  $usuario->email = $email;
  $usuario->rol = $rol;
  $usuario->departamento = $departamento;

  include_once '../view/usuarios_editar_view.php';
  $conexion = null;
  exit;
}


/* ========= LISTADO ========= */

$tituloPagina = 'Usuarios';

// Filtros
$buscar = '';
if (isset($_GET['buscar'])) {
  $buscar = $_GET['buscar'];
}

$filtroRol = '';
if (isset($_GET['rol'])) {
  $filtroRol = $_GET['rol'];
}

// Paginación
$porPagina = 4;
$pagina = 1;

if (isset($_GET['pagina'])) {
  $pagina = (int)$_GET['pagina'];

  if ($pagina < 1) {
    $pagina = 1;
  }
}

$inicio = ($pagina - 1) * $porPagina;

$totalFilas = UsuarioAlta::contar($conexion, $buscar, $filtroRol);
$totalPaginas = (int)ceil($totalFilas / $porPagina);

if ($totalPaginas < 1) {
  $totalPaginas = 1;
}

if ($pagina > $totalPaginas) {
  $pagina = $totalPaginas;
}

$usuarios = UsuarioAlta::listar($conexion, $buscar, $filtroRol, $inicio, $porPagina);

include_once '../view/usuarios_view.php';

$conexion = null;