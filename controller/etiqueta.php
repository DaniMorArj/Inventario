<?php
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}

$codigo = '';
$modelo = '';

if (isset($_GET['codigo'])) { $codigo = $_GET['codigo']; }
if (isset($_GET['modelo'])) { $modelo = $_GET['modelo']; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Etiqueta - <?php echo $codigo; ?></title>
    <script src="../view/js/JsBarcode.all.min.js"></script>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estiloetiqueta.css">
</head>
<body>

    <div class="controles">
        <button class="btn-imprimir" onclick="window.print()"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"> Imprimir</button>
        <button class="btn-cerrar" onclick="window.close()">Cerrar</button>
    </div>

    <div class="etiqueta">
        <svg id="barcode"></svg>
        <div class="codigo-texto"><?php echo $codigo; ?></div>
    </div>

    <script>
        JsBarcode("#barcode", "<?php echo $codigo; ?>", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: false,
            margin: 0,
            background: "#ffffff",
            lineColor: "#000000"
        });
    </script>

</body>
</html>