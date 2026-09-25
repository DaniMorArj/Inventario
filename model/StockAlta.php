<?php

class StockAlta
{
    /* ============================
       CATEGORÍAS
    ============================ */

    public static function listarCategorias($cn)
    {
        $consulta = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
        $resultado = $cn->query($consulta);

        $categorias = array();
        while ($categoria = $resultado->fetchObject()) {
            $categorias[] = $categoria;
        }

        return $categorias;
    }

    /**
     * Devuelve un array de categorías REALES para un submenú (slug).
     * Ej: impresora -> [Impresora Tickets, Impresoras Multifuncion]
     */
    public static function obtenerCategoriasAltaPorSlug($cn, $slug)
    {
        $slug = trim($slug);
        $slug = strtolower($slug);

        $map = array(
            'ordenador'  => array('Ordenador'),
            'monitor'    => array('Monitor'),
            'impresora'  => array('Impresora Tickets', 'Impresoras Multifuncion'),
            'periferico' => array('Teclado', 'Raton', 'HUB USB', 'Cajon Portamonedas'),
            'lector'     => array('Lector Codigo', 'Lector Billete'),
            'telefono_fijo'   => array('Telefono Fijo'),
            'telefono_movil'  => array('Telefono Movil'),
            'movil'      => array('Numero Movil'),
            'router'     => array('Router'),
            'camara'     => array('Camaras 360', 'Camaras Fija'),
            'licencia'   => array('Licencia'),
            'tpv'        => array('Datafono', 'Pinpad')
        );

        if (!isset($map[$slug])) {
            return array();
        }

        $nombres = $map[$slug];
        $condicion = "";

        foreach ($nombres as $i => $nombre) {
            if ($i == 0) {
                $condicion .= " nombre = '$nombre' ";
            } else {
                $condicion .= " OR nombre = '$nombre' ";
            }
        }

        $consulta = "SELECT id, nombre FROM categoria WHERE $condicion ORDER BY nombre ASC";
        $resultado = $cn->query($consulta);

        $categorias = array();
        while ($categoria = $resultado->fetchObject()) {
            $categorias[] = $categoria;
        }

        return $categorias;
    }

    /* ============================
       PRODUCTOS
    ============================ */

    // Condiciones (ciclo de vida) del material: clave => etiqueta.
    public static function condiciones()
    {
        return array(
            'nuevo' => 'Nuevo',
            'usado' => 'Usado',
            'reacondicionado' => 'Reacondicionado',
            'averiado' => 'Averiado',
            'en_garantia' => 'En garantía',
            'en_reparacion' => 'En reparación',
            'para_piezas' => 'Para piezas',
            'desechado' => 'Desechado'
        );
    }

    // Solo el material operativo (nuevo/usado/reacondicionado) se puede asignar.
    public static function condicionAsignable($estado)
    {
        return $estado == 'nuevo' || $estado == 'usado' || $estado == 'reacondicionado';
    }

    // Prefijo y ancho de digitos del codigo autogenerado por categoria.
    // (Esquema provisional para pruebas; se ajustara mas adelante.)
    public static function prefijosCategoria()
    {
        return array(
            'Ordenador' => array('OR', 10),
            'Monitor' => array('MONI', 6),
            'Impresora Tickets' => array('IMPT', 6),
            'Impresoras Multifuncion' => array('IMPM', 6),
            'Lector Codigo' => array('LECC', 6),
            'Lector Billete' => array('LECB', 6),
            'Router' => array('ROUT', 6),
            'Camaras 360' => array('CAM3', 6),
            'Camaras Fija' => array('CAMF', 6),
            'Datafono' => array('DATA', 6),
            'Pinpad' => array('PINP', 6),
            'Periferico' => array('PERI', 6),
            'Telefono Fijo' => array('TELF', 6),
            'Telefono Movil' => array('TELM', 6),
            'Numero Movil' => array('NUMM', 6),
            'Licencia' => array('LICE', 6),
            'Teclado' => array('TECL', 6),
            'Raton' => array('RATO', 6),
            'HUB USB' => array('HUBU', 6),
            'Cajon Portamonedas' => array('CAJO', 6),
            'TPV' => array('TPV', 6)
        );
    }

    // Genera el siguiente codigo autogenerado segun la categoria (prefijo propio + correlativo
    // por categoria). Si la categoria no tiene prefijo definido, cae al generico CD.
    public static function generarCodigoPorCategoria($cn, $idCategoria)
    {
        $stmt = $cn->prepare("SELECT nombre FROM categoria WHERE id = ? LIMIT 1");
        $stmt->execute(array($idCategoria));
        $cat = $stmt->fetchObject();
        $nombre = '';
        if ($cat) {
            $nombre = $cat->nombre;
        }

        $mapa = self::prefijosCategoria();
        if (!isset($mapa[$nombre])) {
            return self::generarCodigoCompraDirecta($cn);
        }

        $prefijo = $mapa[$nombre][0];
        $ancho = $mapa[$nombre][1];
        $inicio = strlen($prefijo) + 1;

        $stmt = $cn->query("SELECT MAX(CAST(SUBSTRING(codigo, $inicio) AS UNSIGNED)) AS maxnum FROM producto WHERE codigo LIKE '$prefijo%'");
        $fila = $stmt->fetchObject();
        $siguiente = 1;
        if ($fila && $fila->maxnum !== null) {
            $siguiente = (int)$fila->maxnum + 1;
        }

        return $prefijo . str_pad($siguiente, $ancho, '0', STR_PAD_LEFT);
    }

    // Genera un codigo generico (respaldo) para material sin categoria mapeada:
    // CD + 8 digitos (CD00000001).
    public static function generarCodigoCompraDirecta($cn)
    {
        $stmt = $cn->query("SELECT MAX(CAST(SUBSTRING(codigo,3) AS UNSIGNED)) AS maxnum FROM producto WHERE codigo LIKE 'CD%'");
        $fila = $stmt->fetchObject();
        $siguiente = 1;
        if ($fila && $fila->maxnum !== null) {
            $siguiente = (int)$fila->maxnum + 1;
        }
        return 'CD' . str_pad($siguiente, 8, '0', STR_PAD_LEFT);
    }

    public static function codigoExiste($cn, $codigo)
    {
        $consulta = "SELECT id FROM producto WHERE codigo = '$codigo' LIMIT 1";
        $resultado = $cn->query($consulta);

        if ($resultado->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function insertarProducto($cn, $codigo, $modelo, $idCategoria, $estado, $subtipo)
    {
        $consulta = "
            INSERT INTO producto (codigo, modelo, id_categoria, estado, subtipo)
            VALUES ('$codigo', '$modelo', $idCategoria, '$estado', '$subtipo')
        ";

        $cn->exec($consulta);
        return $cn->lastInsertId();
    }

    public static function contarProductos($cn, $buscar, $filtroTipo, $filtroCondicion = '')
    {
        $where = " WHERE 1=1 ";

        if ($buscar != '') {
            $where .= " AND (p.codigo LIKE '%$buscar%' OR p.modelo LIKE '%$buscar%' OR p.subtipo LIKE '%$buscar%' OR c.nombre LIKE '%$buscar%') ";
        }

        if ($filtroTipo != '') {
            $where .= " AND (c.nombre = '$filtroTipo' OR p.subtipo = '$filtroTipo') ";
        }

        if ($filtroCondicion != '') {
            $where .= " AND p.estado = '$filtroCondicion' ";
        }

        $consulta = "
            SELECT p.id
            FROM producto p
            INNER JOIN categoria c ON c.id = p.id_categoria
            $where
        ";

        $resultado = $cn->query($consulta);
        return $resultado->rowCount();
    }

    public static function listarProductos($cn, $buscar, $filtroTipo, $orden, $inicio, $porPagina, $filtroCondicion = '')
    {
        $where = " WHERE 1=1 ";

        if ($buscar != '') {
            $where .= " AND (p.codigo LIKE '%$buscar%' OR p.modelo LIKE '%$buscar%' OR p.subtipo LIKE '%$buscar%' OR c.nombre LIKE '%$buscar%') ";
        }

        if ($filtroTipo != '') {
            $where .= " AND (c.nombre = '$filtroTipo' OR p.subtipo = '$filtroTipo') ";
        }

        if ($filtroCondicion != '') {
            $where .= " AND p.estado = '$filtroCondicion' ";
        }

        $orderBy = " ORDER BY p.id DESC ";

        if ($orden == 'codigo_asc') {
            $orderBy = " ORDER BY p.codigo ASC ";
        }

        if ($orden == 'codigo_desc') {
            $orderBy = " ORDER BY p.codigo DESC ";
        }

        if ($orden == 'estado_asc') {
            $orderBy = " ORDER BY p.estado ASC ";
        }

        if ($orden == 'estado_desc') {
            $orderBy = " ORDER BY p.estado DESC ";
        }

        $consulta = "
            SELECT
                p.*,
                c.nombre AS categoria_nombre,
                a.destino_tipo,
                a.destino_id,
                a.slot,
                e.estado AS envio_estado
            FROM producto p
            INNER JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN asignacion a ON a.id_producto = p.id
            LEFT JOIN envio e ON e.id_producto = p.id AND e.estado IN ('preparando','enviado')
            $where
            $orderBy
            LIMIT $inicio, $porPagina
        ";

        $resultado = $cn->query($consulta);

        $productos = array();
        while ($producto = $resultado->fetchObject()) {
            $productos[] = $producto;
        }

        return $productos;
    }

    public static function contarTotalPorCategoria($cn, $idCategoria)
    {
        $consulta = "SELECT id FROM producto WHERE id_categoria = $idCategoria";
        $resultado = $cn->query($consulta);

        return $resultado->rowCount();
    }

    public static function contarDisponiblesPorCategoria($cn, $idCategoria)
    {
        $consulta = "
            SELECT p.id
            FROM producto p
            LEFT JOIN asignacion a ON a.id_producto = p.id
            WHERE p.id_categoria = $idCategoria
              AND a.id_producto IS NULL
        ";

        $resultado = $cn->query($consulta);

        return $resultado->rowCount();
    }

    /* ============================
       ASIGNACIONES
    ============================ */

    public static function obtenerProductoConAsignacion($cn, $idProducto)
    {
        $consulta = "
            SELECT p.*,
                   c.nombre AS categoria_nombre,
                   a.destino_tipo,
                   a.destino_id,
                   a.slot,
                   e.estado AS envio_estado
            FROM producto p
            INNER JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN asignacion a ON a.id_producto = p.id
            LEFT JOIN envio e ON e.id_producto = p.id AND e.estado IN ('preparando','enviado')
            WHERE p.id = $idProducto
            LIMIT 1
        ";

        $resultado = $cn->query($consulta);
        return $resultado->fetchObject();
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

    public static function slotOcupado($cn, $tipo, $destinoId, $slot)
    {
        $consulta = "
            SELECT id
            FROM asignacion
            WHERE destino_tipo = '$tipo'
              AND destino_id = $destinoId
              AND slot = '$slot'
            LIMIT 1
        ";

        $resultado = $cn->query($consulta);

        if ($resultado->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function asignarProducto($cn, $idProducto, $tipo, $destinoId, $slot)
    {
        $consulta = "
            INSERT INTO asignacion (id_producto, destino_tipo, destino_id, slot)
            VALUES ($idProducto, '$tipo', $destinoId, '$slot')
        ";

        return $cn->exec($consulta);
    }

    public static function listarTiendas($cn)
    {
        // Solo tiendas activas: no se asigna material a tiendas cerradas ni en apertura
        // (las que están en apertura se equipan mediante los packs de apertura).
        $consulta = "SELECT id, numero, nombre FROM tienda WHERE estado = 'activa' ORDER BY numero ASC";
        $resultado = $cn->query($consulta);

        $tiendas = array();
        while ($tienda = $resultado->fetchObject()) {
            $tiendas[] = $tienda;
        }

        return $tiendas;
    }

    // Devuelve true si la tienda existe y está activa.
    public static function tiendaActiva($cn, $idTienda)
    {
        $stmt = $cn->prepare("SELECT estado FROM tienda WHERE id = ? LIMIT 1");
        $stmt->execute(array($idTienda));
        $fila = $stmt->fetchObject();
        if ($fila && $fila->estado == 'activa') {
            return true;
        }
        return false;
    }

    /* ============================
       RESUMEN
    ============================ */

    public static function contarResumenCategorias($cn, $cats, $subtipoLike)
    {
        $condicion = "";

        foreach ($cats as $i => $cat) {
            if ($i == 0) {
                $condicion .= " c.nombre = '$cat' ";
            } else {
                $condicion .= " OR c.nombre = '$cat' ";
            }
        }

        $consulta = "
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN a.id_producto IS NULL THEN 1 ELSE 0 END) AS disponibles
            FROM producto p
            INNER JOIN categoria c ON c.id = p.id_categoria
            LEFT JOIN asignacion a ON a.id_producto = p.id
            WHERE ($condicion)
        ";

        if ($subtipoLike != null && $subtipoLike != '') {
            $consulta .= " AND p.subtipo LIKE '$subtipoLike' ";
        }

        $resultado = $cn->query($consulta);
        $fila = $resultado->fetchObject();

        $resumen = array();

        if ($fila) {
            $resumen['total'] = $fila->total;
            $resumen['disponibles'] = $fila->disponibles;
        } else {
            $resumen['total'] = 0;
            $resumen['disponibles'] = 0;
        }

        return $resumen;
    }

    public static function resumenTop($cn)
    {
        $resumen = array();

        $resumen['Portatiles'] = self::contarResumenCategorias($cn, array('Ordenador'), '%portatil%');
        $resumen['Monitor'] = self::contarResumenCategorias($cn, array('Monitor'), null);
        $resumen['Impresora'] = self::contarResumenCategorias($cn, array('Impresora Tickets', 'Impresoras Multifuncion'), null);
        $resumen['Perifericos'] = self::contarResumenCategorias($cn, array('Teclado', 'Raton', 'HUB USB'), null);
        $resumen['Cajon Portamonedas'] = self::contarResumenCategorias($cn, array('Cajon Portamonedas'), null);
        $resumen['Lectores'] = self::contarResumenCategorias($cn, array('Lector Codigo', 'Lector Billete'), null);
        $resumen['Telefono Fijo'] = self::contarResumenCategorias($cn, array('Telefono Fijo'), null);
        $resumen['Telefono Movil'] = self::contarResumenCategorias($cn, array('Telefono Movil'), null);
        $resumen['Movil'] = self::contarResumenCategorias($cn, array('Numero Movil'), null);
        $resumen['Router'] = self::contarResumenCategorias($cn, array('Router'), null);
        $resumen['Camaras'] = self::contarResumenCategorias($cn, array('Camaras 360', 'Camaras Fija'), null);
        $resumen['Licencias'] = self::contarResumenCategorias($cn, array('Licencia'), null);
        $resumen['TPV'] = self::contarResumenCategorias($cn, array('Datafono', 'Pinpad'), null);

        return $resumen;
    }

    public static function slotsPermitidosPorCategoria($categoriaNombre)
    {
        $cat = trim($categoriaNombre);
        $cat = strtolower($cat);

        $cat = strtr($cat, array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'ü' => 'u'
        ));

        // Slots con nombre que usa el detalle de tienda, con etiqueta legible.
        // Así el material asignado desde Stock cae en su sección correspondiente.
        if ($cat == 'ordenador') {
            return array('equipo1' => 'Equipo 1', 'equipo2' => 'Equipo 2');
        }

        if ($cat == 'impresora tickets') {
            return array('tickets1' => 'Impresora Tickets 1', 'tickets2' => 'Impresora Tickets 2');
        }

        if ($cat == 'impresoras multifuncion') {
            return array('multifuncion' => 'Impresora Multifunción');
        }

        if ($cat == 'lector codigo') {
            return array('lector_codigo1' => 'Lector Código 1', 'lector_codigo2' => 'Lector Código 2');
        }

        if ($cat == 'lector billete') {
            return array('lector_billete1' => 'Lector Billete 1', 'lector_billete2' => 'Lector Billete 2');
        }

        if ($cat == 'cajon portamonedas') {
            return array('cajon_portamonedas' => 'Cajón Portamonedas');
        }

        if ($cat == 'telefono fijo') {
            return array('telefono_fijo' => 'Teléfono Fijo');
        }

        if ($cat == 'telefono movil') {
            return array('telefono_movil' => 'Teléfono Móvil');
        }

        if ($cat == 'router') {
            return array('router_sos' => 'Router');
        }

        if ($cat == 'datafono') {
            return array('datafono' => 'Datáfono');
        }

        if ($cat == 'pinpad') {
            return array('pinpad' => 'PINPAD');
        }

        if ($cat == 'camaras 360') {
            return array('camara360_1' => 'Cámara 360 1');
        }

        if ($cat == 'camaras fija') {
            return array('camara_fija_1' => 'Cámara Fija 1');
        }

        // Categorías sin hueco propio en el detalle -> aparecen en "Material adicional".
        if ($cat == 'monitor') {
            return array('monitor' => 'Monitor');
        }

        if ($cat == 'teclado') {
            return array('teclado' => 'Teclado');
        }

        if ($cat == 'raton') {
            return array('raton' => 'Ratón');
        }

        if ($cat == 'hub usb') {
            return array('hub' => 'HUB USB');
        }

        if ($cat == 'numero movil') {
            return array('movil' => 'Número Móvil');
        }

        if ($cat == 'licencia') {
            return array('licencia' => 'Licencia');
        }

        if ($cat == 'periferico') {
            return array('periferico' => 'Periférico');
        }

        return array();
    }

    public static function slotsPermitidosParaProducto($cn, $idProducto)
    {
        $p = self::obtenerProductoConAsignacion($cn, $idProducto);

        if (!$p) {
            return array();
        }

        return self::slotsPermitidosPorCategoria($p->categoria_nombre);
    }

    /* ============================
    ATRIBUTOS
    ============================ */

    public static function listarAtributosPorCategoria($cn, $idCategoria)
    {
        $consulta = "
            SELECT a.*
            FROM categoria_atributo ca
            INNER JOIN atributo a ON a.id = ca.id_atributo
            WHERE ca.id_categoria = $idCategoria
            ORDER BY a.nombre ASC
        ";

        $resultado = $cn->query($consulta);

        $atributos = array();
        while ($atributo = $resultado->fetchObject()) {
            $atributos[] = $atributo;
        }

        return $atributos;
    }

    public static function guardarAtributosProducto($cn, $idProducto, $atributos, &$errores)
    {
        foreach ($atributos as $idA => $valor) {
            $idA = (int)$idA;

            if ($idA > 0) {
                if ($valor == '') {
                    $valorSQL = "NULL";
                } else {
                    $valorSQL = "'$valor'";
                }

                $consultaExiste = "
                    SELECT id_producto
                    FROM producto_atributo
                    WHERE id_producto = $idProducto
                      AND id_atributo = $idA
                    LIMIT 1
                ";

                $resultadoExiste = $cn->query($consultaExiste);

                if ($resultadoExiste->rowCount() > 0) {
                    $consulta = "
                        UPDATE producto_atributo
                        SET valor = $valorSQL
                        WHERE id_producto = $idProducto
                          AND id_atributo = $idA
                        LIMIT 1
                    ";
                } else {
                    $consulta = "
                        INSERT INTO producto_atributo (id_producto, id_atributo, valor)
                        VALUES ($idProducto, $idA, $valorSQL)
                    ";
                }

                $ok = $cn->exec($consulta);

                if ($ok === false) {
                    $errores[] = "Error al guardar atributo";
                }
            }
        }
    }

    public static function obtenerAtributosProducto($cn, $idProducto)
    {
        $consulta = "
            SELECT
                a.id,
                a.clave,
                a.nombre,
                a.tipo,
                a.unidad,
                pa.valor
            FROM producto_atributo pa
            INNER JOIN atributo a ON a.id = pa.id_atributo
            WHERE pa.id_producto = $idProducto
            ORDER BY a.nombre ASC
        ";

        $resultado = $cn->query($consulta);

        $atributos = array();
        while ($atributo = $resultado->fetchObject()) {
            $atributos[] = $atributo;
        }

        return $atributos;
    }

    public static function actualizarProductoBase($cn, $idProducto, $codigo, $modelo, $subtipo, $estado)
    {
        $consulta = "
            UPDATE producto
            SET codigo = '$codigo',
                modelo = '$modelo',
                subtipo = '$subtipo',
                estado = '$estado'
            WHERE id = $idProducto
            LIMIT 1
        ";

        return $cn->exec($consulta);
    }

    public static function upsertAtributoProducto($cn, $idProducto, $idAtributo, $valor)
    {
        if ($valor == '') {
            $valorSQL = "NULL";
        } else {
            $valorSQL = "'$valor'";
        }

        $consultaExiste = "
            SELECT id_producto
            FROM producto_atributo
            WHERE id_producto = $idProducto
              AND id_atributo = $idAtributo
            LIMIT 1
        ";

        $resultadoExiste = $cn->query($consultaExiste);

        if ($resultadoExiste->rowCount() > 0) {
            $consulta = "
                UPDATE producto_atributo
                SET valor = $valorSQL
                WHERE id_producto = $idProducto
                  AND id_atributo = $idAtributo
                LIMIT 1
            ";
        } else {
            $consulta = "
                INSERT INTO producto_atributo (id_producto, id_atributo, valor)
                VALUES ($idProducto, $idAtributo, $valorSQL)
            ";
        }

        return $cn->exec($consulta);
    }

    public static function liberarProducto($cn, $idProducto)
    {
        $consulta = "DELETE FROM asignacion WHERE id_producto = $idProducto LIMIT 1";
        return $cn->exec($consulta);
    }
}