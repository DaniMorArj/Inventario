<?php
session_start();// Comprobamos si la sesión existe, si no existe lo redirigimos al login
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

// Los usuarios solo pueden ver, no editar

// Activos
$active = 'stock';
$submenuStock = true;
$activeSubmenu = '';

// Acción
$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];
}

// Conexión BD
include_once '../model/InventarioDB.php';
$conexion = InventarioDB::connectDB();

require_once '../model/StockAlta.php';

// Catálogo de condiciones (ciclo de vida) para las vistas
$condiciones = StockAlta::condiciones();

/* ==========================================================
   DEVOLVER SLOTS PERMITIDOS
========================================================== */
if ($action == 'slots' && isset($_GET['id'])) {

  header('Content-Type: application/json; charset=utf-8');// Indicamos que devolvemos JSON

  $idProducto = (int)$_GET['id'];// ID del producto para el que queremos los slots permitidos
  $slots = StockAlta::slotsPermitidosParaProducto($conexion, $idProducto);// Obtenemos los slots permitidos para ese producto (debe devolver un array con los slots o false si error)

  if ($slots) {// Devolvemos los slots en formato JSON
    echo json_encode(array(
      'ok' => true,
      'slots' => $slots
    ));
  } else {// Si hubo un error, devolvemos un mensaje de error
    echo json_encode(array(
      'ok' => false,
      'error' => 'Error obteniendo slots'
    ));
  }

  $conexion = null;
  exit;
}

/* ==========================================================
   DEVOLVER SLOTS OCUPADOS
========================================================== */
if ($action == 'slots_ocupados') {

  header('Content-Type: application/json; charset=utf-8');// Indicamos que devolvemos JSON

  $destinoTipo = '';
  if (isset($_GET['destino_tipo'])) {// tipo de destino (tienda, oficina, almacen, serigrafia)
    $destinoTipo = trim($_GET['destino_tipo']);
    $destinoTipo = strtolower($destinoTipo);
  }

  $destinoId = 0;
  if (isset($_GET['destino_id'])) {// ID del destino (ejemplo: id de la tienda)
    $destinoId = (int)$_GET['destino_id'];
  }

  if ($destinoTipo != 'tienda') {// Por ahora solo tenemos tiendas, pero si quieres agregar oficinas, almacenes o serigrafía, debes validar aquí que el tipo es correcto
    echo json_encode(array(
      'ok' => false,
      'error' => 'Destino no válido'
    ));
    $conexion = null;
    exit;
  }

  if ($destinoId <= 0) {// ID destino no válido
    echo json_encode(array(
      'ok' => false,
      'error' => 'ID destino no válido'
    ));
    $conexion = null;
    exit;
  }

  // Consulta para obtener los slots ocupados en ese destino (ejemplo: tienda) y su producto asignado (si quieres mostrar código/modelo en el select de asignación)
  $consulta = "
    SELECT
      a.slot,
      p.id AS id_producto,
      p.codigo,
      p.modelo
    FROM asignacion a
    INNER JOIN producto p ON p.id = a.id_producto
    WHERE a.destino_tipo = '$destinoTipo'
      AND a.destino_id = $destinoId
  ";

  // Ejecutamos la consulta
  $resultado = $conexion->query($consulta);

  $ocupados = array();// Array para almacenar los slots ocupados

  while ($row = $resultado->fetchObject()) {// Recorremos los resultados y los almacenamos en el array de ocupados
    $slot = $row->slot;

    $ocupados[$slot] = array(// Guardamos el producto asignado a ese slot para mostrarlo en el select (opcional)
      'id_producto' => (int)$row->id_producto,
      'codigo' => $row->codigo,
      'modelo' => $row->modelo
    );
  }

  echo json_encode(array(// Devolvemos los slots ocupados en formato JSON
    'ok' => true,
    'ocupados' => $ocupados
  ));

  $conexion = null;
  exit;
}

/* ==========================================================
   VER DETALLE PRODUCTO
========================================================== */
if ($action == 'ver' && isset($_GET['id'])) {

  $tituloPagina = 'Detalle Producto';// Título de la página para la vista
  $errores = array();
  $idProducto = (int)$_GET['id'];

  // Obtenemos el producto con su asignación actual (si tiene)
  $producto = StockAlta::obtenerProductoConAsignacion($conexion, $idProducto);

  // Si no existe el producto, mostramos error y salimos (puedes redirigir a stock.php o mostrar un mensaje en la misma vista
  if (!$producto) {
    $errores[] = "Producto no encontrado.";
    include_once '../view/stock_detalle_view.php';
    $conexion = null;
    exit;
  }

  // Obtenemos los atributos del producto para mostrarlos en el detalle
  $atributosProducto = StockAlta::obtenerAtributosProducto($conexion, $idProducto);

  // ¿Su condición permite asignarlo? (solo nuevo/usado/reacondicionado)
  $puedeAsignar = StockAlta::condicionAsignable($producto->estado);

  // Link + etiqueta del destino actual del producto (si está asignado), para el botón de la
  // vista de detalle. Los destinos "fijos" (tienda/oficina/almacen/serigrafia) llevan a su
  // ficha; los estados en tránsito (envío/devolución/recepción/pack) llevan a su panel.
  $linkDestino = '';
  $linkDestinoTexto = 'Ir al destino';
  if (isset($producto->destino_tipo) && $producto->destino_tipo != '' && isset($producto->destino_id) && $producto->destino_id != '') {
    if ($producto->destino_tipo == 'tienda') {
      $linkDestino = 'tienda.php?action=ver&id=' . (int)$producto->destino_id;
      $linkDestinoTexto = 'Ir a la tienda';
    }

    if ($producto->destino_tipo == 'oficina') {
      $linkDestino = 'oficina.php?action=ver&id=' . (int)$producto->destino_id;
    }

    if ($producto->destino_tipo == 'almacen') {
      $linkDestino = 'almacen.php?action=ver&id=' . (int)$producto->destino_id;
    }

    if ($producto->destino_tipo == 'serigrafia') {
      $linkDestino = 'serigrafia.php?action=ver&id=' . (int)$producto->destino_id;
    }

    if ($producto->destino_tipo == 'envio') {
      $linkDestino = 'envio.php';
      $linkDestinoTexto = 'Ver en Envíos';
    }

    if ($producto->destino_tipo == 'devolucion') {
      $linkDestino = 'devolucion.php';
      $linkDestinoTexto = 'Ver en Devoluciones';
    }

    if ($producto->destino_tipo == 'recepcion') {
      $linkDestino = 'recepcion.php';
      $linkDestinoTexto = 'Ver en Recepciones';
    }

    if ($producto->destino_tipo == 'pack') {
      $linkDestino = 'pack.php';
      $linkDestinoTexto = 'Ver en Packs';
    }
  }

  include_once '../view/stock_detalle_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   EDITAR PRODUCTO
========================================================== */
if ($action == 'editar' && isset($_GET['id'])) {
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }


  $tituloPagina = 'Editar Producto';
  $errores = array();
  $ok = false;

  $idProducto = (int)$_GET['id'];
  $producto = StockAlta::obtenerProductoConAsignacion($conexion, $idProducto);

  if (!$producto) {
    $errores[] = "Producto no encontrado.";
    include_once '../view/stock_editar_view.php';
    $conexion = null;
    exit;
  }

  // atributos actuales
  $atributosProducto = StockAlta::obtenerAtributosProducto($conexion, $idProducto);
  
  // catálogo de atributos permitidos por categoría (los que quieres editar)
  $atributosPermitidos = StockAlta::listarAtributosPorCategoria($conexion, (int)$producto->id_categoria);

  // Mapa id_atributo => valor actual
  $valoresActuales = array();

  foreach ($atributosProducto as $a) {
    if (isset($a->valor)) {
      $valoresActuales[(int)$a->id] = (string)$a->valor;
    } else {
      $valoresActuales[(int)$a->id] = '';
    }
  }

  if (isset($_POST['guardarEdicion'])) {// Si se envió el formulario de edición

    $codigo = '';
    if (isset($_POST['codigo'])) {// Obtenemos el código del formulario (si se envió)
      $codigo = trim($_POST['codigo']);
    }

    $modelo = '';
    if (isset($_POST['modelo'])) {// Obtenemos el modelo del formulario (si se envió)
      $modelo = trim($_POST['modelo']);
    }

    $subtipo = '';
    if (isset($_POST['subtipo'])) {// Obtenemos el subtipo del formulario (si se envió)
      $subtipo = trim($_POST['subtipo']);
    }

    $estadoCond = 'usado';
    if (isset($_POST['estado'])) {
      $estadoCond = trim($_POST['estado']);
    }
    $condValidas = StockAlta::condiciones();
    if (!isset($condValidas[$estadoCond])) {
      $estadoCond = 'usado';
    }

    if ($codigo == '') $errores[] = "El código es obligatorio.";
    if ($modelo == '') $errores[] = "El modelo es obligatorio.";
    if ($subtipo == '') $errores[] = "El subtipo es obligatorio.";

    if (!isset($errores) || count($errores) == 0 && strtolower($codigo) != strtolower($producto->codigo)) {
      if (StockAlta::codigoExiste($conexion, $codigo)) {
        $errores[] = "Ese código ya existe.";
      }
    }

    if (!isset($errores) || count($errores) == 0) {

      // 1) Actualizar campos base (incluida la condición)
      StockAlta::actualizarProductoBase($conexion, $idProducto, $codigo, $modelo, $subtipo, $estadoCond);

      // 2) Guardar atributos (solo los permitidos)
      foreach ($atributosPermitidos as $att) {
        $idAtt = (int)$att->id;
        $key = 'att_' . $idAtt;

        $valor = '';
        if (isset($_POST[$key])) {
          $valor = trim($_POST[$key]);
        }
        //Si quieres permitir NULL/vacío, lo guardamos vacío; si quieres borrar, hacemos delete
        StockAlta::upsertAtributoProducto($conexion, $idProducto, $idAtt, $valor);
      }

      header('location:stock.php?action=ver&id=' . $idProducto);
      $conexion = null;
      exit;
    }
  }

  include_once '../view/stock_editar_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   LIBERAR PRODUCTO
========================================================== */
if ($action == 'liberar' && isset($_GET['id'])) {// Solo admin puede liberar un producto (quitar su asignación actual)
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }


  $idProducto = (int)$_GET['id'];// ID del producto que queremos liberar

  $producto = StockAlta::obtenerProductoConAsignacion($conexion, $idProducto);// Obtenemos el producto con su asignación actual para verificar que existe y que está asignado (si no está asignado, no tiene sentido liberarlo)

  // Si no está asignado, no hacemos nada
  if (!$producto) {
    header('location:stock.php');
    exit;
  }

  if (!isset($producto->destino_tipo) || $producto->destino_tipo == '' || !isset($producto->destino_id) || $producto->destino_id == '') {
    header('location:stock.php?action=ver&id=' . $idProducto);
    exit;
  }

  StockAlta::liberarProducto($conexion, $idProducto);

  header('location:stock.php?action=ver&id=' . $idProducto);
  $conexion = null;
  exit;
}

/* ==========================================================
   ASIGNAR PRODUCTO
========================================================== */
if ($action == 'asignar' && isset($_GET['id'])) {// Solo admin puede asignar un producto a un destino (tienda, oficina, almacen, serigrafia)
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }

  $tituloPagina = 'Asignar Producto';
  $errores = array();
  $idProducto = (int)$_GET['id'];

  // Obtenemos el producto con su asignación actual (si tiene) para verificar que existe y que no esté ya asignado (si ya está asignado, primero hay que liberarlo o cambiarlo desde su destino actual)
  $producto = StockAlta::obtenerProductoConAsignacion($conexion, $idProducto);

  if (!$producto) {
    $errores[] = "Producto no encontrado.";
    include_once '../view/stock_asignar_view.php';
    $conexion = null;
    exit;
  }

  if (isset($producto->destino_tipo) && $producto->destino_tipo != '' && isset($producto->destino_id) && $producto->destino_id != '') {
    $errores[] = "Este producto ya está asignado. Debes liberarlo/cambiarlo desde su destino.";
    include_once '../view/stock_asignar_view.php';
    $conexion = null;
    exit;
  }

  // No se puede asignar material cuya condición no sea operativa (solo nuevo/usado/reacondicionado)
  if (StockAlta::condicionAsignable($producto->estado) == false) {
    $errores[] = "No se puede asignar material en condición '" . $producto->estado . "' (solo nuevo, usado o reacondicionado).";
    include_once '../view/stock_asignar_view.php';
    $conexion = null;
    exit;
  }

  // Listas
  $tiendas = StockAlta::listarTiendas($conexion);

  // Slots permitidos para ESTE producto (para pintar el select)
  // Este método debe existir en StockAlta.php
  $slotsPermitidos = StockAlta::slotsPermitidosParaProducto($conexion, $idProducto);

  if (isset($_POST['guardarAsignacion'])) {// Si se envió el formulario de asignación

    $destino_tipo = '';
    if (isset($_POST['destino_tipo'])) {
      $destino_tipo = $_POST['destino_tipo'];
    }

    $destino_id = 0;
    if (isset($_POST['destino_id'])) {
      $destino_id = (int)$_POST['destino_id'];
    }

    $slot = '';
    if (isset($_POST['slot'])) {
      $slot = $_POST['slot'];
    }

    if ($destino_tipo == '') $errores[] = "Debes seleccionar un destino.";
    if ($destino_id == 0) $errores[] = "Debes seleccionar la tienda.";
    if ($slot == '') $errores[] = "Debes seleccionar un slot.";

    // No permitir asignar material a una tienda que no esté activa (cerrada o en apertura).
    if (count($errores) == 0 && $destino_tipo == 'tienda' && StockAlta::tiendaActiva($conexion, $destino_id) == false) {
      $errores[] = "No se puede asignar material a una tienda cerrada o en apertura.";
    }

    // No permitir asignar material no operativo (averiado / para piezas / desechado).
    if (count($errores) == 0 && StockAlta::condicionAsignable($producto->estado) == false) {
      $errores[] = "No se puede asignar material en estado '" . $producto->estado . "' (solo nuevo, usado o reacondicionado).";
    }

    // Validación extra: slot debe estar permitido para ese producto
    if (!isset($errores) || count($errores) == 0) {
      if (!isset($slotsPermitidos[$slot])) {
        $errores[] = "Slot no válido para este producto.";
      }
    }

    if (!isset($errores) || count($errores) == 0 && StockAlta::slotOcupado($conexion, $destino_tipo, $destino_id, $slot)) {
      $errores[] = "Ese slot ya está ocupado en el destino seleccionado.";
    }

    if (!isset($errores) || count($errores) == 0 && StockAlta::productoOcupado($conexion, $idProducto)) {
      $errores[] = "Este producto ya está asignado en otro sitio.";
    }

    if (!isset($errores) || count($errores) == 0) {
      $filas = StockAlta::asignarProducto($conexion, $idProducto, $destino_tipo, $destino_id, $slot);

      if ($filas == 1) {
        header('location:stock.php');
        exit;
      } else {
        $errores[] = "No se pudo asignar el producto.";
      }
    }
  }

  include_once '../view/stock_asignar_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   ALTA PRODUCTO
========================================================== */
if ($action == 'alta') {// Solo admin puede dar de alta un producto
  if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
  }

  // Submenú stock alta
  $cat = '';
  if (isset($_GET['cat'])) {
    $cat = trim($_GET['cat']);
    $cat = strtolower($cat);
  }

  $activeSubmenu = $cat;// Para marcar el submenú activo en la vista (ejemplo: 'camisetas', 'tazas', etc.)

  $errores = array();
  $ok = false;

  // Lista de categorías reales para ese submenú
  $categoriasAlta = StockAlta::obtenerCategoriasAltaPorSlug($conexion, $cat);

  // Determinar categoría elegida (para pintar atributos)
  $idCategoriaElegida = 0;
  
  if (count($categoriasAlta) == 1) {
    $idCategoriaElegida = (int)$categoriasAlta[0]->id;
  }

  if (count($categoriasAlta) > 1) {
    if (isset($_POST['id_categoria'])) {
      $idCategoriaElegida = (int)$_POST['id_categoria'];
    }
  }

  // Atributos de la categoría elegida (si hay)
  $atributosCategoria = array();

  if ($idCategoriaElegida > 0) {
    $atributosCategoria = StockAlta::listarAtributosPorCategoria($conexion, $idCategoriaElegida);
  }

  // Guardar
  if (isset($_POST['guardarProducto'])) {

    $codigo = '';
    if (isset($_POST['codigo'])) {
      $codigo = trim($_POST['codigo']);
    }

    $modelo = '';
    if (isset($_POST['modelo'])) {
      $modelo = trim($_POST['modelo']);
    }

    $subtipo = '';
    if (isset($_POST['subtipo'])) {
      $subtipo = trim($_POST['subtipo']);
    }

    $estado = 'nuevo';
    if (isset($_POST['estado'])) {
      $estado = trim($_POST['estado']);
    }
    $condValidas = StockAlta::condiciones();
    if (!isset($condValidas[$estado])) {
      $estado = 'nuevo';
    }

    $idCategoria = 0;

    if (count($categoriasAlta) > 1) {
      if (isset($_POST['id_categoria'])) {
        $idCategoria = (int)$_POST['id_categoria'];
      }
    }

    if (count($categoriasAlta) == 1) {
      $idCategoria = (int)$categoriasAlta[0]->id;
    }

    if ($codigo == '') $errores[] = "El código es obligatorio";
    if ($modelo == '') $errores[] = "El modelo es obligatorio";
    if ($subtipo == '') $errores[] = "El subtipo es obligatorio";

    if (!isset($categoriasAlta) || $categoriasAlta == '') $errores[] = "Categoría no válida.";
    if ($idCategoria <= 0) $errores[] = "Debes seleccionar un tipo válido.";

    if (!isset($errores) || count($errores) == 0 && StockAlta::codigoExiste($conexion, $codigo)) {
      $errores[] = "Ese código ya existe";
    }

    // Recargar atributos según categoría final seleccionada
    $atributosCategoria = array();

    if ($idCategoria > 0) {
      $atributosCategoria = StockAlta::listarAtributosPorCategoria($conexion, $idCategoria);
    }

    // Validar requeridos (si algún atributo está marcado como requerido)
    $attrsPost = array();

    if (isset($_POST['attrs'])) {
      $attrsPost = $_POST['attrs'];
    }

    if (!isset($errores) || count($errores) == 0 && isset($atributosCategoria) && $atributosCategoria != '') {
      foreach ($atributosCategoria as $a) {

        $req = 0;
        if (isset($a->requerido)) {
          $req = (int)$a->requerido;
        }

        if ($req == 1) {
          $val = '';

          if (isset($attrsPost[$a->id])) {
            $val = trim($attrsPost[$a->id]);
          }

          if ($val == '') {
            $errores[] = "El campo \"" . $a->nombre . "\" es obligatorio.";
          }
        }
      }
    }

    if (!isset($errores) || count($errores) == 0) {// Si no hay errores, insertamos el producto y sus atributos (si los hay)

      // Insertar producto
      $idProducto = StockAlta::insertarProducto($conexion, $codigo, $modelo, $idCategoria, $estado, $subtipo);

      // Insertar atributos (si hay)
      if ($idProducto > 0) {

        if (count($attrsPost) > 0) {
          StockAlta::guardarAtributosProducto($conexion, $idProducto, $attrsPost, $errores);
        }

        if (!isset($errores) || count($errores) == 0) {
          header('location:stock.php');
          exit;
        } else {
          $errores[] = "Error al guardar los atributos del producto.";
        }

      } else {
        $errores[] = "Error al insertar el producto.";
      }
    }
  }

  $tituloPagina = 'Alta Producto';
  include_once '../view/stock_alta_view.php';
  $conexion = null;
  exit;
}

/* ==========================================================
   LISTADO PRINCIPAL
========================================================== */
$tituloPagina = 'Stock';

// Filtros
$buscar = '';
if (isset($_GET['buscar'])) {
  $buscar = trim($_GET['buscar']);
}

$filtroTipo = '';
if (isset($_GET['tipo'])) {
  $filtroTipo = trim($_GET['tipo']);
}

$orden = '';
if (isset($_GET['orden'])) {
  $orden = trim($_GET['orden']);
}

$filtroCondicion = '';
if (isset($_GET['condicion'])) {
  $filtroCondicion = trim($_GET['condicion']);
}

//Paginación
$porPagina = 6;
$pagina = 1;

if (isset($_GET['pagina'])) {
  $pagina = (int)$_GET['pagina'];
}

if ($pagina < 1) {
  $pagina = 1;
}

$inicio = ($pagina - 1) * $porPagina;

$totalFilas = StockAlta::contarProductos($conexion, $buscar, $filtroTipo, $filtroCondicion);
$totalPaginas = (int)ceil($totalFilas / $porPagina);

if ($totalPaginas < 1) {
  $totalPaginas = 1;
}

if ($pagina > $totalPaginas) {
  $pagina = $totalPaginas;
}

// Productos
$productos = StockAlta::listarProductos($conexion, $buscar, $filtroTipo, $orden, $inicio, $porPagina, $filtroCondicion);

// Resumen TOP (cards superiores)
$resumenTop = StockAlta::resumenTop($conexion);

$resultadoCategorias = $conexion->query("SELECT id, nombre FROM categoria ORDER BY nombre");

// Resumen por categorías
$categorias = array();
while ($cat = $resultadoCategorias->fetchObject()) {
  $categorias[] = $cat;
}

$resumenCategorias = array();

foreach ($categorias as $c) {
  $resumen = new stdClass();

  $resumen->id = (int)$c->id;
  $resumen->nombre = $c->nombre;
  $resumen->total = StockAlta::contarTotalPorCategoria($conexion, (int)$c->id);
  $resumen->disponibles = StockAlta::contarDisponiblesPorCategoria($conexion, (int)$c->id);

  $resumenCategorias[] = $resumen;
}

include_once '../view/stock_view.php';

$conexion = null;
exit;