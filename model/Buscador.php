<?php
include_once 'InventarioDB.php';

class Buscador
{
    // Busca trabajadores por nombre
    public static function buscarTrabajadores($texto)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT t.id, t.nombre, t.cargo, c.nombre AS centro_nombre, c.tipo AS centro_tipo
                FROM trabajador t
                JOIN centro c ON c.id = t.id_centro
                WHERE t.nombre LIKE '%$texto%'
                AND t.estado = 'ACTIVO'
                ORDER BY t.nombre
                LIMIT 10";

        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }

        $conexion = null;
        return $lista;
    }

    // Busca tiendas por nombre o número
    public static function buscarTiendas($texto)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT t.id, t.nombre, t.numero, s.nombre AS sociedad
                FROM tienda t
                JOIN sociedad s ON s.id = t.id_sociedad
                WHERE t.nombre LIKE '%$texto%'
                OR t.numero LIKE '%$texto%'
                ORDER BY t.numero
                LIMIT 10";

        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }

        $conexion = null;
        return $lista;
    }

    // Busca productos por código o modelo, con info de asignación
    public static function buscarProductos($texto)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT
                    p.id,
                    p.codigo,
                    p.modelo,
                    p.subtipo,
                    cat.nombre AS categoria,
                    a.destino_tipo,
                    a.destino_id,
                    a.slot,
                    ti.nombre AS nombre_tienda,
                    tr.nombre AS nombre_trabajador
                FROM producto p
                JOIN categoria cat ON cat.id = p.id_categoria
                LEFT JOIN asignacion a ON a.id_producto = p.id
                LEFT JOIN tienda ti ON a.destino_tipo = 'tienda' AND ti.id = a.destino_id
                LEFT JOIN trabajador tr ON a.destino_tipo = 'trabajador' AND tr.id = a.destino_id
                LEFT JOIN producto_atributo pa_num ON pa_num.id_producto = p.id AND pa_num.id_atributo = 14
                WHERE p.codigo LIKE '%$texto%'
                OR p.modelo LIKE '%$texto%'
                OR pa_num.valor LIKE '%$texto%'
                ORDER BY p.codigo
                LIMIT 10";

        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }

        $conexion = null;
        return $lista;
    }
}