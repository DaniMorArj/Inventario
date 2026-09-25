<?php
include_once 'InventarioDB.php';

class Usuario
{
    private $id;
    private $email;
    private $pass;
    private $rol;
    private $departamento;

    public function __construct($id, $email, $pass, $rol, $departamento)
    {
        $this->id = $id;
        $this->email = $email;
        $this->pass = $pass;
        $this->rol = $rol;
        $this->departamento = $departamento;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    // Login: busca por email y verifica la contraseña con bcrypt
    public static function getUsuarioByEmailPass($email, $pass)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT id, email, pass, rol, departamento
                FROM usuario
                WHERE email = '$email'";

        $consulta = $conexion->query($sql);

        if ($consulta->rowCount() > 0) {
            $registro = $consulta->fetchObject();

            if (password_verify($pass, $registro->pass)) {
                $usuario = new Usuario(
                    $registro->id,
                    $registro->email,
                    $registro->pass,
                    $registro->rol,
                    $registro->departamento
                );
                $conexion = null;
                return $usuario;
            }
        }

        $conexion = null;
        return false;
    }

    // Comprueba si un email existe en la tabla usuario
    public static function emailExiste($email)
    {
        $conexion = InventarioDB::connectDB();

        $sql = "SELECT id FROM usuario WHERE email = '$email'";
        $consulta = $conexion->query($sql);

        $conexion = null;

        if ($consulta->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Guarda un token de recuperación (invalida los anteriores del mismo email)
    public static function guardarToken($email, $token)
    {
        $conexion = InventarioDB::connectDB();

        // Expira en 1 hora
        $expira = date('Y-m-d H:i:s', time() + 3600);

        // Invalidamos tokens anteriores del mismo email
        $conexion->exec("UPDATE password_reset_tokens SET usado = 1 WHERE email = '$email'");

        // Insertamos el nuevo
        $sql = "INSERT INTO password_reset_tokens (email, token, expira)
                VALUES ('$email', '$token', '$expira')";

        $conexion->exec($sql);
        $conexion = null;
    }

    // Valida el token: que exista, no esté usado y no haya expirado
    // Devuelve el email si es válido, false si no
    public static function validarToken($token)
    {
        $conexion = InventarioDB::connectDB();

        $ahora = date('Y-m-d H:i:s');

        $sql = "SELECT email FROM password_reset_tokens
                WHERE token = '$token'
                AND usado = 0
                AND expira > '$ahora'";

        $consulta = $conexion->query($sql);
        $conexion = null;

        if ($consulta->rowCount() > 0) {
            $registro = $consulta->fetchObject();
            return $registro->email;
        } else {
            return false;
        }
    }

    // Cambia la contraseña y marca el token como usado
    public static function cambiarPassword($email, $nuevaPass, $token)
    {
        $conexion = InventarioDB::connectDB();

        $hash = password_hash($nuevaPass, PASSWORD_DEFAULT);

        $conexion->exec("UPDATE usuario SET pass = '$hash' WHERE email = '$email'");
        $conexion->exec("UPDATE password_reset_tokens SET usado = 1 WHERE token = '$token'");

        $conexion = null;
    }
}