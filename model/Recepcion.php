<?php
// Gestion del material pendiente de recepcion en oficina tras el cierre de una tienda.
// Fuente de verdad del workflow (pendiente/recibido/incidencia).
// El material en transito se mantiene "ocupado" repuntando su asignacion a
// destino_tipo='recepcion', para que no cuente como stock disponible ni se pueda reasignar.
class Recepcion
{
    // Al cerrar una tienda: crea una linea de recepcion (pendiente) por cada producto
    // asignado y repunta esas asignaciones a 'recepcion'. Devuelve cuantos items entran.
    public static function crearDesdeCierre($cn, $idTienda, $usuario)
    {
        // Asignaciones actuales de la tienda
        $stmt = $cn->prepare("
            SELECT id_producto, slot
            FROM asignacion
            WHERE destino_tipo = 'tienda' AND destino_id = ?
        ");
        $stmt->execute(array($idTienda));
        $asignaciones = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (count($asignaciones) == 0) {
            return 0;
        }

        // Un lote por cierre, para agrupar el material de esta tienda
        $lote = $idTienda . '_' . date('YmdHis');
        $fecha = date('Y-m-d H:i:s');

        $insert = $cn->prepare("
            INSERT INTO recepcion
                (lote, id_producto, id_tienda_origen, slot_origen, fecha_cierre, usuario_cierre, estado)
            VALUES (?, ?, ?, ?, ?, ?, 'pendiente')
        ");

        foreach ($asignaciones as $a) {
            $insert->execute(array($lote, $a->id_producto, $idTienda, $a->slot, $fecha, $usuario));
        }

        // Repuntar las asignaciones de la tienda a 'recepcion' (siguen ocupadas)
        $update = $cn->prepare("
            UPDATE asignacion
            SET destino_tipo = 'recepcion'
            WHERE destino_tipo = 'tienda' AND destino_id = ?
        ");
        $update->execute(array($idTienda));

        return count($asignaciones);
    }

    // Numero de items pendientes (para el contador del menu)
    public static function contarPendientes($cn)
    {
        $stmt = $cn->query("SELECT COUNT(*) AS n FROM recepcion WHERE estado = 'pendiente'");
        $fila = $stmt->fetchObject();
        if ($fila) {
            return (int)$fila->n;
        }
        return 0;
    }

    // Lineas de recepcion con datos de producto y tienda. Si $soloAbiertos, solo
    // devuelve lotes que tengan algun item pendiente o con incidencia.
    public static function listar($cn, $soloAbiertos)
    {
        $consulta = "
            SELECT
                r.id, r.lote, r.slot_origen, r.estado,
                r.fecha_cierre, r.usuario_cierre,
                r.fecha_recepcion, r.usuario_recepcion, r.observaciones,
                p.codigo AS codigo, p.modelo AS modelo,
                c.nombre AS categoria,
                t.numero AS tienda_numero, t.nombre AS tienda_nombre
            FROM recepcion r
            INNER JOIN producto p ON p.id = r.id_producto
            LEFT JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN tienda t ON t.id = r.id_tienda_origen
            ORDER BY r.fecha_cierre DESC, r.lote, r.estado
        ";
        $stmt = $cn->query($consulta);
        $filas = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Agrupar por lote
        $lotes = array();
        foreach ($filas as $f) {
            if (!isset($lotes[$f->lote])) {
                $lotes[$f->lote] = array(
                    'lote' => $f->lote,
                    'tienda_numero' => $f->tienda_numero,
                    'tienda_nombre' => $f->tienda_nombre,
                    'fecha_cierre' => $f->fecha_cierre,
                    'usuario_cierre' => $f->usuario_cierre,
                    'items' => array(),
                    'pendientes' => 0,
                    'recibidos' => 0,
                    'incidencias' => 0
                );
            }
            $lotes[$f->lote]['items'][] = $f;
            if ($f->estado == 'pendiente') {
                $lotes[$f->lote]['pendientes']++;
            }
            if ($f->estado == 'recibido') {
                $lotes[$f->lote]['recibidos']++;
            }
            if ($f->estado == 'incidencia') {
                $lotes[$f->lote]['incidencias']++;
            }
        }

        if ($soloAbiertos) {
            $abiertos = array();
            foreach ($lotes as $clave => $lote) {
                if ($lote['pendientes'] > 0 || $lote['incidencias'] > 0) {
                    $abiertos[$clave] = $lote;
                }
            }
            return $abiertos;
        }

        return $lotes;
    }

    // Marca un item como recibido: libera su asignacion (pasa a stock disponible).
    public static function marcarRecibido($cn, $idRecepcion, $usuario)
    {
        $stmt = $cn->prepare("SELECT id_producto FROM recepcion WHERE id = ? AND estado = 'pendiente' LIMIT 1");
        $stmt->execute(array($idRecepcion));
        $fila = $stmt->fetchObject();

        if (!$fila) {
            return false;
        }

        // Liberar la asignacion 'recepcion' de ese producto -> vuelve a disponible
        $del = $cn->prepare("DELETE FROM asignacion WHERE id_producto = ? AND destino_tipo = 'recepcion'");
        $del->execute(array($fila->id_producto));

        $upd = $cn->prepare("
            UPDATE recepcion
            SET estado = 'recibido', fecha_recepcion = NOW(), usuario_recepcion = ?
            WHERE id = ?
        ");
        $upd->execute(array($usuario, $idRecepcion));

        return true;
    }

    // Marca un item con incidencia: el material NO vuelve a disponible (sigue ocupado).
    public static function marcarIncidencia($cn, $idRecepcion, $usuario, $observaciones)
    {
        $upd = $cn->prepare("
            UPDATE recepcion
            SET estado = 'incidencia', fecha_recepcion = NOW(), usuario_recepcion = ?, observaciones = ?
            WHERE id = ? AND estado = 'pendiente'
        ");
        $upd->execute(array($usuario, $observaciones, $idRecepcion));

        return $upd->rowCount() > 0;
    }

    // Marca como recibidos todos los items pendientes de un lote (atajo "marcar todo").
    public static function marcarLoteRecibido($cn, $lote, $usuario)
    {
        $stmt = $cn->prepare("SELECT id FROM recepcion WHERE lote = ? AND estado = 'pendiente'");
        $stmt->execute(array($lote));
        $filas = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($filas as $f) {
            self::marcarRecibido($cn, $f->id, $usuario);
        }

        return count($filas);
    }
}
