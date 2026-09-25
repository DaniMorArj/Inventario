<?php

class TrabajadorCrud
{
    public static function obtenerTrabajador($cn, $idTrabajador)
    {
        $consulta = "
            SELECT
                t.*,
                c.tipo AS centro_tipo,
                c.nombre AS centro_nombre,
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

    public static function logCambioDato($cn, $idTrabajador, $usuario, $accion, $campo, $anterior, $nuevo)
    {
        if ($usuario == null) $usuario = '';
        if ($campo == null) $campo = '';
        if ($anterior == null) $anterior = '';
        if ($nuevo == null) $nuevo = '';

        $consulta = "
            INSERT INTO trabajador_historial
            (id_trabajador, usuario, accion, campo, valor_anterior, valor_nuevo)
            VALUES
            ($idTrabajador, '$usuario', '$accion', '$campo', '$anterior', '$nuevo')
        ";

        return $cn->exec($consulta);
    }

    public static function logAsignacion($cn, $idTrabajador, $slot, $accion, $idAnterior, $idNuevo, $usuario)
    {
        if ($usuario == null) $usuario = '';

        if ($idAnterior == null) {
            $idAnteriorSQL = "NULL";
        } else {
            $idAnteriorSQL = $idAnterior;
        }

        if ($idNuevo == null) {
            $idNuevoSQL = "NULL";
        } else {
            $idNuevoSQL = $idNuevo;
        }

        $consulta = "
            INSERT INTO asignacion_historial
            (id_trabajador, slot, accion, id_producto_anterior, id_producto_nuevo, usuario)
            VALUES
            ($idTrabajador, '$slot', '$accion', $idAnteriorSQL, $idNuevoSQL, '$usuario')
        ";

        return $cn->exec($consulta);
    }

    public static function actualizarTrabajador(
        $cn,
        $id,
        $nombre,
        $email,
        $idCentro,
        $idDepartamento,
        $cargo,
        $idProductoNumeroMovil,
        $observaciones,
        $usuario = null
    ) {
        $antes = self::obtenerTrabajador($cn, $id);

        if ($idDepartamento <= 0) {
            $idDepartamentoSQL = "NULL";
        } else {
            $idDepartamentoSQL = $idDepartamento;
        }

        if ($idProductoNumeroMovil <= 0) {
            $idProductoNumeroMovilSQL = "NULL";
        } else {
            $idProductoNumeroMovilSQL = $idProductoNumeroMovil;
        }

        if ($email == '') {
            $emailSQL = "NULL";
        } else {
            $emailSQL = "'$email'";
        }

        if ($cargo == '') {
            $cargoSQL = "NULL";
        } else {
            $cargoSQL = "'$cargo'";
        }

        if ($observaciones == '') {
            $observacionesSQL = "NULL";
        } else {
            $observacionesSQL = "'$observaciones'";
        }

        $consulta = "
            UPDATE trabajador
            SET nombre = '$nombre',
                email = $emailSQL,
                id_centro = $idCentro,
                id_departamento = $idDepartamentoSQL,
                cargo = $cargoSQL,
                id_producto_numero_movil = $idProductoNumeroMovilSQL,
                observaciones = $observacionesSQL
            WHERE id = $id
            LIMIT 1
        ";

        $filas = $cn->exec($consulta);

        if ($antes) {
            $despues = array(
                'nombre' => $nombre,
                'email' => $email,
                'id_centro' => $idCentro,
                'id_departamento' => $idDepartamento,
                'cargo' => $cargo,
                'id_producto_numero_movil' => $idProductoNumeroMovil,
                'observaciones' => $observaciones
            );

            self::logCambiosComparando($cn, $id, $usuario, 'EDITAR_DATOS', $antes, $despues);
        }

        return $filas;
    }

    public static function logCambiosComparando($cn, $idTrabajador, $usuario, $accion, $antesObj, $despues)
    {
        $mapAntes = array(
            'nombre' => $antesObj->nombre,
            'email' => $antesObj->email,
            'id_centro' => $antesObj->id_centro,
            'id_departamento' => $antesObj->id_departamento,
            'cargo' => $antesObj->cargo,
            'id_producto_numero_movil' => $antesObj->id_producto_numero_movil,
            'observaciones' => $antesObj->observaciones
        );

        foreach ($despues as $campo => $nuevo) {
            $ant = $mapAntes[$campo];

            if ((string)$ant != (string)$nuevo) {
                self::logCambioDato($cn, $idTrabajador, $usuario, $accion, $campo, $ant, $nuevo);
            }
        }
    }

    public static function actualizarAnydesk($cn, $idTrabajador, $any1, $any2, $usuario = null)
    {
        $antes = self::obtenerTrabajador($cn, $idTrabajador);

        if ($any1 == '') {
            $any1SQL = "NULL";
        } else {
            $any1SQL = "'$any1'";
        }

        if ($any2 == '') {
            $any2SQL = "NULL";
        } else {
            $any2SQL = "'$any2'";
        }

        $consulta = "
            UPDATE trabajador
            SET anydesk_equipo1 = $any1SQL,
                anydesk_equipo2 = $any2SQL
            WHERE id = $idTrabajador
            LIMIT 1
        ";

        $filas = $cn->exec($consulta);

        if ($antes) {
            if ((string)$antes->anydesk_equipo1 != (string)$any1) {
                self::logCambioDato($cn, $idTrabajador, $usuario, 'EDITAR_ANYDESK', 'anydesk_equipo1', $antes->anydesk_equipo1, $any1);
            }

            if ((string)$antes->anydesk_equipo2 != (string)$any2) {
                self::logCambioDato($cn, $idTrabajador, $usuario, 'EDITAR_ANYDESK', 'anydesk_equipo2', $antes->anydesk_equipo2, $any2);
            }
        }

        return $filas;
    }

    public static function asignacionesActualesPorSlot($cn, $idTrabajador)
    {
        $consulta = "
            SELECT slot, id_producto
            FROM asignacion
            WHERE destino_tipo = 'trabajador'
              AND destino_id = $idTrabajador
        ";

        $resultado = $cn->query($consulta);

        $out = array();

        while ($fila = $resultado->fetchObject()) {
            $out[$fila->slot] = (int)$fila->id_producto;
        }

        return $out;
    }

    public static function borrarAsignacionSlot($cn, $idTrabajador, $slot)
    {
        $consulta = "
            DELETE FROM asignacion
            WHERE destino_tipo = 'trabajador'
              AND destino_id = $idTrabajador
              AND slot = '$slot'
            LIMIT 1
        ";

        return $cn->exec($consulta);
    }

    public static function insertarAsignacion($cn, $idProducto, $idTrabajador, $slot)
    {
        $consulta = "
            INSERT INTO asignacion (id_producto, destino_tipo, destino_id, slot)
            VALUES ($idProducto, 'trabajador', $idTrabajador, '$slot')
        ";

        return $cn->exec($consulta);
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

    public static function setSlot($cn, $idTrabajador, $slot, $nuevoIdProducto, $usuario = null)
    {
        $actual = self::asignacionesActualesPorSlot($cn, $idTrabajador);
        $actualId = 0;

        if (isset($actual[$slot])) {
            $actualId = (int)$actual[$slot];
        }

        if ($nuevoIdProducto == $actualId) {
            return array('ok' => true, 'msg' => 'Sin cambios');
        }

        if ($nuevoIdProducto == 0) {
            if ($actualId > 0) {
                self::borrarAsignacionSlot($cn, $idTrabajador, $slot);
                self::logAsignacion($cn, $idTrabajador, $slot, 'LIBERAR', $actualId, null, $usuario);
            }

            return array('ok' => true, 'msg' => 'Slot liberado');
        }

        if (self::productoOcupado($cn, $nuevoIdProducto)) {
            return array('ok' => false, 'msg' => 'Ese producto ya está asignado');
        }

        if ($actualId > 0) {
            self::borrarAsignacionSlot($cn, $idTrabajador, $slot);
            self::insertarAsignacion($cn, $nuevoIdProducto, $idTrabajador, $slot);
            self::logAsignacion($cn, $idTrabajador, $slot, 'CAMBIAR', $actualId, $nuevoIdProducto, $usuario);

            return array('ok' => true, 'msg' => 'Cambiado correctamente');
        } else {
            self::insertarAsignacion($cn, $nuevoIdProducto, $idTrabajador, $slot);
            self::logAsignacion($cn, $idTrabajador, $slot, 'ASIGNAR', null, $nuevoIdProducto, $usuario);

            return array('ok' => true, 'msg' => 'Asignado correctamente');
        }
    }

    public static function darDeBajaTotal($cn, $idTrabajador)
    {
        // Liberar el equipo asignado (vuelve a stock disponible)
        $consulta1 = "
            DELETE FROM asignacion
            WHERE destino_tipo = 'trabajador'
              AND destino_id = $idTrabajador
        ";
        $cn->exec($consulta1);

        // Baja LÓGICA: se conserva el registro (y su histórico) marcándolo como BAJA
        // y guardando la fecha. Deja de aparecer en los listados de trabajadores activos.
        $consulta2 = "
            UPDATE trabajador
            SET id_producto_numero_movil = NULL,
                estado = 'BAJA',
                fecha_baja = NOW()
            WHERE id = $idTrabajador
            LIMIT 1
        ";
        $cn->exec($consulta2);

        return true;
    }

    // Reactiva un trabajador dado de baja: vuelve a ACTIVO y limpia la fecha de baja.
    public static function reactivar($cn, $idTrabajador)
    {
        $consulta = "
            UPDATE trabajador
            SET estado = 'ACTIVO', fecha_baja = NULL
            WHERE id = $idTrabajador
            LIMIT 1
        ";
        $cn->exec($consulta);
        return true;
    }

    public static function listarHistorial($cn, $idTrabajador, $limit = 50)
    {
        if ($limit < 1) {
            $limit = 1;
        }

        if ($limit > 200) {
            $limit = 200;
        }

        $consulta1 = "
            SELECT fecha, usuario, accion, campo,
                   valor_anterior, valor_nuevo,
                   NULL AS slot, NULL AS asignacion_accion,
                   NULL AS id_producto_anterior, NULL AS id_producto_nuevo
            FROM trabajador_historial
            WHERE id_trabajador = $idTrabajador
        ";

        $resultado1 = $cn->query($consulta1);

        $a = array();
        while ($fila = $resultado1->fetchObject()) {
            $a[] = $fila;
        }

        $consulta2 = "
            SELECT fecha, usuario,
                   'ASIGNACION' AS accion,
                   NULL AS campo,
                   NULL AS valor_anterior,
                   NULL AS valor_nuevo,
                   slot,
                   accion AS asignacion_accion,
                   id_producto_anterior,
                   id_producto_nuevo
            FROM asignacion_historial
            WHERE id_trabajador = $idTrabajador
        ";

        $resultado2 = $cn->query($consulta2);

        $b = array();
        while ($fila = $resultado2->fetchObject()) {
            $b[] = $fila;
        }

        $all = array();

        foreach ($a as $fila) {
            $all[] = $fila;
        }

        foreach ($b as $fila) {
            $all[] = $fila;
        }

        //usort($all, "ordenarHistorialPorFecha");

        return array_slice($all, 0, $limit);
    }
}

function ordenarHistorialPorFecha($x, $y)
{
    return strcmp($y->fecha, $x->fecha);
}
