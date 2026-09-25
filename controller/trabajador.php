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

// Acción
$action = 'alta';
if (isset($_GET['action'])) {
  $action = trim($_GET['action']);
  $action = strtolower($action);
}

// Tipo centro
$tipo = 'oficina';
if (isset($_GET['tipo'])) {
  $tipo = trim($_GET['tipo']);
  $tipo = strtolower($tipo);
}

if ($tipo != 'oficina' && $tipo != 'almacen' && $tipo != 'serigrafia') {
  $tipo = 'oficina';
}

// Conexión a la base de datos
include_once '../model/InventarioDB.php';
$conexion = InventarioDB::connectDB();

require_once '../model/TrabajadorAlta.php';
require_once '../model/TrabajadorListado.php';
require_once '../model/TrabajadorCrud.php';

/* ==========================================================
   Helpers
========================================================== */
function setMenuCentroFlags($tipoCentro)
{
  $datos = array();

  $active = 'oficina';

  if ($tipoCentro == 'almacen') {
    $active = 'almacen';
  }

  if ($tipoCentro == 'serigrafia') {
    $active = 'serigrafia';
  }

  $submenuOficina = false;
  $submenuAlmacen = false;
  $submenuSerigrafia = false;

  $activeSubmenuOficina = '';
  $activeSubmenuAlmacen = '';
  $activeSubmenuSerigrafia = '';

  if ($active == 'oficina') {
    $submenuOficina = true;
    $activeSubmenuOficina = 'alta_trabajador';
  }

  if ($active == 'almacen') {
    $submenuAlmacen = true;
    $activeSubmenuAlmacen = 'alta_trabajador';
  }

  if ($active == 'serigrafia') {
    $submenuSerigrafia = true;
    $activeSubmenuSerigrafia = 'alta_trabajador';
  }

  $datos['active'] = $active;
  $datos['submenuOficina'] = $submenuOficina;
  $datos['submenuAlmacen'] = $submenuAlmacen;
  $datos['submenuSerigrafia'] = $submenuSerigrafia;
  $datos['activeSubmenuOficina'] = $activeSubmenuOficina;
  $datos['activeSubmenuAlmacen'] = $activeSubmenuAlmacen;
  $datos['activeSubmenuSerigrafia'] = $activeSubmenuSerigrafia;

  return $datos;
}

function fetchProductoPorId($cn, $idProducto)
{
  if ($idProducto <= 0) {
    return null;
  }

  $consulta = "
    SELECT p.id, p.codigo, p.modelo, p.subtipo, c.nombre AS categoria_nombre,
           pa_num.valor AS numero,
           pa_tar.valor AS tarifa
    FROM producto p
    INNER JOIN categoria c ON c.id = p.id_categoria
    LEFT JOIN producto_atributo pa_num ON pa_num.id_producto = p.id AND pa_num.id_atributo = 14
    LEFT JOIN producto_atributo pa_tar ON pa_tar.id_producto = p.id AND pa_tar.id_atributo = 13
    WHERE p.id = $idProducto
    LIMIT 1
  ";

  $resultado = $cn->query($consulta);
  return $resultado->fetchObject();
}

// Devuelve lista de productos disponibles por categorías + incluye el actual
function opcionesProducto($cn, $categorias, $idActual, $subtipo = '')
{
  $lista = TrabajadorAlta::listarProductosDisponiblesPorCategorias($cn, $categorias, $subtipo);

  if ($idActual > 0) {
    $actual = fetchProductoPorId($cn, $idActual);

    if ($actual) {
      $ya = false;

      foreach ($lista as $p) {
        if ((int)$p->id == (int)$idActual) {
          $ya = true;
        }
      }

      if (!$ya) {
        $nuevaLista = array();
        $nuevaLista[] = $actual;

        foreach ($lista as $p) {
          $nuevaLista[] = $p;
        }

        $lista = $nuevaLista;
      }
    }
  }

  return $lista;
}

/* ==========================================================
   BAJA CON CONFIRMACIÓN
========================================================== */
if ($action == 'baja' && isset($_GET['id'])) {

  $idTrabajador = (int)$_GET['id'];

  $trabajador = TrabajadorListado::obtenerTrabajador($conexion, $idTrabajador);
  if (!$trabajador) {
    $conexion = null;
    die("Trabajador no encontrado");
  }

  $tipo = $trabajador->centro_tipo;

  $datosMenu = setMenuCentroFlags($tipo);
  $active = $datosMenu['active'];
  $submenuOficina = $datosMenu['submenuOficina'];
  $submenuAlmacen = $datosMenu['submenuAlmacen'];
  $submenuSerigrafia = $datosMenu['submenuSerigrafia'];
  $activeSubmenuOficina = $datosMenu['activeSubmenuOficina'];
  $activeSubmenuAlmacen = $datosMenu['activeSubmenuAlmacen'];
  $activeSubmenuSerigrafia = $datosMenu['activeSubmenuSerigrafia'];

  $tituloPagina = "Eliminar Trabajador";

  if (isset($_POST['confirmar_baja'])) {
    $ok = TrabajadorCrud::darDeBajaTotal($conexion, $idTrabajador);

    if ($ok) {
      header("location:" . $tipo . ".php");
    } else {
      $error = "No se pudo eliminar el trabajador.";
      include_once '../view/trabajador_baja_confirmar_view.php';
    }
  } else {
    include_once '../view/trabajador_baja_confirmar_view.php';
  }

  $conexion = null;
  exit;
}

/* ==========================================================
   REACTIVAR (deshacer baja lógica)
========================================================== */
if ($action == 'reactivar' && isset($_GET['id'])) {
  if ($_SESSION['rol'] != 'admin') { header('location:index.php'); exit; }

  $idTrabajador = (int)$_GET['id'];
  $trabajador = TrabajadorListado::obtenerTrabajador($conexion, $idTrabajador);
  $tipoDestino = 'oficina';
  if ($trabajador) { $tipoDestino = $trabajador->centro_tipo; }

  TrabajadorCrud::reactivar($conexion, $idTrabajador);
  $conexion = null;
  header("location:" . $tipoDestino . ".php?ver=bajas");
  exit;
}

/* ==========================================================
   EDITAR DATOS
========================================================== */
if ($action == 'editar' && isset($_GET['id'])) {

  $idTrabajador = (int)$_GET['id'];

  $trabajador = TrabajadorListado::obtenerTrabajador($conexion, $idTrabajador);
  if (!$trabajador) {
    $conexion = null;
    die("Trabajador no encontrado");
  }

  $tipo = $trabajador->centro_tipo;

  $datosMenu = setMenuCentroFlags($tipo);
  $active = $datosMenu['active'];
  $submenuOficina = $datosMenu['submenuOficina'];
  $submenuAlmacen = $datosMenu['submenuAlmacen'];
  $submenuSerigrafia = $datosMenu['submenuSerigrafia'];
  $activeSubmenuOficina = $datosMenu['activeSubmenuOficina'];
  $activeSubmenuAlmacen = $datosMenu['activeSubmenuAlmacen'];
  $activeSubmenuSerigrafia = $datosMenu['activeSubmenuSerigrafia'];

  $tituloPagina = 'Editar Trabajador';
  $errores = array();
  $ok = false;

  $centros = TrabajadorAlta::listarCentrosPorTipo($conexion, $tipo);
  $departamentos = TrabajadorAlta::listarDepartamentos($conexion);

  $idSimActual = 0;
  if (isset($trabajador->id_producto_numero_movil)) {
    $idSimActual = (int)$trabajador->id_producto_numero_movil;
  }

  $numerosMovilDisponibles = opcionesProducto($conexion, array('Numero Movil'), $idSimActual);

  if (isset($_POST['guardarEdicion'])) {

    $nombre = '';
    if (isset($_POST['nombre'])) {
      $nombre = trim($_POST['nombre']);
    }

    $email = '';
    if (isset($_POST['email'])) {
      $email = trim($_POST['email']);
    }

    $idCentro = 0;
    if (isset($_POST['id_centro'])) {
      $idCentro = (int)$_POST['id_centro'];
    }

    $idDepartamento = 0;
    if (isset($_POST['id_departamento'])) {
      $idDepartamento = (int)$_POST['id_departamento'];
    }

    $cargo = '';
    if (isset($_POST['cargo'])) {
      $cargo = trim($_POST['cargo']);
    }

    $idProductoNumeroMovil = 0;
    if (isset($_POST['id_producto_numero_movil'])) {
      $idProductoNumeroMovil = (int)$_POST['id_producto_numero_movil'];
    }

    $observaciones = '';
    if (isset($_POST['observaciones'])) {
      $observaciones = trim($_POST['observaciones']);
    }

    if ($idCentro <= 0) $errores[] = "Debes seleccionar un centro.";
    if ($nombre == '') $errores[] = "El nombre es obligatorio.";
    if ($email == '') $errores[] = "El email es obligatorio.";

    if (!isset($errores) || count($errores) == 0 && $idProductoNumeroMovil > 0 && $idProductoNumeroMovil != $idSimActual) {
      if (TrabajadorCrud::productoOcupado($conexion, $idProductoNumeroMovil)) {
        $errores[] = "Ese número móvil ya está asignado.";
      }
    }

    if (!isset($errores) || count($errores) == 0) {

      TrabajadorCrud::actualizarTrabajador(
        $conexion,
        $idTrabajador,
        $nombre,
        $email,
        $idCentro,
        $idDepartamento,
        $cargo,
        $idProductoNumeroMovil,
        $observaciones
      );

      if ($idSimActual > 0 && $idProductoNumeroMovil == 0) {
        TrabajadorCrud::borrarAsignacionSlot($conexion, $idTrabajador, 'numero_movil');
      }

      if ($idSimActual > 0 && $idProductoNumeroMovil > 0 && $idProductoNumeroMovil != $idSimActual) {
        TrabajadorCrud::borrarAsignacionSlot($conexion, $idTrabajador, 'numero_movil');
      }

      if ($idProductoNumeroMovil > 0 && $idProductoNumeroMovil != $idSimActual) {
        $r = TrabajadorCrud::setSlot($conexion, $idTrabajador, 'numero_movil', $idProductoNumeroMovil);

        if (!$r['ok']) {
          $errores[] = $r['msg'];
        }
      }

      if (!isset($errores) || count($errores) == 0) {
        header("location:trabajador_detalle.php?id=" . $idTrabajador);
        $conexion = null;
        exit;
      }
    }
  }

  include_once '../view/trabajador_editar_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   EQUIPO
========================================================== */
if ($action == 'equipo' && isset($_GET['id'])) {

  $idTrabajador = (int)$_GET['id'];

  $trabajador = TrabajadorListado::obtenerTrabajador($conexion, $idTrabajador);
  if (!$trabajador) {
    $conexion = null;
    die("Trabajador no encontrado");
  }

  $tipo = $trabajador->centro_tipo;

  $datosMenu = setMenuCentroFlags($tipo);
  $active = $datosMenu['active'];
  $submenuOficina = $datosMenu['submenuOficina'];
  $submenuAlmacen = $datosMenu['submenuAlmacen'];
  $submenuSerigrafia = $datosMenu['submenuSerigrafia'];
  $activeSubmenuOficina = $datosMenu['activeSubmenuOficina'];
  $activeSubmenuAlmacen = $datosMenu['activeSubmenuAlmacen'];
  $activeSubmenuSerigrafia = $datosMenu['activeSubmenuSerigrafia'];

  $tituloPagina = 'Equipo del Trabajador';
  $errores = array();
  $ok = false;

  $asigActual = TrabajadorCrud::asignacionesActualesPorSlot($conexion, $idTrabajador);

  $idEquipo1 = isset($asigActual['equipo1']) ? (int)$asigActual['equipo1'] : 0;
  $idEquipo2 = isset($asigActual['equipo2']) ? (int)$asigActual['equipo2'] : 0;
  $idMonitor1 = isset($asigActual['monitor1']) ? (int)$asigActual['monitor1'] : 0;
  $idMonitor2 = isset($asigActual['monitor2']) ? (int)$asigActual['monitor2'] : 0;
  $idTeclado = isset($asigActual['teclado']) ? (int)$asigActual['teclado'] : 0;
  $idRaton = isset($asigActual['raton']) ? (int)$asigActual['raton'] : 0;
  $idTelefonoMovil = isset($asigActual['telefono_movil']) ? (int)$asigActual['telefono_movil'] : 0;
  $idMaletin = isset($asigActual['maletin']) ? (int)$asigActual['maletin'] : 0;

  $equiposDisponibles = opcionesProducto($conexion, array('Ordenador'), $idEquipo1);
  $equiposDisponibles2 = opcionesProducto($conexion, array('Ordenador'), $idEquipo2);

  $monitoresDisponibles = opcionesProducto($conexion, array('Monitor'), $idMonitor1);
  $monitoresDisponibles2 = opcionesProducto($conexion, array('Monitor'), $idMonitor2);

  $tecladosDisponibles = opcionesProducto($conexion, array('Teclado'), $idTeclado);
  $ratonesDisponibles = opcionesProducto($conexion, array('Raton', 'Ratón'), $idRaton);

  $movilesDisponibles = opcionesProducto($conexion, array('Telefono Movil'), $idTelefonoMovil);
  $maletinesDisponibles = opcionesProducto($conexion, array('Maletin', 'Maletín', 'Maletin Portatil', 'Maletín Portatil'), $idMaletin);

  $anydesk_equipo1 = '';
  if (isset($trabajador->anydesk_equipo1)) {
    $anydesk_equipo1 = $trabajador->anydesk_equipo1;
  }

  $anydesk_equipo2 = '';
  if (isset($trabajador->anydesk_equipo2)) {
    $anydesk_equipo2 = $trabajador->anydesk_equipo2;
  }

  if (isset($_POST['guardarEquipo'])) {

    $nuevoEquipo1 = 0;
    if (isset($_POST['equipo1'])) {
      $nuevoEquipo1 = (int)$_POST['equipo1'];
    }

    $nuevoEquipo2 = 0;
    if (isset($_POST['equipo2'])) {
      $nuevoEquipo2 = (int)$_POST['equipo2'];
    }

    $nuevoMonitor1 = 0;
    if (isset($_POST['monitor1'])) {
      $nuevoMonitor1 = (int)$_POST['monitor1'];
    }

    $nuevoMonitor2 = 0;
    if (isset($_POST['monitor2'])) {
      $nuevoMonitor2 = (int)$_POST['monitor2'];
    }

    $nuevoTeclado = 0;
    if (isset($_POST['teclado'])) {
      $nuevoTeclado = (int)$_POST['teclado'];
    }

    $nuevoRaton = 0;
    if (isset($_POST['raton'])) {
      $nuevoRaton = (int)$_POST['raton'];
    }

    $nuevoTelefonoMovil = 0;
    if (isset($_POST['telefono_movil'])) {
      $nuevoTelefonoMovil = (int)$_POST['telefono_movil'];
    }

    $nuevoMaletin = 0;
    if (isset($_POST['maletin'])) {
      $nuevoMaletin = (int)$_POST['maletin'];
    }

    $anydesk_equipo1 = '';
    if (isset($_POST['anydesk_equipo1'])) {
      $anydesk_equipo1 = trim($_POST['anydesk_equipo1']);
    }

    $anydesk_equipo2 = '';
    if (isset($_POST['anydesk_equipo2'])) {
      $anydesk_equipo2 = trim($_POST['anydesk_equipo2']);
    }

    if ($nuevoEquipo1 > 0 && $nuevoEquipo2 > 0 && $nuevoEquipo1 == $nuevoEquipo2) {
      $errores[] = "No puedes seleccionar el mismo equipo en Equipo 1 y Equipo 2.";
    }

    if (!isset($errores) || count($errores) == 0) {

      if ($anydesk_equipo1 == '') {
        $anydesk1SQL = "NULL";
      } else {
        $anydesk1SQL = "'$anydesk_equipo1'";
      }

      if ($anydesk_equipo2 == '') {
        $anydesk2SQL = "NULL";
      } else {
        $anydesk2SQL = "'$anydesk_equipo2'";
      }

      $consulta = "
        UPDATE trabajador
        SET anydesk_equipo1 = $anydesk1SQL,
            anydesk_equipo2 = $anydesk2SQL
        WHERE id = $idTrabajador
        LIMIT 1
      ";

      $conexion->exec($consulta);

      $cambios = array(
        'equipo1' => $nuevoEquipo1,
        'equipo2' => $nuevoEquipo2,
        'monitor1' => $nuevoMonitor1,
        'monitor2' => $nuevoMonitor2,
        'teclado' => $nuevoTeclado,
        'raton' => $nuevoRaton,
        'telefono_movil' => $nuevoTelefonoMovil,
        'maletin' => $nuevoMaletin
      );

      foreach ($cambios as $slot => $idProd) {
        $r = TrabajadorCrud::setSlot($conexion, $idTrabajador, $slot, (int)$idProd);

        if (!$r['ok']) {
          $errores[] = $slot . ": " . $r['msg'];
        }
      }

      if (!isset($errores) || count($errores) == 0) {
        header("location:trabajador_detalle.php?id=" . $idTrabajador);
        $conexion = null;
        exit;
      }
    }
  }

  include_once '../view/trabajador_equipo_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   ALTA
========================================================== */

$tipoAlta = $tipo;

$datosMenu = setMenuCentroFlags($tipoAlta);
$active = $datosMenu['active'];
$submenuOficina = $datosMenu['submenuOficina'];
$submenuAlmacen = $datosMenu['submenuAlmacen'];
$submenuSerigrafia = $datosMenu['submenuSerigrafia'];
$activeSubmenuOficina = $datosMenu['activeSubmenuOficina'];
$activeSubmenuAlmacen = $datosMenu['activeSubmenuAlmacen'];
$activeSubmenuSerigrafia = $datosMenu['activeSubmenuSerigrafia'];

$tituloPagina = 'Alta Trabajador';
$errores = array();
$ok = false;

$centros = TrabajadorAlta::listarCentrosPorTipo($conexion, $tipoAlta);

$idCentro = 0;
if (isset($_GET['id_centro'])) {
  $idCentro = (int)$_GET['id_centro'];
}

if (isset($_POST['id_centro'])) {
  $idCentro = (int)$_POST['id_centro'];
}

$centro = null;
if ($idCentro > 0) {
  $centro = TrabajadorAlta::obtenerCentro($conexion, $idCentro, $tipoAlta);
  if (!$centro) {
    $idCentro = 0;
  }
}

$departamentos = TrabajadorAlta::listarDepartamentos($conexion);

$equiposDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Ordenador'));
$monitoresDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Monitor'));
$tecladosDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Teclado'));
$ratonesDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Raton', 'Ratón'));
$movilesDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Telefono Movil'));
$maletinesDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Maletin', 'Maletín', 'Maletin Portatil', 'Maletín Portatil'));
$numerosMovilDisponibles = TrabajadorAlta::listarProductosDisponiblesPorCategorias($conexion, array('Numero Movil'));

if ($action == 'alta' && isset($_POST['guardarTrabajador'])) {

  $nombre = '';
  if (isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
  }

  $email = '';
  if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
  }

  $idDepartamento = 0;
  if (isset($_POST['id_departamento'])) {
    $idDepartamento = (int)$_POST['id_departamento'];
  }

  $cargo = '';
  if (isset($_POST['cargo'])) {
    $cargo = trim($_POST['cargo']);
  }

  $idProductoNumeroMovil = 0;
  if (isset($_POST['id_producto_numero_movil'])) {
    $idProductoNumeroMovil = (int)$_POST['id_producto_numero_movil'];
  }

  $idEquipo1 = 0;
  if (isset($_POST['equipo1'])) {
    $idEquipo1 = (int)$_POST['equipo1'];
  }

  $idEquipo2 = 0;
  if (isset($_POST['equipo2'])) {
    $idEquipo2 = (int)$_POST['equipo2'];
  }

  $anydesk_equipo1 = '';
  if (isset($_POST['anydesk_equipo1'])) {
    $anydesk_equipo1 = trim($_POST['anydesk_equipo1']);
  }

  $anydesk_equipo2 = '';
  if (isset($_POST['anydesk_equipo2'])) {
    $anydesk_equipo2 = trim($_POST['anydesk_equipo2']);
  }

  $idMonitor1 = 0;
  if (isset($_POST['monitor1'])) {
    $idMonitor1 = (int)$_POST['monitor1'];
  }

  $idMonitor2 = 0;
  if (isset($_POST['monitor2'])) {
    $idMonitor2 = (int)$_POST['monitor2'];
  }

  $idTeclado = 0;
  if (isset($_POST['teclado'])) {
    $idTeclado = (int)$_POST['teclado'];
  }

  $idRaton = 0;
  if (isset($_POST['raton'])) {
    $idRaton = (int)$_POST['raton'];
  }

  $idTelefonoMovil = 0;
  if (isset($_POST['telefono_movil'])) {
    $idTelefonoMovil = (int)$_POST['telefono_movil'];
  }

  $idMaletin = 0;
  if (isset($_POST['maletin'])) {
    $idMaletin = (int)$_POST['maletin'];
  }

  $observaciones = '';
  if (isset($_POST['observaciones'])) {
    $observaciones = trim($_POST['observaciones']);
  }

  if ($idCentro <= 0) $errores[] = "Debes seleccionar un centro.";
  if ($nombre == '') $errores[] = "El nombre es obligatorio.";
  if ($email == '') $errores[] = "El email no es válido.";

  if ($idEquipo1 > 0 && $idEquipo2 > 0 && $idEquipo1 == $idEquipo2) {
    $errores[] = "No puedes seleccionar el mismo equipo en Equipo 1 y Equipo 2.";
  }

  $idsAValidar = array(
    $idProductoNumeroMovil,
    $idEquipo1,
    $idEquipo2,
    $idMonitor1,
    $idMonitor2,
    $idTeclado,
    $idRaton,
    $idTelefonoMovil,
    $idMaletin
  );

  foreach ($idsAValidar as $pid) {
    if ($pid > 0 && TrabajadorAlta::productoOcupado($conexion, $pid)) {
      $errores[] = "El producto con ID $pid ya está asignado. Refresca la página y selecciona otro.";
      break;
    }
  }

  if (!isset($errores) || count($errores) == 0) {

    if ($idDepartamento <= 0) {
      $idDepartamentoEnviar = null;
    } else {
      $idDepartamentoEnviar = $idDepartamento;
    }

    if ($idProductoNumeroMovil <= 0) {
      $idProductoNumeroMovilEnviar = null;
    } else {
      $idProductoNumeroMovilEnviar = $idProductoNumeroMovil;
    }

    if ($anydesk_equipo1 == '') {
      $anydesk1Enviar = null;
    } else {
      $anydesk1Enviar = $anydesk_equipo1;
    }

    if ($anydesk_equipo2 == '') {
      $anydesk2Enviar = null;
    } else {
      $anydesk2Enviar = $anydesk_equipo2;
    }

    if ($observaciones == '') {
      $observacionesEnviar = null;
    } else {
      $observacionesEnviar = $observaciones;
    }

    $idTrabajador = TrabajadorAlta::insertarTrabajador(
      $conexion,
      $idCentro,
      $nombre,
      $email,
      $idDepartamentoEnviar,
      $cargo,
      $idProductoNumeroMovilEnviar,
      $anydesk1Enviar,
      $anydesk2Enviar,
      $observacionesEnviar
    );

    if ($idTrabajador <= 0) {
      $errores[] = "No se pudo crear el trabajador.";
    } else {

      $asignaciones = array(
        'equipo1' => $idEquipo1,
        'equipo2' => $idEquipo2,
        'monitor1' => $idMonitor1,
        'monitor2' => $idMonitor2,
        'teclado' => $idTeclado,
        'raton' => $idRaton,
        'telefono_movil' => $idTelefonoMovil,
        'maletin' => $idMaletin
      );

      foreach ($asignaciones as $slot => $pid) {
        if ($pid > 0) {
          TrabajadorAlta::asignarProductoATrabajador($conexion, $pid, $idTrabajador, $slot);
        }
      }

      if ($idProductoNumeroMovil > 0) {
        TrabajadorAlta::asignarProductoATrabajador($conexion, $idProductoNumeroMovil, $idTrabajador, 'numero_movil');
      }

      header("location:trabajador.php?action=alta&tipo=" . urlencode($tipoAlta) . "&ok=1");
      $conexion = null;
      exit;
    }
  }
}

if (isset($_GET['ok']) && $_GET['ok'] == '1') {
  $ok = true;
}

include_once '../view/trabajador_alta_view.php';
$conexion = null;
exit;