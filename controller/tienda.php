<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
  header('location:login.php');
  exit;
}

// Funcion auxiliar para obtener codigo de producto por id desde asignaciones
function getCodigoPorId($asignaciones, $idProducto) {
  if ($idProducto <= 0) { return ''; }
  foreach ($asignaciones as $a) {
    if ($a->id_producto == $idProducto) {
      return $a->codigo;
    }
  }
  return '';
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

$verTareasPendientes = ($rol == 'admin');

$active = 'tienda';
$submenuTienda = true;
$activeSubmenu = '';

// Acción
$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];
}

// Conexión a la base de datos
include_once '../model/InventarioDB.php';
$conexion = InventarioDB::connectDB();

require_once '../model/TiendaAlta.php';
require_once '../model/Envio.php';
require_once '../model/StockAlta.php';

/* ==========================================================
   HELPER: cargar datos para detalle tienda
========================================================== */
function cargarDetalleTiendaData($conexion, $idTienda)
{
  $data = array();

  $data['sociedades'] = TiendaAlta::listarSociedades($conexion);
  $data['tienda'] = TiendaAlta::obtenerPorId($conexion, $idTienda);
  $data['asignaciones'] = array();

  $data['ordenadoresDisponibles'] = array();
  $data['impTicketsDisponibles'] = array();
  $data['impMultiDisponibles'] = array();
  $data['cajonesDisponibles'] = array();
  $data['lectorCodigoDisponibles'] = array();
  $data['lectorBilleteDisponibles'] = array();
  $data['telefonosFijosDisponibles'] = array();
  $data['telefonosMovilesDisponibles'] = array();
  $data['movilesDisponibles'] = array();
  $data['datafonosDisponibles'] = array();
  $data['pinpadsDisponibles'] = array();
  $data['routersDisponibles'] = array();
  $data['camaras360Disponibles'] = array();
  $data['camarasFijasDisponibles'] = array();

  if (!$data['tienda']) {
    return $data;
  }

  $data['asignaciones'] = TiendaAlta::obtenerAsignacionesTienda($conexion, $idTienda);

  $idCatOrdenador      = 1;
  $idCatImpTickets     = 2;
  $idCatImpMulti       = 3;
  $idCatLectorCodigo   = 4;
  $idCatLectorBillete  = 5;
  $idCatRouter         = 6;
  $idCatCam360         = 7;
  $idCatCamFija        = 8;
  $idCatDatafono       = 9;
  $idCatPinpad         = 10;
  $idCatTelefono       = 13;
  $idCatTelefonoMovil  = 20;
  $idCatMovil          = 14;

  $data['ordenadoresDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatOrdenador);
  $data['impTicketsDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatImpTickets);
  $data['impMultiDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatImpMulti);

  $data['lectorCodigoDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatLectorCodigo);
  $data['lectorBilleteDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatLectorBillete);

  $data['telefonosFijosDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatTelefono);
  $data['telefonosMovilesDisponibles'] = TiendaAlta::listarTelefonosMovilesDisponibles($conexion, $idCatTelefonoMovil);
  $data['movilesDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatMovil);

  $data['datafonosDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatDatafono);
  $data['pinpadsDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatPinpad);

  $data['routersDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatRouter);

  $data['camaras360Disponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatCam360);
  $data['camarasFijasDisponibles'] = TiendaAlta::listarProductosDisponiblesPorCategoria($conexion, $idCatCamFija);

  $data['cajonesDisponibles'] = TiendaAlta::listarCajonesDisponibles($conexion);
  $data['clusterManagers'] = TiendaAlta::listarClusterManagers($conexion);
  // Envios en curso hacia esta tienda + material disponible para enviar
  $data['enviosTienda'] = Envio::listarPorTienda($conexion, $idTienda);
  $data['enviosDisponibles'] = Envio::listarDisponibles($conexion);
  $movilActual = '';
  if (isset($data['tienda']->movil)) { $movilActual = $data['tienda']->movil; }
  $data['numerosMovilDisponibles'] = TiendaAlta::listarTelefonosMovilesDisponibles($conexion, 14, $movilActual);

  return $data;
}

/* ==========================================================
   ALTA TIENDA
========================================================== */
if ($action == 'alta') {
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }


  if ($_SESSION['rol'] != 'admin') {
    header('location:tienda.php');
    exit;
  }

  $tituloPagina = 'Alta Tienda';
  $active = 'tienda';
  $activeSubmenu = 'alta';
  $errores = array();
  $ok = false;

  $sociedades = TiendaAlta::listarSociedades($conexion);
  $numerosMovilDisponibles = TiendaAlta::listarTelefonosMovilesDisponibles($conexion, 14);

  if (isset($_POST['guardarTienda'])) {

    $numero = '';
    if (isset($_POST['numero'])) {
      $numero = trim($_POST['numero']);
    }

    $nombre = '';
    if (isset($_POST['nombre'])) {
      $nombre = trim($_POST['nombre']);
    }

    $idSociedad = '';
    if (isset($_POST['sociedad'])) {
      $idSociedad = trim($_POST['sociedad']);
    }

    $movil = '';
    if (isset($_POST['movil'])) {
      $movil = trim($_POST['movil']);
    }

    $fijo = '';
    if (isset($_POST['fijo'])) {
      $fijo = trim($_POST['fijo']);
    }

    $email = '';
    if (isset($_POST['email'])) {
      $email = trim($_POST['email']);
    }

    $observaciones = '';
    if (isset($_POST['observaciones'])) {
      $observaciones = trim($_POST['observaciones']);
    }

    if ($numero == '') $errores[] = "El número de tienda es obligatorio";
    if ($nombre == '') $errores[] = "El nombre de la tienda es obligatorio";
    if ($idSociedad == '') $errores[] = "Debes seleccionar una sociedad";

    if (!isset($errores) || count($errores) == 0) {
      $filas = TiendaAlta::insertar($conexion, $numero, $nombre, $idSociedad, $movil, $fijo, $email, $observaciones);

      if ($filas == 1) {
        header('location:tienda.php');
        exit;
      } else {
        $errores[] = "Error al insertar la tienda";
      }
    }
  }

  include_once '../view/tienda_alta_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   DETALLE TIENDA
========================================================== */
if ($action == 'ver' && isset($_GET['id'])) {

  $tituloPagina = 'Detalle Tienda';
  $active = 'tienda';
  $submenuTienda = true;
  $activeSubmenu = '';

  $errores = array();
  $ok = false;

  $id = (int)$_GET['id'];

  $detalle = cargarDetalleTiendaData($conexion, $id);

  $sociedades = $detalle['sociedades'];
  $tienda = $detalle['tienda'];
  $asignaciones = $detalle['asignaciones'];

  $ordenadoresDisponibles = $detalle['ordenadoresDisponibles'];
  $impTicketsDisponibles = $detalle['impTicketsDisponibles'];
  $impMultiDisponibles = $detalle['impMultiDisponibles'];
  $cajonesDisponibles = $detalle['cajonesDisponibles'];
  $lectorCodigoDisponibles = $detalle['lectorCodigoDisponibles'];
  $lectorBilleteDisponibles = $detalle['lectorBilleteDisponibles'];
  $telefonosFijosDisponibles = $detalle['telefonosFijosDisponibles'];
  $telefonosMovilesDisponibles = $detalle['telefonosMovilesDisponibles'];
  $movilesDisponibles = $detalle['movilesDisponibles'];
  $datafonosDisponibles = $detalle['datafonosDisponibles'];
  $pinpadsDisponibles = $detalle['pinpadsDisponibles'];
  $routersDisponibles = $detalle['routersDisponibles'];
  $camaras360Disponibles = $detalle['camaras360Disponibles'];
  $camarasFijasDisponibles = $detalle['camarasFijasDisponibles'];
  $clusterManagers = $detalle['clusterManagers'];
  $numerosMovilDisponibles = $detalle['numerosMovilDisponibles'];
  $enviosTienda = $detalle['enviosTienda'];
  $enviosDisponibles = $detalle['enviosDisponibles'];
  $slotsTienda = Envio::slotsTienda();
  // Datos para "Registrar material comprado (directo)"
  $categorias = StockAlta::listarCategorias($conexion);
  $condicionesAsignables = array('nuevo' => 'Nuevo', 'usado' => 'Usado', 'reacondicionado' => 'Reacondicionado');

  include_once '../view/tienda_detalle_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   GUARDAR DATOS BÁSICOS TIENDA
========================================================== */
if ($action == 'guardar' && isset($_POST['id'])) {
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }


  if ($_SESSION['rol'] != 'admin') {
    header('location:tienda.php');
    exit;
  }

  $errores = array();
  $ok = false;

  $id = (int)$_POST['id'];

  $numero = '';
  if (isset($_POST['numero'])) {
    $numero = trim($_POST['numero']);
  }

  $nombre = '';
  if (isset($_POST['nombre'])) {
    $nombre = trim($_POST['nombre']);
  }

  $idSociedad = '';
  if (isset($_POST['sociedad'])) {
    $idSociedad = trim($_POST['sociedad']);
  }

  $movil = '';
  if (isset($_POST['movil'])) {
    $movil = trim($_POST['movil']);
  }

  $fijo = '';
  if (isset($_POST['fijo'])) {
    $fijo = trim($_POST['fijo']);
  }

  $email = '';
  if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
  }

  $observaciones = '';
  if (isset($_POST['observaciones'])) {
    $observaciones = trim($_POST['observaciones']);
  }

  $idClusterManager = 0;
  if (isset($_POST['id_cluster_manager'])) {
    $idClusterManager = $_POST['id_cluster_manager'];
  }



  if ($numero == '') $errores[] = "El número de tienda es obligatorio";
  if ($nombre == '') $errores[] = "El nombre es obligatorio";
  if ($idSociedad == '') $errores[] = "Debes seleccionar una sociedad";

  if (!isset($errores) || count($errores) == 0) {
    TiendaAlta::actualizar($conexion, $id, $numero, $nombre, $idSociedad, $movil, $fijo, $email, $observaciones, $idClusterManager);

    // Gestionar asignacion del movil de la tienda
    // Primero liberar el slot movil_tienda si habia uno
    TiendaAlta::liberarAsignacion($conexion, 'tienda', $id, 'movil_tienda');

    // Si se selecciono un movil, crear la asignacion
    if ($movil != '') {
      $consultaMovil = $conexion->query("SELECT id FROM producto WHERE codigo = '$movil' LIMIT 1");
      $productoMovil = $consultaMovil->fetchObject();
      if ($productoMovil) {
        TiendaAlta::asignarProducto($conexion, 'tienda', $id, 'movil_tienda', $productoMovil->id);
      }
    }

    $conexion = null;
    header('location:tienda.php?action=ver&id=' . $id);
    exit;
  }

  $tituloPagina = 'Detalle Tienda';
  $active = 'tienda';
  $submenuTienda = true;
  $activeSubmenu = '';

  $detalle = cargarDetalleTiendaData($conexion, $id);

  $sociedades = $detalle['sociedades'];
  $tienda = $detalle['tienda'];
  $asignaciones = $detalle['asignaciones'];

  $ordenadoresDisponibles = $detalle['ordenadoresDisponibles'];
  $impTicketsDisponibles = $detalle['impTicketsDisponibles'];
  $impMultiDisponibles = $detalle['impMultiDisponibles'];
  $cajonesDisponibles = $detalle['cajonesDisponibles'];
  $lectorCodigoDisponibles = $detalle['lectorCodigoDisponibles'];
  $lectorBilleteDisponibles = $detalle['lectorBilleteDisponibles'];
  $telefonosFijosDisponibles = $detalle['telefonosFijosDisponibles'];
  $telefonosMovilesDisponibles = $detalle['telefonosMovilesDisponibles'];
  $movilesDisponibles = $detalle['movilesDisponibles'];
  $datafonosDisponibles = $detalle['datafonosDisponibles'];
  $pinpadsDisponibles = $detalle['pinpadsDisponibles'];
  $routersDisponibles = $detalle['routersDisponibles'];
  $camaras360Disponibles = $detalle['camaras360Disponibles'];
  $camarasFijasDisponibles = $detalle['camarasFijasDisponibles'];

  // Para repintar lo que se había escrito
  if ($tienda) {
    $tienda->numero = $numero;
    $tienda->nombre = $nombre;
    $tienda->id_sociedad = $idSociedad;
    $tienda->movil = $movil;
    $tienda->fijo = $fijo;
    $tienda->email = $email;
    $tienda->observaciones = $observaciones;
  }

  include_once '../view/tienda_detalle_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   GUARDAR CONFIGURACIÓN + ASIGNACIONES TIENDA
========================================================== */
if ($action == 'guardar_asignaciones' && isset($_POST['id'])) {
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }


  if ($_SESSION['rol'] != 'admin') {
    header('location:tienda.php');
    exit;
  }

  $idTienda = (int)$_POST['id'];

  // ====== CONFIGURACIÓN DE TIENDA ======
  $anydeskEquipo1 = '';
  if (isset($_POST['anydesk_equipo1'])) {
    $anydeskEquipo1 = trim($_POST['anydesk_equipo1']);
  }

  $posEquipo1 = '';
  if (isset($_POST['pos_equipo1'])) {
    $posEquipo1 = trim($_POST['pos_equipo1']);
  }

  $cajaEquipo1 = '';
  if (isset($_POST['caja_equipo1'])) {
    $cajaEquipo1 = trim($_POST['caja_equipo1']);
  }

  $anydeskEquipo2 = '';
  if (isset($_POST['anydesk_equipo2'])) {
    $anydeskEquipo2 = trim($_POST['anydesk_equipo2']);
  }

  $posEquipo2 = '';
  if (isset($_POST['pos_equipo2'])) {
    $posEquipo2 = trim($_POST['pos_equipo2']);
  }

  $cajaEquipo2 = '';
  if (isset($_POST['caja_equipo2'])) {
    $cajaEquipo2 = trim($_POST['caja_equipo2']);
  }

  $operadora = '';
  if (isset($_POST['operadora'])) {
    $operadora = trim($_POST['operadora']);
  }

  $ipFija = '';
  if (isset($_POST['ip_fija'])) {
    $ipFija = trim($_POST['ip_fija']);
  }

  $vpn = '';
  if (isset($_POST['vpn'])) {
    $vpn = trim($_POST['vpn']);
  }

  // ====== SLOTS DE PRODUCTOS ======
  $slots = array();

  $slots['equipo1'] = isset($_POST['equipo1']) ? (int)$_POST['equipo1'] : 0;
  $slots['equipo2'] = isset($_POST['equipo2']) ? (int)$_POST['equipo2'] : 0;

  $slots['tickets1'] = isset($_POST['tickets1']) ? (int)$_POST['tickets1'] : 0;
  $slots['tickets2'] = isset($_POST['tickets2']) ? (int)$_POST['tickets2'] : 0;
  $slots['multifuncion'] = isset($_POST['multifuncion']) ? (int)$_POST['multifuncion'] : 0;
  $slots['cajon_portamonedas'] = isset($_POST['cajon_portamonedas']) ? (int)$_POST['cajon_portamonedas'] : 0;

  $slots['lector_codigo1'] = isset($_POST['lector_codigo1']) ? (int)$_POST['lector_codigo1'] : 0;
  $slots['lector_codigo2'] = isset($_POST['lector_codigo2']) ? (int)$_POST['lector_codigo2'] : 0;

  $slots['lector_billete1'] = isset($_POST['lector_billete1']) ? (int)$_POST['lector_billete1'] : 0;
  $slots['lector_billete2'] = isset($_POST['lector_billete2']) ? (int)$_POST['lector_billete2'] : 0;

  $slots['telefono_fijo'] = isset($_POST['telefono_fijo']) ? (int)$_POST['telefono_fijo'] : 0;
  $slots['telefono_movil'] = isset($_POST['telefono_movil']) ? (int)$_POST['telefono_movil'] : 0;

  $slots['datafono'] = isset($_POST['datafono']) ? (int)$_POST['datafono'] : 0;
  $slots['pinpad'] = isset($_POST['pinpad']) ? (int)$_POST['pinpad'] : 0;

  $slots['router_sos'] = isset($_POST['router_sos']) ? (int)$_POST['router_sos'] : 0;

  // ====== CÁMARAS MÚLTIPLES ======
  $cam360 = array();
  if (isset($_POST['camara360'])) {
    $cam360 = $_POST['camara360'];
  }

  $camFija = array();
  if (isset($_POST['camara_fija'])) {
    $camFija = $_POST['camara_fija'];
  }

  $i = 1;
  foreach ($cam360 as $val) {
    $slots['camara360_' . $i] = (int)$val;
    $i++;
  }

  $j = 1;
  foreach ($camFija as $val) {
    $slots['camara_fija_' . $j] = (int)$val;
    $j++;
  }

  // Completar cámaras vacías para poder liberar si antes existían (hasta 10)
  for ($c = 1; $c <= 10; $c++) {
    if (!isset($slots['camara360_' . $c])) {
      $slots['camara360_' . $c] = 0;
    }
    if (!isset($slots['camara_fija_' . $c])) {
      $slots['camara_fija_' . $c] = 0;
    }
  }

  // ====== VALIDACIONES ======
  $errores = array();

  // Mismo producto en dos slots
  $usados = array();
  foreach ($slots as $slot => $idProd) {
    if ($idProd > 0) {
      if (isset($usados[$idProd])) {
        $errores[] = "No puedes asignar el mismo producto en '" . $usados[$idProd] . "' y '" . $slot . "'.";
      } else {
        $usados[$idProd] = $slot;
      }
    }
  }

  // Producto ocupado en otro sitio
  if (!isset($errores) || count($errores) == 0) {
    foreach ($slots as $slot => $idProd) {
      if ($idProd > 0) {
        if (TiendaAlta::productoOcupadoEnOtroSitio($conexion, $idProd, 'tienda', $idTienda, $slot)) {
          $errores[] = "El producto seleccionado en '" . $slot . "' ya está asignado en otro sitio.";
        }
      }
    }
  }

  // ====== SI HAY ERRORES, RECARGAR VISTA ======
  if (isset($errores) && count($errores) > 0) {

    $tituloPagina = 'Detalle Tienda';
    $active = 'tienda';
    $submenuTienda = true;
    $activeSubmenu = '';

    $detalle = cargarDetalleTiendaData($conexion, $idTienda);

    $sociedades = $detalle['sociedades'];
    $tienda = $detalle['tienda'];
    $asignaciones = $detalle['asignaciones'];

    $ordenadoresDisponibles = $detalle['ordenadoresDisponibles'];
    $impTicketsDisponibles = $detalle['impTicketsDisponibles'];
    $impMultiDisponibles = $detalle['impMultiDisponibles'];
    $cajonesDisponibles = $detalle['cajonesDisponibles'];
    $lectorCodigoDisponibles = $detalle['lectorCodigoDisponibles'];
    $lectorBilleteDisponibles = $detalle['lectorBilleteDisponibles'];
    $telefonosFijosDisponibles = $detalle['telefonosFijosDisponibles'];
    $movilesDisponibles = $detalle['movilesDisponibles'];
    $datafonosDisponibles = $detalle['datafonosDisponibles'];
    $pinpadsDisponibles = $detalle['pinpadsDisponibles'];
    $routersDisponibles = $detalle['routersDisponibles'];
    $camaras360Disponibles = $detalle['camaras360Disponibles'];
    $camarasFijasDisponibles = $detalle['camarasFijasDisponibles'];
    $clusterManagers = $detalle['clusterManagers'];
  $numerosMovilDisponibles = $detalle['numerosMovilDisponibles'];

    // Mantener en pantalla lo que el usuario había escrito
    if ($tienda) {
      $tienda->anydesk_equipo1 = $anydeskEquipo1;
      $tienda->pos_equipo1 = $posEquipo1;
      $tienda->caja_equipo1 = $cajaEquipo1;

      $tienda->anydesk_equipo2 = $anydeskEquipo2;
      $tienda->pos_equipo2 = $posEquipo2;
      $tienda->caja_equipo2 = $cajaEquipo2;

      $tienda->operadora = $operadora;
      $tienda->ip_fija = $ipFija;
      $tienda->vpn = $vpn;
    }

    include_once '../view/tienda_detalle_view.php';
    $conexion = null;
    exit;
  }

  // ====== GUARDAR TODO ======

  // 1) Guardar configuración de tienda
  TiendaAlta::actualizarConfiguracionTienda(
    $conexion,
    $idTienda,
    $anydeskEquipo1,
    $posEquipo1,
    $cajaEquipo1,
    $anydeskEquipo2,
    $posEquipo2,
    $cajaEquipo2,
    $operadora,
    $ipFija,
    $vpn
  );

  // 2) Guardar asignaciones
  foreach ($slots as $slot => $idProd) {
    if ($idProd == 0) {
      TiendaAlta::liberarAsignacion($conexion, 'tienda', $idTienda, $slot);
    } else {
      $okAsignar = TiendaAlta::asignarProducto($conexion, 'tienda', $idTienda, $slot, $idProd);

      if (!$okAsignar) {
        $conexion = null;
        header('location:tienda.php?action=ver&id=' . $idTienda . '&error=1');
        exit;
      }
    }
  }

  $conexion = null;
  header('location:tienda.php?action=ver&id=' . $idTienda);
  exit;
}

/* ==========================================================
   LISTADO TIENDAS
========================================================== */
$tituloPagina = 'Tiendas';

// Cargar sociedades para filtro
$sociedades = TiendaAlta::listarSociedades($conexion);

// Filtros 
$buscar = '';
if (isset($_GET['buscar'])) {
  $buscar = $_GET['buscar'];
}

$idSociedad = '';
if (isset($_GET['sociedad'])) {
  $idSociedad = $_GET['sociedad'];
}

$filtroEstado = '';
if (isset($_GET['estado'])) {
  $filtroEstado = $_GET['estado'];
}

// Paginación
$porPagina = 10;
$pagina = 1;

if (isset($_GET['pagina'])) {
  $pagina = (int)$_GET['pagina'];

  if ($pagina < 1) {
    $pagina = 1;
  }
}

if ($action == 'cerrar' && isset($_GET['id'])) {
  if ($_SESSION['rol'] != 'admin') { header('location:index.php'); exit; }
  $id = $_GET['id'];
  // Antes de cerrar: pasar el material asignado a "pendiente de recepcion" en oficina.
  include_once '../model/Recepcion.php';
  Recepcion::crearDesdeCierre($conexion, $id, $_SESSION['usuario']);
  TiendaAlta::cerrarTienda($conexion, $id);
  $conexion = null;
  header('location:tienda.php');
  exit;
}

if ($action == 'reabrir' && isset($_GET['id'])) {
  if ($_SESSION['rol'] != 'admin') { header('location:index.php'); exit; }
  $id = $_GET['id'];
  TiendaAlta::reabrirTienda($conexion, $id);
  $conexion = null;
  header('location:tienda.php?action=ver&id=' . $id);
  exit;
}

// Devolver un material de la tienda a oficina (normalmente por averia):
// sale del slot de la tienda y queda en transito hacia oficina (panel Devoluciones).
if ($action == 'devolver' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto']) && isset($_POST['id_tienda'])) {
  if ($_SESSION['rol'] != 'admin') { header('location:index.php'); exit; }
  include_once '../model/Devolucion.php';
  $idProd = (int)$_POST['id_producto'];
  $idT = (int)$_POST['id_tienda'];
  $slot = '';
  if (isset($_POST['slot'])) { $slot = trim($_POST['slot']); }
  $motivo = '';
  if (isset($_POST['motivo'])) { $motivo = trim($_POST['motivo']); }
  Devolucion::solicitar($conexion, $idProd, $idT, $slot, $motivo, $_SESSION['usuario']);
  $conexion = null;
  header('location:tienda.php?action=ver&id=' . $idT);
  exit;
}

// Compra directa: material comprado que llega directo a la tienda (sin pasar por oficina).
// Crea el producto y lo deja asignado a un hueco de la tienda en un solo paso.
if ($action == 'compra_directa' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_tienda'])) {
  if ($_SESSION['rol'] != 'admin') { header('location:index.php'); exit; }

  $idTienda = (int)$_POST['id_tienda'];

  $idCategoria = 0;
  if (isset($_POST['id_categoria'])) { $idCategoria = (int)$_POST['id_categoria']; }
  $modelo = '';
  if (isset($_POST['modelo'])) { $modelo = trim($_POST['modelo']); }
  $subtipo = '';
  if (isset($_POST['subtipo'])) { $subtipo = trim($_POST['subtipo']); }
  $condicion = 'nuevo';
  if (isset($_POST['condicion'])) { $condicion = trim($_POST['condicion']); }
  $codigo = '';
  if (isset($_POST['codigo'])) { $codigo = trim($_POST['codigo']); }
  $slot = '';
  if (isset($_POST['slot'])) { $slot = trim($_POST['slot']); }

  $err = '';

  if ($idCategoria <= 0 || $modelo == '' || $subtipo == '') { $err = 'datos'; }
  if ($err == '' && StockAlta::condicionAsignable($condicion) == false) { $err = 'condicion'; }
  if ($err == '' && StockAlta::tiendaActiva($conexion, $idTienda) == false) { $err = 'tienda'; }

  // Código: autogenerar según la categoría si viene vacío; si viene, debe ser único
  if ($err == '') {
    if ($codigo == '') {
      $codigo = StockAlta::generarCodigoPorCategoria($conexion, $idCategoria);
    } else if (StockAlta::codigoExiste($conexion, $codigo)) {
      $err = 'codigo';
    }
  }

  // Hueco con nombre: no debe estar ocupado (el "adicional" usa un slot único)
  if ($err == '' && $slot != '' && StockAlta::slotOcupado($conexion, 'tienda', $idTienda, $slot)) {
    $err = 'ocupado';
  }

  if ($err == '') {
    $idProd = StockAlta::insertarProducto($conexion, $codigo, $modelo, $idCategoria, $condicion, $subtipo);
    $slotFinal = $slot;
    if ($slotFinal == '') { $slotFinal = 'cd_' . $idProd; }
    TiendaAlta::asignarProducto($conexion, 'tienda', $idTienda, $slotFinal, $idProd);
    $conexion = null;
    header('location:tienda.php?action=ver&id=' . $idTienda . '&cd_ok=' . $codigo);
    exit;
  }

  $conexion = null;
  header('location:tienda.php?action=ver&id=' . $idTienda . '&cd_error=' . $err);
  exit;
}

$inicio = ($pagina - 1) * $porPagina;

$totalFilas = TiendaAlta::contar($conexion, $buscar, $idSociedad, $filtroEstado);
$totalPaginas = (int)ceil($totalFilas / $porPagina);

if ($totalPaginas < 1) {
  $totalPaginas = 1;
}

if ($pagina > $totalPaginas) {
  $pagina = $totalPaginas;
}

$tiendas = TiendaAlta::listar($conexion, $buscar, $idSociedad, $inicio, $porPagina, $filtroEstado);

include_once '../view/tienda_view.php';

$conexion = null;
exit;