<?php

class TiendaAlta
{
  private static function crearFiltros($buscar, $idSociedad, $filtroEstado = '')
  {
    $filtrosSQL = " WHERE 1=1 ";

    if ($buscar != '') {
      $filtrosSQL .= " AND (t.numero LIKE '%$buscar%' OR t.nombre LIKE '%$buscar%') ";
    }

    if ($idSociedad != '') {
      $filtrosSQL .= " AND t.id_sociedad = $idSociedad ";
    }

    if ($filtroEstado != '') {
      $filtrosSQL .= " AND t.estado = '$filtroEstado' ";
    }

    return $filtrosSQL;
  }

  public static function contar($conexion, $buscar, $idSociedad, $filtroEstado = '')
  {
    $filtrosSQL = self::crearFiltros($buscar, $idSociedad, $filtroEstado);

    $consultaTotal = "SELECT t.id FROM tienda t $filtrosSQL";
    $resultadoTotal = $conexion->query($consultaTotal);

    return $resultadoTotal->rowCount();
  }

  public static function listar($conexion, $buscar, $idSociedad, $inicio, $porPagina, $filtroEstado = '')
  {
    $filtrosSQL = self::crearFiltros($buscar, $idSociedad, $filtroEstado);

    $consultaListado = "
      SELECT t.id, t.numero, t.nombre, t.estado, s.nombre AS sociedad, s.cif AS cif
      FROM tienda t
      INNER JOIN sociedad s ON s.id = t.id_sociedad
      $filtrosSQL
      ORDER BY t.id DESC
      LIMIT $inicio, $porPagina
    ";

    $resultadoListado = $conexion->query($consultaListado);

    $tiendas = array();
    while ($tienda = $resultadoListado->fetchObject()) {
      $tiendas[] = $tienda;
    }

    return $tiendas;
  }

  public static function listarSociedades($conexion)
  {
    $consultaSociedades = "SELECT id, nombre, cif FROM sociedad ORDER BY nombre ASC";
    $resultadoSociedades = $conexion->query($consultaSociedades);

    $sociedades = array();
    while ($sociedad = $resultadoSociedades->fetchObject()) {
      $sociedades[] = $sociedad;
    }

    return $sociedades;
  }

  public static function insertar($conexion, $numero, $nombre, $idSociedad, $movil, $fijo, $email, $observaciones)
  {
    $consultaInsert = "
      INSERT INTO tienda (
        numero,
        nombre,
        id_sociedad,
        movil,
        fijo,
        email,
        observaciones
      )
      VALUES (
        $numero,
        '$nombre',
        $idSociedad,
        '$movil',
        '$fijo',
        '$email',
        '$observaciones'
      )
    ";

    return $conexion->exec($consultaInsert);
  }

  public static function obtenerPorId($conexion, $id)
  {
    $consulta = "
      SELECT
        t.id,
        t.numero,
        t.nombre,
        t.movil,
        t.fijo,
        t.email,
        t.observaciones,
        t.anydesk_equipo1,
        t.pos_equipo1,
        t.caja_equipo1,
        t.anydesk_equipo2,
        t.pos_equipo2,
        t.caja_equipo2,
        t.operadora,
        t.ip_fija,
        t.vpn,
        t.id_sociedad,
        t.id_cluster_manager,
        t.estado,
        s.nombre AS sociedad,
        s.cif AS cif
      FROM tienda t
      INNER JOIN sociedad s ON s.id = t.id_sociedad
      WHERE t.id = $id
      LIMIT 1
    ";

    $resultado = $conexion->query($consulta);
    return $resultado->fetchObject();
  }

  public static function actualizar($conexion, $id, $numero, $nombre, $idSociedad, $movil, $fijo, $email, $observaciones, $idClusterManager = 0)
  {
    $clusterSQL = "NULL";
    if ($idClusterManager > 0) {
      $clusterSQL = $idClusterManager;
    }

    $consulta = "
      UPDATE tienda
      SET
        numero = $numero,
        nombre = '$nombre',
        id_sociedad = $idSociedad,
        movil = '$movil',
        fijo = '$fijo',
        email = '$email',
        observaciones = '$observaciones',
        id_cluster_manager = $clusterSQL
      WHERE id = $id
      LIMIT 1
    ";

    return $conexion->exec($consulta);
  }

  public static function actualizarConfiguracionTienda(
    $conexion,
    $id,
    $anydeskEquipo1,
    $posEquipo1,
    $cajaEquipo1,
    $anydeskEquipo2,
    $posEquipo2,
    $cajaEquipo2,
    $operadora,
    $ipFija,
    $vpn
  ) {
    $consulta = "
      UPDATE tienda
      SET
        anydesk_equipo1 = '$anydeskEquipo1',
        pos_equipo1 = '$posEquipo1',
        caja_equipo1 = '$cajaEquipo1',
        anydesk_equipo2 = '$anydeskEquipo2',
        pos_equipo2 = '$posEquipo2',
        caja_equipo2 = '$cajaEquipo2',
        operadora = '$operadora',
        ip_fija = '$ipFija',
        vpn = '$vpn'
      WHERE id = $id
      LIMIT 1
    ";

    return $conexion->exec($consulta);
  }

  public static function obtenerAsignacionesTienda($conexion, $idTienda)
  {
    if ($idTienda <= 0) {
      return array();
    }

    $consulta = "
      SELECT
        a.slot,
        p.id AS id_producto,
        p.codigo,
        p.modelo
      FROM asignacion a
      INNER JOIN producto p ON p.id = a.id_producto
      WHERE a.destino_tipo = 'tienda'
        AND a.destino_id = $idTienda
    ";

    $resultado = $conexion->query($consulta);

    $asignaciones = array();
    while ($fila = $resultado->fetchObject()) {
      $asignaciones[$fila->slot] = $fila;
    }

    return $asignaciones;
  }

  public static function listarProductosDisponiblesPorCategoria($conexion, $idCategoria)
  {
    $consulta = "
      SELECT p.id, p.codigo, p.modelo
      FROM producto p
      LEFT JOIN asignacion a ON a.id_producto = p.id
      WHERE p.id_categoria = $idCategoria
        AND p.estado IN ('nuevo','usado','reacondicionado')
        AND a.id_producto IS NULL
      ORDER BY p.codigo ASC
    ";

    $resultado = $conexion->query($consulta);

    $productos = array();
    while ($producto = $resultado->fetchObject()) {
      $productos[] = $producto;
    }

    return $productos;
  }

  // Lista telefonos moviles mostrando numero y tarifa de la ficha tecnica (disponibles + el actual de la tienda)
  public static function listarTelefonosMovilesDisponibles($conexion, $idCategoria, $codigoActual = '')
  {
    $condicionActual = "";
    if ($codigoActual != '') {
      $condicionActual = "OR p.codigo = '$codigoActual'";
    }

    $consulta = "
      SELECT
        p.id,
        p.codigo,
        pa_num.valor AS numero,
        pa_tar.valor AS tarifa
      FROM producto p
      LEFT JOIN asignacion a ON a.id_producto = p.id
      LEFT JOIN producto_atributo pa_num ON pa_num.id_producto = p.id AND pa_num.id_atributo = 14
      LEFT JOIN producto_atributo pa_tar ON pa_tar.id_producto = p.id AND pa_tar.id_atributo = 13
      WHERE p.id_categoria = $idCategoria
        AND (a.id_producto IS NULL $condicionActual)
      ORDER BY p.codigo ASC
    ";

    $resultado = $conexion->query($consulta);

    $productos = array();
    while ($producto = $resultado->fetchObject()) {
      $productos[] = $producto;
    }

    return $productos;
  }

  public static function listarProductosDisponiblesPorCategoriaYSubtipo($conexion, $idCategoria, $subLike)
  {
    $consulta = "
      SELECT p.id, p.codigo, p.modelo
      FROM producto p
      LEFT JOIN asignacion a ON a.id_producto = p.id
      WHERE p.id_categoria = $idCategoria
        AND p.estado IN ('nuevo','usado','reacondicionado')
        AND a.id_producto IS NULL
        AND p.subtipo LIKE '$subLike'
      ORDER BY p.codigo ASC
    ";

    $resultado = $conexion->query($consulta);

    $productos = array();
    while ($producto = $resultado->fetchObject()) {
      $productos[] = $producto;
    }

    return $productos;
  }

  public static function listarCajonesDisponibles($conexion)
  {
    $consulta = "
      SELECT p.id, p.codigo, p.modelo
      FROM producto p
      LEFT JOIN asignacion a ON a.id_producto = p.id
      WHERE p.estado IN ('nuevo','usado','reacondicionado')
        AND a.id_producto IS NULL
        AND (
          LOWER(p.subtipo) LIKE '%cajon%'
          OR LOWER(p.subtipo) LIKE '%cajón%'
          OR LOWER(p.modelo) LIKE '%cajon%'
          OR LOWER(p.modelo) LIKE '%cajón%'
        )
      ORDER BY p.codigo ASC
    ";

    $resultado = $conexion->query($consulta);

    $productos = array();
    while ($producto = $resultado->fetchObject()) {
      $productos[] = $producto;
    }

    return $productos;
  }

  public static function obtenerProductoPorId($conexion, $idProducto)
  {
    $consulta = "
      SELECT id, codigo, modelo, estado, id_categoria
      FROM producto
      WHERE id = $idProducto
      LIMIT 1
    ";

    $resultado = $conexion->query($consulta);
    return $resultado->fetchObject();
  }

  public static function liberarAsignacion($conexion, $destinoTipo, $destinoId, $slot)
  {
    $consulta = "
      SELECT id_producto
      FROM asignacion
      WHERE destino_tipo = '$destinoTipo'
        AND destino_id = $destinoId
        AND slot = '$slot'
      LIMIT 1
    ";

    $resultado = $conexion->query($consulta);
    $fila = $resultado->fetchObject();

    if ($fila) {
      $idProducto = $fila->id_producto;

      $consultaBorrar = "
        DELETE FROM asignacion
        WHERE destino_tipo = '$destinoTipo'
          AND destino_id = $destinoId
          AND slot = '$slot'
        LIMIT 1
      ";

      $conexion->exec($consultaBorrar);

      // La disponibilidad se deriva de la ausencia de fila en asignacion.
      // NO se toca producto.estado, que ahora es la condicion/ciclo de vida.
    }
  }

  public static function asignarProducto($conexion, $destinoTipo, $destinoId, $slot, $idProducto)
  {
    $consultaActual = "
      SELECT id_producto
      FROM asignacion
      WHERE destino_tipo = '$destinoTipo'
        AND destino_id = $destinoId
        AND slot = '$slot'
      LIMIT 1
    ";

    $resActual = $conexion->query($consultaActual);
    $actual = $resActual->fetchObject();

    if ($actual && $actual->id_producto == $idProducto) {
      return true;
    }

    $consultaOcupado = "
      SELECT destino_tipo, destino_id, slot
      FROM asignacion
      WHERE id_producto = $idProducto
      LIMIT 1
    ";

    $resOcupado = $conexion->query($consultaOcupado);
    $ocupado = $resOcupado->fetchObject();

    if ($ocupado) {
      return false;
    }

    self::liberarAsignacion($conexion, $destinoTipo, $destinoId, $slot);

    $consultaInsertar = "
      INSERT INTO asignacion (id_producto, destino_tipo, destino_id, slot)
      VALUES ($idProducto, '$destinoTipo', $destinoId, '$slot')
    ";

    $okInsert = $conexion->exec($consultaInsertar);

    if ($okInsert === false) {
      return false;
    }

    // La disponibilidad se deriva de la presencia de fila en asignacion.
    // NO se toca producto.estado, que ahora es la condicion/ciclo de vida.

    return true;
  }

  public static function productoOcupado($conexion, $idProducto)
  {
    $consulta = "SELECT id FROM asignacion WHERE id_producto = $idProducto LIMIT 1";
    $resultado = $conexion->query($consulta);

    if ($resultado->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  // Obtiene todos los trabajadores con cargo Cluster Manager
  public static function listarClusterManagers($conexion)
  {
    $consulta = "
      SELECT t.id, t.nombre
      FROM trabajador t
      WHERE LOWER(t.cargo) LIKE '%cluster manager%'
      AND t.estado = 'ACTIVO'
      ORDER BY t.nombre ASC
    ";

    $resultado = $conexion->query($consulta);
    $managers = array();
    while ($fila = $resultado->fetchObject()) {
      $managers[] = $fila;
    }
    return $managers;
  }

  // Cerrar tienda y liberar todo el material asignado
  public static function cerrarTienda($conexion, $idTienda)
  {
    // Marcar tienda como cerrada.
    // NOTA: el material asignado NO se libera aqui. Antes de llamar a este metodo,
    // Recepcion::crearDesdeCierre() genera las lineas de recepcion (pendiente) y
    // repunta las asignaciones a 'recepcion', para controlar la entrega en oficina.
    $conexion->exec("UPDATE tienda SET estado = 'cerrada' WHERE id = $idTienda");
  }

  // Reabrir tienda
  public static function reabrirTienda($conexion, $idTienda)
  {
    $conexion->exec("UPDATE tienda SET estado = 'activa' WHERE id = $idTienda");
  }

  public static function productoOcupadoEnOtroSitio($conexion, $idProducto, $destinoTipo, $destinoId, $slot)
  {
    $consulta = "
      SELECT id
      FROM asignacion
      WHERE id_producto = $idProducto
        AND NOT (
          destino_tipo = '$destinoTipo'
          AND destino_id = $destinoId
          AND slot = '$slot'
        )
      LIMIT 1
    ";

    $resultado = $conexion->query($consulta);

    if ($resultado->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }
}