<?php
include_once 'InventarioDB.php';

class Exportar
{
    // Obtiene todos los trabajadores activos con su centro y departamento
    public static function getTrabajadores()
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT
                    t.nombre,
                    t.email,
                    t.cargo,
                    c.nombre AS centro,
                    d.nombre AS departamento
                FROM trabajador t
                JOIN centro c ON c.id = t.id_centro
                LEFT JOIN departamento d ON d.id = t.id_departamento
                WHERE t.estado = 'ACTIVO'
                ORDER BY c.nombre, t.nombre";

        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }

        $conexion = null;
        return $lista;
    }

    // Obtiene todo el inventario de productos con su estado y destino
    public static function getStock()
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT
                    p.codigo,
                    p.modelo,
                    p.subtipo,
                    cat.nombre AS categoria,
                    a.slot,
                    a.destino_tipo,
                    ti.nombre AS nombre_tienda,
                    tr.nombre AS nombre_trabajador
                FROM producto p
                JOIN categoria cat ON cat.id = p.id_categoria
                LEFT JOIN asignacion a ON a.id_producto = p.id
                LEFT JOIN tienda ti ON a.destino_tipo = 'tienda' AND ti.id = a.destino_id
                LEFT JOIN trabajador tr ON a.destino_tipo = 'trabajador' AND tr.id = a.destino_id
                ORDER BY cat.nombre, p.codigo";

        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }

        $conexion = null;
        return $lista;
    }

    // Obtiene todo el material asignado a una tienda concreta
    public static function getMaterialTienda($idTienda)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT
                    t.nombre AS tienda,
                    t.numero,
                    p.codigo,
                    p.modelo,
                    p.subtipo,
                    cat.nombre AS categoria,
                    a.slot
                FROM asignacion a
                JOIN producto p ON p.id = a.id_producto
                JOIN categoria cat ON cat.id = p.id_categoria
                JOIN tienda t ON t.id = a.destino_id
                WHERE a.destino_tipo = 'tienda'
                AND a.destino_id = $idTienda
                ORDER BY cat.nombre, p.codigo";

        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }

        $conexion = null;
        return $lista;
    }

    // Obtiene el nombre de una tienda por su id
    public static function getNombreTienda($idTienda)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT nombre, numero FROM tienda WHERE id = $idTienda";
        $resultado = $conexion->query($sql);
        $fila = $resultado->fetchObject();

        $conexion = null;
        return $fila;
    }

    // Obtiene los datos completos de una tienda
    public static function getDatosTienda($idTienda)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT t.numero, t.nombre, t.fijo, t.email, t.observaciones,
                        s.nombre AS sociedad, s.cif,
                        tr.nombre AS cluster_manager,
                        COALESCE(pa.valor, t.movil) AS movil
                FROM tienda t
                JOIN sociedad s ON s.id = t.id_sociedad
                LEFT JOIN trabajador tr ON tr.id = t.id_cluster_manager
                LEFT JOIN producto p ON p.codigo = t.movil
                LEFT JOIN producto_atributo pa ON pa.id_producto = p.id AND pa.id_atributo = 14
                WHERE t.id = $idTienda";

        $resultado = $conexion->query($sql);
        $fila = $resultado->fetchObject();

        $conexion = null;
        return $fila;
    }
}