<?php
// Packs de apertura: material informatico preparado para una tienda que va a abrir.
// El producto metido en un pack queda "ocupado" (asignacion destino_tipo='pack'),
// asi no cuenta como disponible ni se puede reasignar (mismo enfoque que Recepcion).
// Al abrir la tienda, esas asignaciones se repuntan a 'tienda' (material de la tienda).
class Pack
{
    // Genera el siguiente codigo de control: PACK + 8 digitos.
    // (Antes era 'OR...', que se confundia con los codigos OR de los ordenadores.)
    public static function generarCodigo($cn)
    {
        $stmt = $cn->query("SELECT MAX(CAST(SUBSTRING(codigo,5) AS UNSIGNED)) AS maxnum FROM pack WHERE codigo LIKE 'PACK%'");
        $fila = $stmt->fetchObject();
        $siguiente = 1;
        if ($fila && $fila->maxnum !== null) {
            $siguiente = (int)$fila->maxnum + 1;
        }
        return 'PACK' . str_pad($siguiente, 8, '0', STR_PAD_LEFT);
    }

    // Crea un pack para una tienda en apertura. Devuelve el id del pack.
    public static function crearPack($cn, $idTienda, $usuario)
    {
        $codigo = self::generarCodigo($cn);
        $stmt = $cn->prepare("INSERT INTO pack (codigo, id_tienda, usuario_creacion) VALUES (?, ?, ?)");
        $stmt->execute(array($codigo, $idTienda, $usuario));
        return $cn->lastInsertId();
    }

    // Tiendas en apertura que todavia no tienen un pack activo (para crear pack).
    public static function tiendasSinPack($cn)
    {
        $consulta = "
            SELECT t.id, t.numero, t.nombre
            FROM tienda t
            WHERE t.estado = 'en_apertura'
              AND t.id NOT IN (SELECT id_tienda FROM pack WHERE estado != 'cancelado')
            ORDER BY t.numero
        ";
        $stmt = $cn->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Listado de packs con datos de la tienda y numero de items.
    public static function listar($cn)
    {
        $consulta = "
            SELECT p.id, p.codigo, p.estado, p.fecha_creacion, p.usuario_creacion,
                   p.fecha_envio, p.fecha_entrega,
                   t.numero AS tienda_numero, t.nombre AS tienda_nombre,
                   (SELECT COUNT(*) FROM pack_linea pl WHERE pl.id_pack = p.id) AS items
            FROM pack p
            LEFT JOIN tienda t ON t.id = p.id_tienda
            ORDER BY p.fecha_creacion DESC
        ";
        $stmt = $cn->query($consulta);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function obtener($cn, $idPack)
    {
        $stmt = $cn->prepare("
            SELECT p.*, t.numero AS tienda_numero, t.nombre AS tienda_nombre, t.estado AS tienda_estado
            FROM pack p
            LEFT JOIN tienda t ON t.id = p.id_tienda
            WHERE p.id = ? LIMIT 1
        ");
        $stmt->execute(array($idPack));
        return $stmt->fetchObject();
    }

    // Productos actualmente dentro del pack.
    public static function lineas($cn, $idPack)
    {
        $stmt = $cn->prepare("
            SELECT pl.id AS id_linea, pl.id_producto,
                   p.codigo, p.modelo, p.subtipo, p.id_categoria,
                   c.nombre AS categoria
            FROM pack_linea pl
            INNER JOIN producto p ON p.id = pl.id_producto
            LEFT JOIN categoria c ON c.id = p.id_categoria
            WHERE pl.id_pack = ?
            ORDER BY c.nombre, p.codigo
        ");
        $stmt->execute(array($idPack));
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Plantilla estandar (checklist) con nombre de categoria.
    public static function plantilla($cn)
    {
        $stmt = $cn->query("
            SELECT pp.id, pp.id_categoria, pp.subtipo, pp.cantidad, pp.orden,
                   c.nombre AS categoria
            FROM pack_plantilla pp
            INNER JOIN categoria c ON c.id = pp.id_categoria
            ORDER BY pp.orden
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Estado del checklist del pack: por cada linea de plantilla, cuantos hay / cuantos faltan.
    // Devuelve array de lineas + flag 'completo' global.
    public static function checklist($cn, $idPack)
    {
        $plantilla = self::plantilla($cn);
        $lineas = self::lineas($cn, $idPack);

        $resultado = array();
        $completo = true;

        foreach ($plantilla as $pl) {
            $tiene = 0;
            foreach ($lineas as $l) {
                if ($l->id_categoria == $pl->id_categoria) {
                    if ($pl->subtipo === null || $pl->subtipo == '' || $l->subtipo == $pl->subtipo) {
                        $tiene++;
                    }
                }
            }
            $falta = $pl->cantidad - $tiene;
            if ($falta < 0) { $falta = 0; }
            if ($tiene < $pl->cantidad) { $completo = false; }

            $resultado[] = array(
                'id_categoria' => $pl->id_categoria,
                'categoria' => $pl->categoria,
                'subtipo' => $pl->subtipo,
                'cantidad' => $pl->cantidad,
                'tiene' => $tiene,
                'falta' => $falta
            );
        }

        return array('lineas' => $resultado, 'completo' => $completo);
    }

    // Productos disponibles (no ocupados) de una categoria/subtipo, para elegir al añadir.
    public static function disponiblesPara($cn, $idCategoria, $subtipo)
    {
        $sql = "
            SELECT p.id, p.codigo, p.modelo, p.subtipo
            FROM producto p
            WHERE p.id_categoria = ?
              AND p.id NOT IN (SELECT id_producto FROM asignacion)
        ";
        $params = array($idCategoria);
        if ($subtipo !== null && $subtipo != '') {
            $sql .= " AND p.subtipo = ? ";
            $params[] = $subtipo;
        }
        $sql .= " ORDER BY p.codigo ";
        $stmt = $cn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Añade un producto al pack y lo reserva (ocupado). Devuelve true si se añadio.
    public static function anadirProducto($cn, $idPack, $idProducto)
    {
        // No añadir si el producto ya esta ocupado en algun sitio
        $chk = $cn->prepare("SELECT id FROM asignacion WHERE id_producto = ? LIMIT 1");
        $chk->execute(array($idProducto));
        if ($chk->fetchObject()) {
            return false;
        }

        $ins = $cn->prepare("INSERT INTO pack_linea (id_pack, id_producto) VALUES (?, ?)");
        $ins->execute(array($idPack, $idProducto));

        // Reservar: ocupa el producto con destino 'pack'
        $res = $cn->prepare("INSERT INTO asignacion (id_producto, destino_tipo, destino_id, slot) VALUES (?, 'pack', ?, 'pack')");
        $res->execute(array($idProducto, $idPack));

        return true;
    }

    // Quita un producto del pack y lo libera (vuelve a disponible).
    public static function quitarProducto($cn, $idPack, $idProducto)
    {
        $del = $cn->prepare("DELETE FROM pack_linea WHERE id_pack = ? AND id_producto = ?");
        $del->execute(array($idPack, $idProducto));

        $lib = $cn->prepare("DELETE FROM asignacion WHERE id_producto = ? AND destino_tipo = 'pack' AND destino_id = ?");
        $lib->execute(array($idProducto, $idPack));

        return true;
    }

    // Cambia el estado del pack (marcar completo/enviado/entregado).
    public static function cambiarEstado($cn, $idPack, $estado)
    {
        if ($estado == 'enviado') {
            $stmt = $cn->prepare("UPDATE pack SET estado = 'enviado', fecha_envio = NOW() WHERE id = ?");
        } else if ($estado == 'entregado') {
            $stmt = $cn->prepare("UPDATE pack SET estado = 'entregado', fecha_entrega = NOW() WHERE id = ?");
        } else {
            $stmt = $cn->prepare("UPDATE pack SET estado = ? WHERE id = ?");
            $stmt->execute(array($estado, $idPack));
            return true;
        }
        $stmt->execute(array($idPack));
        return true;
    }

    // Slots del detalle de tienda por categoria (mismos ids que usa el detalle de tienda).
    // El material cuya categoria no tiene slot (Raton, HUB USB...) va como "material adicional".
    public static function slotsPorCategoria()
    {
        return array(
            1  => array('equipo1', 'equipo2'),               // Ordenador
            2  => array('tickets1', 'tickets2'),             // Impresora Tickets
            3  => array('multifuncion'),                     // Impresoras Multifuncion
            4  => array('lector_codigo1', 'lector_codigo2'), // Lector Codigo
            5  => array('lector_billete1', 'lector_billete2'), // Lector Billete
            6  => array('router_sos'),                       // Router
            9  => array('datafono'),                         // Datafono
            10 => array('pinpad'),                           // Pinpad
            13 => array('telefono_fijo'),                    // Telefono Fijo
            19 => array('cajon_portamonedas'),               // Cajon Portamonedas
            20 => array('telefono_movil')                    // Telefono Movil
        );
    }

    // Repunta las asignaciones 'pack' a la tienda, colocando cada producto en su slot.
    // Los que no tienen slot definido van a un slot unico 'pack_<idproducto>' (material adicional).
    public static function materializarEnTienda($cn, $idPack, $idTienda)
    {
        // Slots ya ocupados en la tienda (normalmente vacia si es nueva)
        $ocupados = array();
        $q = $cn->prepare("SELECT slot FROM asignacion WHERE destino_tipo = 'tienda' AND destino_id = ?");
        $q->execute(array($idTienda));
        foreach ($q->fetchAll(PDO::FETCH_OBJ) as $f) {
            $ocupados[$f->slot] = true;
        }

        $mapa = self::slotsPorCategoria();
        $usados = array();

        $stmt = $cn->prepare("
            SELECT a.id AS id_asig, a.id_producto, p.id_categoria
            FROM asignacion a
            INNER JOIN producto p ON p.id = a.id_producto
            WHERE a.destino_tipo = 'pack' AND a.destino_id = ?
        ");
        $stmt->execute(array($idPack));
        $items = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($items as $it) {
            $slot = 'pack_' . $it->id_producto; // fallback unico -> material adicional
            if (isset($mapa[$it->id_categoria])) {
                foreach ($mapa[$it->id_categoria] as $candidato) {
                    if (!isset($ocupados[$candidato]) && !isset($usados[$candidato])) {
                        $slot = $candidato;
                        $usados[$candidato] = true;
                        break;
                    }
                }
            }
            $upd = $cn->prepare("UPDATE asignacion SET destino_tipo = 'tienda', destino_id = ?, slot = ? WHERE id = ?");
            $upd->execute(array($idTienda, $slot, $it->id_asig));
        }
    }

    // Abre la tienda: materializa el pack como material de la tienda y la activa.
    public static function abrirTienda($cn, $idPack)
    {
        $pack = self::obtener($cn, $idPack);
        if (!$pack) {
            return false;
        }
        $idTienda = $pack->id_tienda;

        self::materializarEnTienda($cn, $idPack, $idTienda);

        $cn->prepare("UPDATE tienda SET estado = 'activa' WHERE id = ?")->execute(array($idTienda));
        $cn->prepare("UPDATE pack SET estado = 'entregado', fecha_entrega = NOW() WHERE id = ?")->execute(array($idPack));

        return true;
    }

    // Cancela el pack: libera todos sus productos (vuelven a stock disponible).
    // Si la tienda era un placeholder (en apertura y sin otro pack vigente), se elimina
    // para no dejar tiendas huérfanas.
    public static function cancelar($cn, $idPack)
    {
        $pack = self::obtener($cn, $idPack);

        $cn->prepare("DELETE FROM asignacion WHERE destino_tipo = 'pack' AND destino_id = ?")->execute(array($idPack));
        $cn->prepare("DELETE FROM pack_linea WHERE id_pack = ?")->execute(array($idPack));
        $cn->prepare("UPDATE pack SET estado = 'cancelado' WHERE id = ?")->execute(array($idPack));

        if ($pack) {
            $idTienda = $pack->id_tienda;
            $q = $cn->prepare("SELECT estado FROM tienda WHERE id = ? LIMIT 1");
            $q->execute(array($idTienda));
            $t = $q->fetchObject();
            if ($t && $t->estado == 'en_apertura') {
                // La tienda era un placeholder: se elimina si no tiene OTRO pack vigente.
                $c = $cn->prepare("SELECT COUNT(*) AS n FROM pack WHERE id_tienda = ? AND estado != 'cancelado'");
                $c->execute(array($idTienda));
                $cnt = $c->fetchObject();
                if ($cnt && (int)$cnt->n == 0) {
                    $cn->prepare("DELETE FROM tienda WHERE id = ?")->execute(array($idTienda));
                    // Deshacer completo: quitamos también el registro del pack (no queda huérfano).
                    $cn->prepare("DELETE FROM pack WHERE id = ?")->execute(array($idPack));
                }
            }
        }

        return true;
    }

    // Busca una tienda por numero (para evitar duplicados al crear packs).
    public static function tiendaPorNumero($cn, $numero)
    {
        $stmt = $cn->prepare("SELECT id, numero, estado FROM tienda WHERE numero = ? LIMIT 1");
        $stmt->execute(array($numero));
        return $stmt->fetchObject();
    }

    // ¿La tienda tiene algun pack vigente (no cancelado)?
    public static function tienePackVigente($cn, $idTienda)
    {
        $stmt = $cn->prepare("SELECT COUNT(*) AS n FROM pack WHERE id_tienda = ? AND estado != 'cancelado'");
        $stmt->execute(array($idTienda));
        $fila = $stmt->fetchObject();
        return ($fila && (int)$fila->n > 0);
    }

    // Packs "en curso" (no entregados ni cancelados) para el contador del menu.
    public static function contarEnCurso($cn)
    {
        $stmt = $cn->query("SELECT COUNT(*) AS n FROM pack WHERE estado IN ('preparacion','completo','enviado')");
        $fila = $stmt->fetchObject();
        if ($fila) { return (int)$fila->n; }
        return 0;
    }
}
