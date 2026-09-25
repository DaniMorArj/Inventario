<?php
class InventarioDB
{
    public static function connectDB()
    {
        // Configuración por variables de entorno (hosting / Docker).
        // Si no existen (desarrollo sin contenedor), usa los valores locales de XAMPP.
        $host = getenv('DB_HOST');
        if ($host === false || $host == '') {
            $host = 'localhost';
        }
        $nombre = getenv('DB_NAME');
        if ($nombre === false || $nombre == '') {
            $nombre = 'inventario';
        }
        $usuario = getenv('DB_USER');
        if ($usuario === false || $usuario == '') {
            $usuario = 'root';
        }
        $pass = getenv('DB_PASS');
        if ($pass === false) {
            $pass = '';
        }
        $puerto = getenv('DB_PORT');
        if ($puerto === false || $puerto == '') {
            $puerto = '3306';
        }

        // Conexión cifrada (Aiven y la mayoría de hostings gestionados la exigen).
        // DB_SSL=true la activa; sin ella, conexión normal (como en local con XAMPP).
        $sslRequerido = getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1';
        $opciones = [];
        if ($sslRequerido) {
            $opciones[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        try {
            $dsn = "mysql:host=" . $host . ";port=" . $puerto . ";dbname=" . $nombre . ";charset=utf8";
            $conexion = new PDO($dsn, $usuario, $pass, $opciones);
        } catch (PDOException $e) {
            echo "No se ha podido establecer conexión con el servidor de bases de datos.<br>";
            die("Error: " . $e->getMessage());
        }
        return $conexion;
    }
}
