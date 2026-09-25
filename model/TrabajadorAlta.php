<?php

class TrabajadorAlta
{
  public static function listarCentrosPorTipo($cn, $tipo)
  {
    $consulta = "SELECT id, tipo, nombre FROM centro WHERE tipo = '$tipo' ORDER BY nombre ASC";
    $resultado = $cn->query($consulta);

    $centros = array();
    while ($centro = $resultado->fetchObject()) {
      $centros[] = $centro;
    }

    return $centros;
  }

  public static function obtenerCentro($cn, $idCentro, $tipo)
  {
    $consulta = "SELECT id, tipo, nombre FROM centro WHERE id = $idCentro AND tipo = '$tipo' LIMIT 1";
    $resultado = $cn->query($consulta);

    return $resultado->fetchObject();
  }

  public static function listarDepartamentos($cn)
  {
    $consulta = "SELECT id, nombre FROM departamento ORDER BY nombre ASC";
    $resultado = $cn->query($consulta);

    $departamentos = array();
    while ($departamento = $resultado->fetchObject()) {
      $departamentos[] = $departamento;
    }

    return $departamentos;
  }

  public static function listarProductosDisponiblesPorCategorias($cn, $nombresCategoria, $subtipo = '')
  {
    if (count($nombresCategoria) == 0) {
      return array();
    }

    $condicion = "";

    foreach ($nombresCategoria as $i => $nombreCategoria) {
      if ($i == 0) {
        $condicion .= " c.nombre = '$nombreCategoria' ";
      } else {
        $condicion .= " OR c.nombre = '$nombreCategoria' ";
      }
    }

    $subtipoSQL = "";
    if ($subtipo != '') {
      $subtipoSQL = " AND p.subtipo = '$subtipo' ";
    }

    $consulta = "
      SELECT
        p.id, p.codigo, p.modelo, c.nombre AS categoria_nombre, p.subtipo,
        pa_num.valor AS numero,
        pa_tar.valor AS tarifa
      FROM producto p
      INNER JOIN categoria c ON c.id = p.id_categoria
      LEFT JOIN asignacion a ON a.id_producto = p.id
      LEFT JOIN producto_atributo pa_num ON pa_num.id_producto = p.id AND pa_num.id_atributo = 14
      LEFT JOIN producto_atributo pa_tar ON pa_tar.id_producto = p.id AND pa_tar.id_atributo = 13
      WHERE a.id_producto IS NULL
      AND ($condicion)
      $subtipoSQL
      ORDER BY p.codigo ASC
    ";

    $resultado = $cn->query($consulta);

    $productos = array();
    while ($producto = $resultado->fetchObject()) {
      $productos[] = $producto;
    }

    return $productos;
  }

  public static function productoOcupado($cn, $idProducto)
  {
    $consulta = "SELECT id FROM asignacion WHERE id_producto = $idProducto LIMIT 1";
    $resultado = $cn->query($consulta);

    if ($resultado->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public static function insertarTrabajador(
    $cn,
    $idCentro,
    $nombre,
    $email,
    $idDepartamento,
    $cargo,
    $idProductoNumeroMovil,
    $anydesk1,
    $anydesk2,
    $observaciones
  ) {
    if ($email == '') {
      $email = null;
    }

    if ($cargo == '') {
      $cargo = null;
    }

    if ($anydesk1 == '') {
      $anydesk1 = null;
    }

    if ($anydesk2 == '') {
      $anydesk2 = null;
    }

    if ($observaciones == '') {
      $observaciones = null;
    }

    if ($idDepartamento == 0) {
      $idDepartamento = "NULL";
    }

    if ($idProductoNumeroMovil == 0) {
      $idProductoNumeroMovil = "NULL";
    }

    if ($email === null) {
      $emailSQL = "NULL";
    } else {
      $emailSQL = "'$email'";
    }

    if ($cargo === null) {
      $cargoSQL = "NULL";
    } else {
      $cargoSQL = "'$cargo'";
    }

    if ($anydesk1 === null) {
      $anydesk1SQL = "NULL";
    } else {
      $anydesk1SQL = "'$anydesk1'";
    }

    if ($anydesk2 === null) {
      $anydesk2SQL = "NULL";
    } else {
      $anydesk2SQL = "'$anydesk2'";
    }

    if ($observaciones === null) {
      $observacionesSQL = "NULL";
    } else {
      $observacionesSQL = "'$observaciones'";
    }

    $consulta = "
      INSERT INTO trabajador
      (id_centro, nombre, email, id_departamento, cargo, id_producto_numero_movil, anydesk_equipo1, anydesk_equipo2, observaciones)
      VALUES
      (
        $idCentro,
        '$nombre',
        $emailSQL,
        $idDepartamento,
        $cargoSQL,
        $idProductoNumeroMovil,
        $anydesk1SQL,
        $anydesk2SQL,
        $observacionesSQL
      )
    ";

    $cn->exec($consulta);
    return $cn->lastInsertId();
  }

  public static function asignarProductoATrabajador($cn, $idProducto, $idTrabajador, $slot)
  {
    if (self::productoOcupado($cn, $idProducto)) {
      return 0;
    }

    $consulta = "
      INSERT INTO asignacion (id_producto, destino_tipo, destino_id, slot)
      VALUES ($idProducto, 'trabajador', $idTrabajador, '$slot')
    ";

    return $cn->exec($consulta);
  }
}