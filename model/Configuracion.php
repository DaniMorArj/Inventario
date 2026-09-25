<?php
include_once 'InventarioDB.php';

class Configuracion
{
    // ============================================================
    // SOCIEDADES
    // ============================================================

    public static function listarSociedades()
    {
        $conexion = InventarioDB::connectDB();
        $sql = "SELECT id, nombre, cif FROM sociedad ORDER BY nombre";
        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }
        $conexion = null;
        return $lista;
    }

    public static function obtenerSociedad($id)
    {
        $conexion = InventarioDB::connectDB();
        $sql = "SELECT id, nombre, cif FROM sociedad WHERE id = $id";
        $resultado = $conexion->query($sql);
        $conexion = null;
        return $resultado->fetchObject();
    }

    public static function insertarSociedad($nombre, $cif)
    {
        $conexion = InventarioDB::connectDB();
        $sql = "INSERT INTO sociedad (nombre, cif) VALUES ('$nombre', '$cif')";
        $conexion->exec($sql);
        $conexion = null;
    }

    public static function actualizarSociedad($id, $nombre, $cif)
    {
        $conexion = InventarioDB::connectDB();
        $sql = "UPDATE sociedad SET nombre = '$nombre', cif = '$cif' WHERE id = $id";
        $conexion->exec($sql);
        $conexion = null;
    }

    public static function eliminarSociedad($id)
    {
        $conexion = InventarioDB::connectDB();
        // Comprobamos si tiene tiendas asociadas
        $check = $conexion->query("SELECT COUNT(*) AS total FROM tienda WHERE id_sociedad = $id");
        $fila = $check->fetchObject();
        if ($fila->total > 0) {
            $conexion = null;
            return false;
        }
        $conexion->exec("DELETE FROM sociedad WHERE id = $id");
        $conexion = null;
        return true;
    }

    // ============================================================
    // DEPARTAMENTOS
    // ============================================================

    public static function listarDepartamentos()
    {
        $conexion = InventarioDB::connectDB();
        $sql = "SELECT id, nombre FROM departamento ORDER BY nombre";
        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }
        $conexion = null;
        return $lista;
    }

    public static function obtenerDepartamento($id)
    {
        $conexion = InventarioDB::connectDB();
        $sql = "SELECT id, nombre FROM departamento WHERE id = $id";
        $resultado = $conexion->query($sql);
        $conexion = null;
        return $resultado->fetchObject();
    }

    public static function insertarDepartamento($nombre)
    {
        $conexion = InventarioDB::connectDB();
        $sql = "INSERT INTO departamento (nombre) VALUES ('$nombre')";
        $conexion->exec($sql);
        $conexion = null;
    }

    public static function actualizarDepartamento($id, $nombre)
    {
        $conexion = InventarioDB::connectDB();
        $sql = "UPDATE departamento SET nombre = '$nombre' WHERE id = $id";
        $conexion->exec($sql);
        $conexion = null;
    }

    public static function eliminarDepartamento($id)
    {
        $conexion = InventarioDB::connectDB();
        // Comprobamos si tiene trabajadores asociados
        $check = $conexion->query("SELECT COUNT(*) AS total FROM trabajador WHERE id_departamento = $id");
        $fila = $check->fetchObject();
        if ($fila->total > 0) {
            $conexion = null;
            return false;
        }
        $conexion->exec("DELETE FROM departamento WHERE id = $id");
        $conexion = null;
        return true;
    }

    // ============================================================
    // PLANTILLA DE PACKS DE APERTURA
    // ============================================================

    public static function listarCategorias()
    {
        $conexion = InventarioDB::connectDB();
        $resultado = $conexion->query("SELECT id, nombre FROM categoria ORDER BY nombre");
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }
        $conexion = null;
        return $lista;
    }

    public static function listarPlantillaPack()
    {
        $conexion = InventarioDB::connectDB();
        $sql = "
            SELECT pp.id, pp.id_categoria, pp.subtipo, pp.cantidad, pp.orden,
                   c.nombre AS categoria
            FROM pack_plantilla pp
            INNER JOIN categoria c ON c.id = pp.id_categoria
            ORDER BY pp.orden
        ";
        $resultado = $conexion->query($sql);
        $lista = array();
        while ($fila = $resultado->fetchObject()) {
            $lista[] = $fila;
        }
        $conexion = null;
        return $lista;
    }

    public static function obtenerPlantillaLinea($id)
    {
        $conexion = InventarioDB::connectDB();
        $stmt = $conexion->prepare("SELECT id, id_categoria, subtipo, cantidad FROM pack_plantilla WHERE id = ? LIMIT 1");
        $stmt->execute(array($id));
        $fila = $stmt->fetchObject();
        $conexion = null;
        return $fila;
    }

    public static function insertarPlantillaLinea($idCategoria, $subtipo, $cantidad)
    {
        $conexion = InventarioDB::connectDB();
        if ($subtipo == '') {
            $subtipo = null;
        }
        // Siguiente orden
        $r = $conexion->query("SELECT MAX(orden) AS m FROM pack_plantilla");
        $f = $r->fetchObject();
        $orden = 1;
        if ($f && $f->m !== null) {
            $orden = (int)$f->m + 1;
        }
        $stmt = $conexion->prepare("INSERT INTO pack_plantilla (id_categoria, subtipo, cantidad, orden) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($idCategoria, $subtipo, $cantidad, $orden));
        $conexion = null;
    }

    public static function actualizarPlantillaLinea($id, $idCategoria, $subtipo, $cantidad)
    {
        $conexion = InventarioDB::connectDB();
        if ($subtipo == '') {
            $subtipo = null;
        }
        $stmt = $conexion->prepare("UPDATE pack_plantilla SET id_categoria = ?, subtipo = ?, cantidad = ? WHERE id = ?");
        $stmt->execute(array($idCategoria, $subtipo, $cantidad, $id));
        $conexion = null;
    }

    public static function eliminarPlantillaLinea($id)
    {
        $conexion = InventarioDB::connectDB();
        $stmt = $conexion->prepare("DELETE FROM pack_plantilla WHERE id = ?");
        $stmt->execute(array($id));
        $conexion = null;
        return true;
    }
}