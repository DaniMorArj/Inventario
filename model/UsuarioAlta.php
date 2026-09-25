<?php

class UsuarioAlta
{
    public static function emailExiste($conexion, $email)
    {
        $consultaEmail = "SELECT email FROM usuario WHERE email = '$email'";
        $resultado = $conexion->query($consultaEmail);

        if ($resultado->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function insertar($conexion, $nombre, $email, $pass, $rol, $departamento)
    {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $consultaInsert = "
            INSERT INTO usuario (nombre, email, pass, rol, departamento)
            VALUES ('$nombre', '$email', '$hash', '$rol', '$departamento')
        ";

        return $conexion->exec($consultaInsert);
    }

    public static function contar($conexion, $buscar, $rol)
    {
        $filtrosSQL = " WHERE 1=1 ";

        if ($buscar != '') {
            $filtrosSQL .= " AND (nombre LIKE '%$buscar%' OR email LIKE '%$buscar%' OR departamento LIKE '%$buscar%') ";
        }

        if ($rol != '') {
            $filtrosSQL .= " AND rol = '$rol' ";
        }

        $consultaTotal = "SELECT id FROM usuario $filtrosSQL";
        $resultadoTotal = $conexion->query($consultaTotal);

        return $resultadoTotal->rowCount();
    }

    public static function listar($conexion, $buscar, $rol, $inicio, $porPagina)
    {
        $filtrosSQL = " WHERE 1=1 ";

        if ($buscar != '') {
            $filtrosSQL .= " AND (nombre LIKE '%$buscar%' OR email LIKE '%$buscar%' OR departamento LIKE '%$buscar%') ";
        }

        if ($rol != '') {
            $filtrosSQL .= " AND rol = '$rol' ";
        }

        $consultaListado = "
            SELECT id, nombre, email, rol, departamento
            FROM usuario
            $filtrosSQL
            ORDER BY id DESC
            LIMIT $inicio, $porPagina
        ";

        $resultadoListado = $conexion->query($consultaListado);

        $usuarios = array();
        while ($usuario = $resultadoListado->fetchObject()) {
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    public static function eliminar($conexion, $id)
    {
        $consultaEliminar = "DELETE FROM usuario WHERE id = $id";
        return $conexion->exec($consultaEliminar);
    }

    public static function obtenerPorId($conexion, $id)
    {
        $consulta = "SELECT id, nombre, email, rol, departamento FROM usuario WHERE id = $id";
        $resultado = $conexion->query($consulta);

        return $resultado->fetchObject();
    }

    public static function actualizar($conexion, $id, $nombre, $email, $rol, $departamento)
    {
        $consulta = "
            UPDATE usuario
            SET nombre = '$nombre',
                email = '$email',
                rol = '$rol',
                departamento = '$departamento'
            WHERE id = $id
        ";

        return $conexion->exec($consulta);
    }

    public static function actualizarConPass($conexion, $id, $nombre, $email, $rol, $departamento, $pass)
    {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $consulta = "
            UPDATE usuario
            SET nombre = '$nombre',
                email = '$email',
                rol = '$rol',
                departamento = '$departamento',
                pass = '$hash'
            WHERE id = $id
        ";

        return $conexion->exec($consulta);
    }
}