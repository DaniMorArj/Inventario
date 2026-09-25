<?php
/**
 * Borra y vuelve a crear la base de datos indicada por DB_NAME, para poder
 * reimportar inventario.sql desde cero sin duplicar tiendas/trabajadores/stock.
 *
 * Uso: igual que import_sql.php, pero sin dbname en la conexion inicial.
 *   $env:DB_HOST="..."; $env:DB_PORT="..."; $env:DB_NAME="..."
 *   $env:DB_USER="..."; $env:DB_PASS="..."; $env:DB_SSL="true"
 *   php scripts/reset_db.php
 */

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'inventario';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$ssl  = getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1';

$opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
if ($ssl) {
    $opciones[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
    // Sin dbname: hace falta para poder borrarla.
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $opciones);
} catch (PDOException $e) {
    fwrite(STDERR, "No me puedo conectar: " . $e->getMessage() . "\n");
    exit(1);
}

echo "Borrando y recreando '$name' en $host ...\n";
$pdo->exec("DROP DATABASE IF EXISTS `$name`");
$pdo->exec("CREATE DATABASE `$name`");
echo "Listo. Ahora ejecuta: php scripts/import_sql.php\n";