<?php
/**
 * Importa inventario.sql en la base de datos indicada por las variables de
 * entorno DB_*, usando PDO (igual que la propia app) en vez del cliente de
 * linea de comandos de mysql — util cuando ese cliente es demasiado antiguo
 * para el metodo de autenticacion del servidor (caching_sha2_password, tipico
 * en MySQL 8 / Aiven).
 *
 * Uso:
 *   $env:DB_HOST="..."; $env:DB_PORT="..."; $env:DB_NAME="..."
 *   $env:DB_USER="..."; $env:DB_PASS="..."; $env:DB_SSL="true"
 *   php scripts/import_sql.php
 */

$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'inventario';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$ssl  = getenv('DB_SSL') === 'true' || getenv('DB_SSL') === '1';

$archivo = __DIR__ . '/../inventario.sql';
if (!file_exists($archivo)) {
    fwrite(STDERR, "No encuentro $archivo\n");
    exit(1);
}

$opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
if ($ssl) {
    $opciones[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, $opciones);
} catch (PDOException $e) {
    fwrite(STDERR, "No me puedo conectar: " . $e->getMessage() . "\n");
    exit(1);
}

/**
 * Trocea un dump SQL en sentencias individuales, respetando comillas,
 * comillas invertidas y comentarios /*! ... *\/ (para no cortar por un ";"
 * que va dentro de una cadena de texto).
 */
function trocearSql(string $sql): array
{
    $sentencias = [];
    $actual = '';
    $len = strlen($sql);
    $enComilla = null; // ' " ` o null
    $i = 0;
    while ($i < $len) {
        $c = $sql[$i];

        if ($enComilla !== null) {
            $actual .= $c;
            if ($c === '\\' && $i + 1 < $len) { // escape dentro de comillas
                $actual .= $sql[$i + 1];
                $i += 2;
                continue;
            }
            if ($c === $enComilla) {
                $enComilla = null;
            }
            $i++;
            continue;
        }

        if ($c === "'" || $c === '"' || $c === '`') {
            $enComilla = $c;
            $actual .= $c;
            $i++;
            continue;
        }

        // Comentario de linea -- ...
        if ($c === '-' && ($sql[$i + 1] ?? '') === '-') {
            while ($i < $len && $sql[$i] !== "\n") { $i++; }
            continue;
        }

        if ($c === ';') {
            $t = trim($actual);
            if ($t !== '') { $sentencias[] = $t; }
            $actual = '';
            $i++;
            continue;
        }

        $actual .= $c;
        $i++;
    }
    $t = trim($actual);
    if ($t !== '') { $sentencias[] = $t; }
    return $sentencias;
}

$sql = file_get_contents($archivo);
$sentencias = trocearSql($sql);

echo "Importando $archivo en $name @ $host (" . count($sentencias) . " sentencias) ...\n";
foreach ($sentencias as $n => $stmt) {
    try {
        $pdo->exec($stmt);
    } catch (PDOException $e) {
        fwrite(STDERR, "Fallo en la sentencia #$n: " . $e->getMessage() . "\n");
        fwrite(STDERR, substr($stmt, 0, 200) . "\n");
        exit(1);
    }
}

$total = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Listo. Tablas creadas: " . count($total) . "\n";
foreach ($total as $t) {
    echo "  - $t\n";
}
