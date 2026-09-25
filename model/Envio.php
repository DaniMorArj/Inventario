<?php
// Envio de material de oficina a una tienda (reposicion / sustitucion).
// Es el flujo inverso de Devolucion. Estados: preparando -> enviado -> llegado (o cancelado).
// Mientras esta en curso, el producto queda ocupado con destino_tipo='envio' (ni disponible ni
// reasignable). Al marcar "llegado" se decide el hueco de la tienda; si ese hueco ya tiene un
// equipo antiguo, se puede devolver ese antiguo a oficina en el mismo paso (emparejamiento).
class Envio
{
    // Huecos de tienda a los que puede ir un envio al llegar (valor => etiqueta).
    // '' = material adicional (sin hueco con nombre).
    public static function slotsTienda()
    {
        return array(
            'equipo1' => 'Equipo 1',
            'equipo2' => 'Equipo 2',
            'tickets1' => 'Impresora Tickets 1',
            'tickets2' => 'Impresora Tickets 2',
            'multifuncion' => 'Impresora Multifunción',
            'cajon_portamonedas' => 'Cajón Portamonedas',
            'lector_codigo1' => 'Lector Código 1',
            'lector_codigo2' => 'Lector Código 2',
            'lector_billete1' => 'Lector Billete 1',
            'lector_billete2' => 'Lector Billete 2',
            'telefono_fijo' => 'Teléfono Fijo',
            'telefono_movil' => 'Teléfono Móvil',
            'datafono' => 'Datáfono',
            'pinpad' => 'Pinpad',
            'router_sos' => 'Router SOS',
            'camara360_1' => 'Cámara 360 (1)',
            'camara360_2' => 'Cámara 360 (2)',
            'camara_fija_1' => 'Cámara Fija (1)',
            'camara_fija_2' => 'Cámara Fija (2)',
            '' => 'Material adicional (sin hueco fijo)'
        );
    }

    // Crea un envio (preparando) y ocupa el producto (destino_tipo='envio').
    // El producto debe estar disponible (sin asignacion) y en condicion asignable.
    // Devuelve el id del envio creado, o 0 si no se pudo.
    public static function crear($cn, $idProducto, $idTienda, $motivo, $usuario)
    {
        // El producto no debe estar ya ocupado en ningun sitio
        $stmt = $cn->prepare("SELECT id FROM asignacion WHERE id_producto = ? LIMIT 1");
        $stmt->execute(array($idProducto));
        if ($stmt->fetchObject()) {
            return 0;
        }

        // Debe estar en condicion asignable
        $stmt = $cn->prepare("SELECT estado FROM producto WHERE id = ? LIMIT 1");
        $stmt->execute(array($idProducto));
        $prod = $stmt->fetchObject();
        if (!$prod) {
            return 0;
        }
        if ($prod->estado != 'nuevo' && $prod->estado != 'usado' && $prod->estado != 'reacondicionado') {
            return 0;
        }

        $insert = $cn->prepare("
            INSERT INTO envio
                (id_producto, id_tienda_destino, slot_destino, motivo, estado, fecha_creacion, usuario_creacion)
            VALUES (?, ?, '', ?, 'preparando', NOW(), ?)
        ");
        $insert->execute(array($idProducto, $idTienda, $motivo, $usuario));
        $idEnvio = (int)$cn->lastInsertId();

        // Ocupar el producto: en curso hacia la tienda (aun no colocado en un hueco)
        $asig = $cn->prepare("
            INSERT INTO asignacion (id_producto, destino_tipo, destino_id, slot)
            VALUES (?, 'envio', ?, '')
        ");
        $asig->execute(array($idProducto, $idTienda));

        return $idEnvio;
    }

    // Marca un envio como enviado (salio de oficina)
    public static function marcarEnviado($cn, $idEnvio, $usuario)
    {
        $upd = $cn->prepare("
            UPDATE envio
            SET estado = 'enviado', fecha_enviado = NOW()
            WHERE id = ? AND estado = 'preparando'
        ");
        $upd->execute(array($idEnvio));
        return $upd->rowCount() > 0;
    }

    // Marca un envio como llegado y coloca el producto en un hueco de la tienda.
    // Si el hueco ya esta ocupado por otro producto:
    //   - $devolverAntiguo=true  -> ese antiguo se manda a Devoluciones (con $motivoDevolucion) y se libera el hueco.
    //   - $devolverAntiguo=false -> se aborta y se devuelve 'ocupado' (el controlador vuelve a preguntar).
    // Devuelve 'ok', 'ocupado' o 'error'.
    public static function marcarLlegado($cn, $idEnvio, $slotDestino, $devolverAntiguo, $motivoDevolucion, $usuario)
    {
        $stmt = $cn->prepare("
            SELECT id_producto, id_tienda_destino
            FROM envio
            WHERE id = ? AND estado IN ('preparando','enviado')
            LIMIT 1
        ");
        $stmt->execute(array($idEnvio));
        $envio = $stmt->fetchObject();
        if (!$envio) {
            return 'error';
        }

        $idProducto = $envio->id_producto;
        $idTienda = $envio->id_tienda_destino;

        // Hueco destino: el elegido, o uno adicional unico si no se especifica
        $targetSlot = trim($slotDestino);
        if ($targetSlot == '') {
            $targetSlot = 'env_' . $idEnvio;
        }

        $idDevolucion = null;

        // Comprobar si el hueco ya esta ocupado por OTRO producto de esta tienda
        $occ = $cn->prepare("
            SELECT id_producto
            FROM asignacion
            WHERE destino_tipo = 'tienda' AND destino_id = ? AND slot = ?
            LIMIT 1
        ");
        $occ->execute(array($idTienda, $targetSlot));
        $ocupante = $occ->fetchObject();

        if ($ocupante && $ocupante->id_producto != $idProducto) {
            if ($devolverAntiguo) {
                include_once __DIR__ . '/Devolucion.php';
                $idDevolucion = Devolucion::solicitar($cn, $ocupante->id_producto, $idTienda, $targetSlot, $motivoDevolucion, $usuario);
                if ($idDevolucion <= 0) {
                    return 'error';
                }
                $idDevolucion = (int)$idDevolucion;
            } else {
                return 'ocupado';
            }
        }

        // Colocar el producto del envio en el hueco de la tienda
        $place = $cn->prepare("
            UPDATE asignacion
            SET destino_tipo = 'tienda', destino_id = ?, slot = ?
            WHERE id_producto = ? AND destino_tipo = 'envio'
        ");
        $place->execute(array($idTienda, $targetSlot, $idProducto));

        $upd = $cn->prepare("
            UPDATE envio
            SET estado = 'llegado', fecha_llegado = NOW(), usuario_llegado = ?, slot_destino = ?, id_devolucion = ?
            WHERE id = ?
        ");
        $upd->execute(array($usuario, $targetSlot, $idDevolucion, $idEnvio));

        return 'ok';
    }

    // Cancela un envio en curso: libera el producto (vuelve a disponible) y marca cancelado.
    public static function cancelar($cn, $idEnvio)
    {
        $stmt = $cn->prepare("SELECT id_producto FROM envio WHERE id = ? AND estado IN ('preparando','enviado') LIMIT 1");
        $stmt->execute(array($idEnvio));
        $envio = $stmt->fetchObject();
        if (!$envio) {
            return false;
        }

        $del = $cn->prepare("DELETE FROM asignacion WHERE id_producto = ? AND destino_tipo = 'envio'");
        $del->execute(array($envio->id_producto));

        $upd = $cn->prepare("UPDATE envio SET estado = 'cancelado' WHERE id = ?");
        $upd->execute(array($idEnvio));

        return true;
    }

    // Numero de envios en curso (preparando + enviado) para el contador del menu
    public static function contarEnCurso($cn)
    {
        $stmt = $cn->query("SELECT COUNT(*) AS n FROM envio WHERE estado IN ('preparando','enviado')");
        $fila = $stmt->fetchObject();
        if ($fila) {
            return (int)$fila->n;
        }
        return 0;
    }

    // Envios en curso de una tienda concreta (para la ficha de la tienda)
    public static function listarPorTienda($cn, $idTienda)
    {
        $stmt = $cn->prepare("
            SELECT
                e.id, e.slot_destino, e.motivo, e.estado,
                e.fecha_creacion, e.fecha_enviado, e.fecha_llegado,
                p.codigo AS codigo, p.modelo AS modelo,
                c.nombre AS categoria
            FROM envio e
            INNER JOIN producto p ON p.id = e.id_producto
            LEFT JOIN categoria c ON c.id = p.id_categoria
            WHERE e.id_tienda_destino = ? AND e.estado IN ('preparando','enviado')
            ORDER BY e.fecha_creacion DESC, e.id DESC
        ");
        $stmt->execute(array($idTienda));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Todos los envios (para el panel global). Si $soloAbiertos, solo preparando/enviado.
    public static function listar($cn, $soloAbiertos)
    {
        $filtro = '';
        if ($soloAbiertos) {
            $filtro = "WHERE e.estado IN ('preparando','enviado')";
        }

        $consulta = "
            SELECT
                e.id, e.slot_destino, e.motivo, e.estado,
                e.fecha_creacion, e.fecha_enviado, e.fecha_llegado, e.usuario_llegado, e.usuario_creacion,
                p.codigo AS codigo, p.modelo AS modelo,
                c.nombre AS categoria,
                t.numero AS tienda_numero, t.nombre AS tienda_nombre
            FROM envio e
            INNER JOIN producto p ON p.id = e.id_producto
            LEFT JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN tienda t ON t.id = e.id_tienda_destino
            $filtro
            ORDER BY e.fecha_creacion DESC, e.id DESC
        ";
        $stmt = $cn->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Tiendas activas (destino posible de un envio)
    public static function listarTiendasActivas($cn)
    {
        $consulta = "
            SELECT id, numero, nombre
            FROM tienda
            WHERE estado = 'activa'
            ORDER BY numero ASC
        ";
        $stmt = $cn->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Productos disponibles para enviar (condicion asignable y sin asignacion)
    public static function listarDisponibles($cn)
    {
        $consulta = "
            SELECT p.id, p.codigo, p.modelo, c.nombre AS categoria
            FROM producto p
            INNER JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN asignacion a ON a.id_producto = p.id
            WHERE p.estado IN ('nuevo','usado','reacondicionado')
              AND a.id_producto IS NULL
            ORDER BY c.nombre ASC, p.codigo ASC
        ";
        $stmt = $cn->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
