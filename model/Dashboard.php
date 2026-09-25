<?php
include_once 'InventarioDB.php';

class Dashboard
{
    // Resumen de stock: total, asignados y disponibles
    public static function getResumenStock()
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN id IN (SELECT id_producto FROM asignacion) THEN 1 ELSE 0 END) AS asignados,
                    SUM(CASE WHEN id NOT IN (SELECT id_producto FROM asignacion) THEN 1 ELSE 0 END) AS disponibles
                FROM producto";

        $resultado = $conexion->query($sql);
        $conexion = null;
        return $resultado->fetchObject();
    }

    // Trabajadores activos por centro
    public static function getResumenTrabajadores()
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT c.tipo, c.nombre, COUNT(t.id) AS total
                FROM centro c
                LEFT JOIN trabajador t ON t.id_centro = c.id AND t.estado = 'ACTIVO'
                GROUP BY c.id, c.tipo, c.nombre
                ORDER BY c.tipo";

        $resultado = $conexion->query($sql);
        $centros = array();
        while ($fila = $resultado->fetchObject()) {
            $centros[] = $fila;
        }

        $conexion = null;
        return $centros;
    }

    // Total de tiendas
    public static function getTotalTiendas()
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT COUNT(*) AS total FROM tienda";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetchObject();

        $conexion = null;
        return $fila->total;
    }

    // Packs de apertura en curso (preparacion / completo / enviado)
    public static function getPacksPendientes()
    {
        $conexion = InventarioDB::connectDB();
        $sql = "SELECT COUNT(*) AS total FROM pack WHERE estado IN ('preparacion','completo','enviado')";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetchObject();
        $conexion = null;
        if ($fila) {
            return (int)$fila->total;
        }
        return 0;
    }

    // Material pendiente de recepción en oficina
    public static function getRecepcionesPendientes()
    {
        $conexion = InventarioDB::connectDB();
        $sql = "SELECT COUNT(*) AS total FROM recepcion WHERE estado = 'pendiente'";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetchObject();
        $conexion = null;
        if ($fila) {
            return (int)$fila->total;
        }
        return 0;
    }

    // Últimas 5 asignaciones realizadas
    public static function getUltimasAsignaciones()
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT
                    a.destino_tipo,
                    a.destino_id,
                    p.codigo,
                    p.modelo,
                    cat.nombre AS subtipo,
                    t.nombre AS nombre_tienda,
                    tr.nombre AS nombre_trabajador
                FROM asignacion a
                JOIN producto p ON p.id = a.id_producto
                JOIN categoria cat ON cat.id = p.id_categoria
                LEFT JOIN tienda t ON a.destino_tipo = 'tienda' AND t.id = a.destino_id
                LEFT JOIN trabajador tr ON a.destino_tipo = 'trabajador' AND tr.id = a.destino_id
                ORDER BY a.id DESC
                LIMIT 5";

        $resultado = $conexion->query($sql);
        $asignaciones = array();
        while ($fila = $resultado->fetchObject()) {
            $asignaciones[] = $fila;
        }

        $conexion = null;
        return $asignaciones;
    }
}