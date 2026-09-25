<?php
// Gestion del material que una tienda devuelve a oficina (normalmente por averia).
// Es el flujo hermano de Recepcion, pero por item suelto en lugar de por cierre de tienda.
// Mientras esta en transito, la asignacion del producto se repunta a destino_tipo='devolucion'
// para que no cuente como disponible ni sea reasignable. Al recibirlo en oficina se libera y
// se le fija la condicion de desenlace (averiado / en garantia / en reparacion / piezas / desechado).
class Devolucion
{
    // Solicita la devolucion de un producto asignado a una tienda: crea la linea (pendiente)
    // y repunta su asignacion a 'devolucion'. Devuelve el id de la devolucion creada, o 0 si falla.
    public static function solicitar($cn, $idProducto, $idTienda, $slot, $motivo, $usuario)
    {
        // Comprobar que el producto esta realmente asignado a esa tienda
        $stmt = $cn->prepare("
            SELECT id_producto
            FROM asignacion
            WHERE id_producto = ? AND destino_tipo = 'tienda' AND destino_id = ?
            LIMIT 1
        ");
        $stmt->execute(array($idProducto, $idTienda));
        if (!$stmt->fetchObject()) {
            return 0;
        }

        $insert = $cn->prepare("
            INSERT INTO devolucion
                (id_producto, id_tienda_origen, slot_origen, motivo, fecha_solicitud, usuario_solicitud, estado)
            VALUES (?, ?, ?, ?, NOW(), ?, 'pendiente')
        ");
        $insert->execute(array($idProducto, $idTienda, $slot, $motivo, $usuario));
        $idDevolucion = (int)$cn->lastInsertId();

        // Repuntar la asignacion de la tienda a 'devolucion' (sigue ocupada, en transito a oficina)
        $update = $cn->prepare("
            UPDATE asignacion
            SET destino_tipo = 'devolucion'
            WHERE id_producto = ? AND destino_tipo = 'tienda' AND destino_id = ?
        ");
        $update->execute(array($idProducto, $idTienda));

        return $idDevolucion;
    }

    // Numero de devoluciones pendientes (para el contador del menu)
    public static function contarPendientes($cn)
    {
        $stmt = $cn->query("SELECT COUNT(*) AS n FROM devolucion WHERE estado = 'pendiente'");
        $fila = $stmt->fetchObject();
        if ($fila) {
            return (int)$fila->n;
        }
        return 0;
    }

    // Lineas de devolucion con datos de producto y tienda. Si $soloAbiertas, solo
    // devuelve las pendientes o con incidencia.
    public static function listar($cn, $soloAbiertas)
    {
        $filtro = '';
        if ($soloAbiertas) {
            $filtro = "WHERE d.estado IN ('pendiente','incidencia')";
        }

        $consulta = "
            SELECT
                d.id, d.slot_origen, d.motivo, d.estado,
                d.fecha_solicitud, d.usuario_solicitud,
                d.fecha_recepcion, d.usuario_recepcion,
                d.condicion_final, d.observaciones,
                p.codigo AS codigo, p.modelo AS modelo,
                c.nombre AS categoria,
                t.numero AS tienda_numero, t.nombre AS tienda_nombre
            FROM devolucion d
            INNER JOIN producto p ON p.id = d.id_producto
            LEFT JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN tienda t ON t.id = d.id_tienda_origen
            $filtro
            ORDER BY d.fecha_solicitud DESC, d.id DESC
        ";
        $stmt = $cn->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Recibe el material en oficina: libera su asignacion (vuelve a stock) y le fija
    // la condicion de desenlace elegida. Devuelve true si se aplico.
    public static function marcarRecibido($cn, $idDevolucion, $usuario, $condicionFinal)
    {
        $stmt = $cn->prepare("SELECT id_producto FROM devolucion WHERE id = ? AND estado = 'pendiente' LIMIT 1");
        $stmt->execute(array($idDevolucion));
        $fila = $stmt->fetchObject();

        if (!$fila) {
            return false;
        }

        // Liberar la asignacion 'devolucion' del producto -> deja de estar ocupado
        $del = $cn->prepare("DELETE FROM asignacion WHERE id_producto = ? AND destino_tipo = 'devolucion'");
        $del->execute(array($fila->id_producto));

        // Fijar la condicion de desenlace en el producto
        $updProd = $cn->prepare("UPDATE producto SET estado = ? WHERE id = ?");
        $updProd->execute(array($condicionFinal, $fila->id_producto));

        $upd = $cn->prepare("
            UPDATE devolucion
            SET estado = 'recibido', fecha_recepcion = NOW(), usuario_recepcion = ?, condicion_final = ?
            WHERE id = ?
        ");
        $upd->execute(array($usuario, $condicionFinal, $idDevolucion));

        return true;
    }

    // Marca una devolucion con incidencia: el material NO vuelve a stock (sigue en transito).
    public static function marcarIncidencia($cn, $idDevolucion, $usuario, $observaciones)
    {
        $upd = $cn->prepare("
            UPDATE devolucion
            SET estado = 'incidencia', fecha_recepcion = NOW(), usuario_recepcion = ?, observaciones = ?
            WHERE id = ? AND estado = 'pendiente'
        ");
        $upd->execute(array($usuario, $observaciones, $idDevolucion));

        return $upd->rowCount() > 0;
    }
}
