<?php

class TrabajadorListado
{
  /* ==========================
      LISTADOS (CENTRO)
  ========================== */

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

  public static function contarTrabajadores($cn, $tipoCentro, $buscar, $idDepto, $estado = 'ACTIVO')
  {
    $where = " WHERE c.tipo = '$tipoCentro' AND t.estado = '$estado' ";

    if ($buscar != '') {
      $where .= " AND (t.nombre LIKE '%$buscar%' OR t.email LIKE '%$buscar%') ";
    }

    if ($idDepto > 0) {
      $where .= " AND t.id_departamento = $idDepto ";
    }

    $consulta = "
      SELECT t.id
      FROM trabajador t
      INNER JOIN centro c ON c.id = t.id_centro
      $where
    ";

    $resultado = $cn->query($consulta);
    return $resultado->rowCount();
  }

  public static function listarTrabajadores($cn, $tipoCentro, $buscar, $idDepto, $orden, $inicio, $porPagina, $estado = 'ACTIVO')
  {
    $where = " WHERE c.tipo = '$tipoCentro' AND t.estado = '$estado' ";

    if ($buscar != '') {
      $where .= " AND (t.nombre LIKE '%$buscar%' OR t.email LIKE '%$buscar%') ";
    }

    if ($idDepto > 0) {
      $where .= " AND t.id_departamento = $idDepto ";
    }

    $orderBy = " ORDER BY t.nombre ASC ";
    if ($orden == 'nombre_desc') {
      $orderBy = " ORDER BY t.nombre DESC ";
    }
    if ($orden == 'depto_asc') {
      $orderBy = " ORDER BY d.nombre ASC, t.nombre ASC ";
    }
    if ($orden == 'depto_desc') {
      $orderBy = " ORDER BY d.nombre DESC, t.nombre ASC ";
    }

    $consulta = "
      SELECT
        t.id,
        t.nombre,
        t.email,
        t.cargo,
        t.id_departamento,
        t.fecha_baja,
        d.nombre AS departamento,
        d.nombre AS departamento_nombre,
        c.id AS centro_id,
        c.nombre AS centro_nombre,
        c.tipo AS centro_tipo
      FROM trabajador t
      INNER JOIN centro c ON c.id = t.id_centro
      LEFT JOIN departamento d ON d.id = t.id_departamento
      $where
      $orderBy
      LIMIT $inicio, $porPagina
    ";

    $resultado = $cn->query($consulta);

    $trabajadores = array();
    while ($trabajador = $resultado->fetchObject()) {
      $trabajadores[] = $trabajador;
    }

    return $trabajadores;
  }

  /* ==========================
      DETALLE TRABAJADOR
  ========================== */

  public static function obtenerTrabajador($cn, $idTrabajador)
  {
    $consulta = "
      SELECT
        t.*,
        c.nombre AS centro_nombre,
        c.tipo AS centro_tipo,
        d.nombre AS departamento,
        d.nombre AS departamento_nombre
      FROM trabajador t
      INNER JOIN centro c ON c.id = t.id_centro
      LEFT JOIN departamento d ON d.id = t.id_departamento
      WHERE t.id = $idTrabajador
      LIMIT 1
    ";

    $resultado = $cn->query($consulta);
    return $resultado->fetchObject();
  }

  public static function listarAsignacionesTrabajador($cn, $idTrabajador)
  {
    $consulta = "
      SELECT
        a.slot,
        p.id AS id_producto,
        p.codigo,
        p.modelo,
        p.subtipo,
        c.nombre AS categoria_nombre,
        c.nombre AS categoria
      FROM asignacion a
      INNER JOIN producto p ON p.id = a.id_producto
      INNER JOIN categoria c ON c.id = p.id_categoria
      WHERE a.destino_tipo = 'trabajador'
        AND a.destino_id = $idTrabajador
      ORDER BY a.slot ASC
    ";

    $resultado = $cn->query($consulta);

    $asignaciones = array();
    while ($asignacion = $resultado->fetchObject()) {
      $asignaciones[] = $asignacion;
    }

    return $asignaciones;
  }
}