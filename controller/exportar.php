<?php
/*Para la exportación PDF o Excel usé la librería FPDF que se integra con PHP mediante include_once y permite generar archivos PDF. 
Para Excel, en lugar de usar una librería específica, se generan tablas HTML con encabezados que indican al navegador 
que el contenido es un archivo Excel, lo cual es una técnica común para exportar datos a Excel sin necesidad de librerías adicionales.*/

session_start();// Verificar si el usuario ha iniciado sesión
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {// Verificar si el usuario ha iniciado sesión
    header('location:login.php');
    exit;
}

include_once '../model/Exportar.php';//Llamamos a exportar para usar sus métodos
include_once '../lib/fpdf/fpdf.php';//LLamamos a la librería FPDF para generar PDFs


$tipo   = '';
$formato = '';

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
}

if (isset($_GET['formato'])) {
    $formato = $_GET['formato'];
}

$idTienda = 0;
if (isset($_GET['id'])) {
    $idTienda = $_GET['id'];
}

// ============================================================
// EXPORTAR TRABAJADORES
// ============================================================
if ($tipo == 'trabajadores') {// Solo exportamos trabajadores porque el resto de tipos ya tienen su exportación en su propio controlador

    $datos = Exportar::getTrabajadores();

    if ($formato == 'excel') {

        // Para exportar a Excel, se envían encabezados específicos y se genera una tabla HTML que Excel puede interpretar
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="trabajadores.xls"');
        header('Cache-Control: max-age=0');

        // Para evitar problemas de caracteres especiales en Excel, se recomienda enviar un BOM UTF-8 al inicio del archivo
        echo "\xEF\xBB\xBF"; // BOM UTF-8
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Nombre</th>';
        echo '<th>Email</th>';
        echo '<th>Cargo</th>';
        echo '<th>Centro</th>';
        echo '<th>Departamento</th>';
        echo '</tr>';

        foreach ($datos as $t) {// Algunos trabajadores podrían no tener departamento asignado, así que lo manejamos para evitar errores
            $departamento = '';
            if (isset($t->departamento)) {
                $departamento = $t->departamento;
            }
            echo '<tr>';
            echo '<td>' . $t->nombre . '</td>';
            echo '<td>' . $t->email . '</td>';
            echo '<td>' . $t->cargo . '</td>';
            echo '<td>' . $t->centro . '</td>';
            echo '<td>' . $departamento . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit;

    } elseif ($formato == 'pdf') {// Para exportar a PDF, se utiliza la librería FPDF para generar un documento con formato adecuado

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Listado de Trabajadores'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(200, 200, 200);

        $pdf->Cell(60, 8, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'Email', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Cargo', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Centro', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Departamento', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);

        foreach ($datos as $t) {
            $departamento = '';
            if (isset($t->departamento)) {
                $departamento = $t->departamento;
            }
            $pdf->Cell(60, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $t->nombre), 1);
            $pdf->Cell(65, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $t->email), 1);
            $pdf->Cell(40, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $t->cargo), 1);
            $pdf->Cell(50, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $t->centro), 1);
            $pdf->Cell(50, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $departamento), 1, 1);
        }

        $pdf->Output('D', 'trabajadores.pdf');
        exit;
    }
}

// ============================================================
// EXPORTAR STOCK
// ============================================================
if ($tipo == 'stock') {

    $datos = Exportar::getStock();

    if ($formato == 'excel') {

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="inventario.xls"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF"; // BOM UTF-8
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Código</th>';
        echo '<th>Modelo</th>';
        echo '<th>Categoría</th>';
        echo '<th>Subtipo</th>';
        echo '<th>Estado</th>';
        echo '<th>Asignado a</th>';
        echo '</tr>';

        foreach ($datos as $p) {
            $estado = 'Disponible';
            $asignado = '-';

            if (isset($p->destino_tipo) && $p->destino_tipo != '') {
                $estado = 'Asignado';
                if ($p->destino_tipo == 'tienda') {
                    $asignado = $p->nombre_tienda . ' (tienda)';
                } else {
                    $asignado = $p->nombre_trabajador . ' (trabajador)';
                }
            }

            echo '<tr>';
            echo '<td>' . $p->codigo . '</td>';
            echo '<td>' . $p->modelo . '</td>';
            echo '<td>' . $p->categoria . '</td>';
            echo '<td>' . $p->subtipo . '</td>';
            echo '<td>' . $estado . '</td>';
            echo '<td>' . $asignado . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit;

    } elseif ($formato == 'pdf') {

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Inventario de Stock'), 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(200, 200, 200);

        $pdf->Cell(35, 8, 'Codigo', 1, 0, 'C', true);
        $pdf->Cell(55, 8, 'Modelo', 1, 0, 'C', true);
        $pdf->Cell(50, 8, 'Categoria', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Subtipo', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Estado', 1, 0, 'C', true);
        $pdf->Cell(67, 8, 'Asignado a', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);

        foreach ($datos as $p) {
            $estado = 'Disponible';
            $asignado = '-';

            if (isset($p->destino_tipo) && $p->destino_tipo != '') {
                $estado = 'Asignado';
                if ($p->destino_tipo == 'tienda') {
                    $asignado = $p->nombre_tienda . ' (tienda)';
                } else {
                    $asignado = $p->nombre_trabajador . ' (trabajador)';
                }
            }

            $pdf->Cell(35, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->codigo), 1);
            $pdf->Cell(55, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->modelo), 1);
            $pdf->Cell(50, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->categoria), 1);
            $pdf->Cell(35, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->subtipo), 1);
            $pdf->Cell(25, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $estado), 1);
            $pdf->Cell(67, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $asignado), 1, 1);
        }

        $pdf->Output('D', 'inventario.pdf');
        exit;
    }
}

// ============================================================
// EXPORTAR MATERIAL DE UNA TIENDA
// ============================================================
if ($tipo == 'tienda' && $idTienda > 0) {

    $datos  = Exportar::getMaterialTienda($idTienda);
    $tienda = Exportar::getDatosTienda($idTienda);

    if ($formato == 'excel') {

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="tienda_' . $tienda->numero . '.xls"');
        header('Cache-Control: max-age=0');

        echo "\xEF\xBB\xBF"; // BOM UTF-8
        echo '<table border="1">';

        // Datos de la tienda - solo 2 columnas
        echo '<tr><th colspan="2" style="background:#ddd">Datos de la Tienda</th></tr>';
        echo '<tr><td><b>Nombre</b></td><td>' . $tienda->nombre . '</td></tr>';
        echo '<tr><td><b>Numero</b></td><td>' . $tienda->numero . '</td></tr>';
        echo '<tr><td><b>Sociedad</b></td><td>' . $tienda->sociedad . ' (' . $tienda->cif . ')</td></tr>';
        echo '<tr><td><b>Movil</b></td><td>' . $tienda->movil . '</td></tr>';
        echo '<tr><td><b>Fijo</b></td><td>' . $tienda->fijo . '</td></tr>';
        echo '<tr><td><b>Email</b></td><td>' . $tienda->email . '</td></tr>';

        $obs = '';
        if (isset($tienda->observaciones)) { $obs = $tienda->observaciones; }
        echo '<tr><td><b>Observaciones</b></td><td>' . $obs . '</td></tr>';

        $cm = '';
        if (isset($tienda->cluster_manager)) { $cm = $tienda->cluster_manager; }
        echo '<tr><td><b>Cluster Manager</b></td><td>' . $cm . '</td></tr>';

        echo '</table>';
        echo '<br>';

        // Material asignado - tabla separada con 3 columnas
        echo '<table border="1">';
        echo '<tr><th colspan="3" style="background:#ddd">Material Asignado</th></tr>';
        echo '<tr>';
        echo '<th>Codigo</th>';
        echo '<th>Modelo</th>';
        echo '<th>Tipo</th>';
        echo '</tr>';

        foreach ($datos as $p) {
            echo '<tr>';
            echo '<td>' . $p->codigo . '</td>';
            echo '<td>' . $p->modelo . '</td>';
            echo '<td>' . $p->subtipo . '</td>';
            echo '</tr>';
        }

        echo '</table>';
        exit;

    } elseif ($formato == 'pdf') {

        $pdf = new FPDF('L', 'mm', 'A4');// Formato horizontal para tener más espacio
        $pdf->AddPage();// Agregamos la primera página con los datos de la tienda
        $pdf->SetFont('Arial', 'B', 14);// Título con el nombre de la tienda
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Ficha Tienda: ' . $tienda->nombre . ' (N ' . $tienda->numero . ')'), 0, 1, 'C');
        $pdf->Ln(3);

        // Datos de la tienda
        $pdf->SetFont('Arial', 'B', 11);// Subtítulo para la sección de datos de la tienda
        $pdf->SetFillColor(220, 220, 220);// Fondo gris para el encabezado
        $pdf->Cell(0, 8, 'Datos de la Tienda', 1, 1, 'L', true);// Encabezado de la sección
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(50, 7, 'Nombre:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $tienda->nombre), 0, 1);

        $pdf->Cell(50, 7, 'Numero:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $tienda->numero), 0, 1);

        $pdf->Cell(50, 7, 'Sociedad:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $tienda->sociedad . ' (' . $tienda->cif . ')'), 0, 1);

        $pdf->Cell(50, 7, 'Movil:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $tienda->movil), 0, 1);

        $pdf->Cell(50, 7, 'Fijo:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $tienda->fijo), 0, 1);

        $pdf->Cell(50, 7, 'Email:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $tienda->email), 0, 1);

        $obs = '';
        if (isset($tienda->observaciones)) { $obs = $tienda->observaciones; }
        $pdf->Cell(50, 7, 'Observaciones:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $obs), 0, 1);

        $cm = '';
        if (isset($tienda->cluster_manager)) { $cm = $tienda->cluster_manager; }
        $pdf->Cell(50, 7, 'Cluster Manager:', 0);
        $pdf->Cell(0, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $cm), 0, 1);

        $pdf->Ln(5);

        // Material asignado
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 8, 'Material Asignado', 1, 1, 'L', true);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(200, 200, 200);

        $pdf->Cell(55, 8, 'Codigo', 1, 0, 'C', true);
        $pdf->Cell(120, 8, 'Modelo', 1, 0, 'C', true);
        $pdf->Cell(102, 8, 'Tipo', 1, 1, 'C', true);
        $pdf->SetFont('Arial', '', 9);

        foreach ($datos as $p) {
            $pdf->Cell(55, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->codigo), 1);
            $pdf->Cell(120, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->modelo), 1);
            $pdf->Cell(102, 7, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $p->subtipo), 1, 1);
        }

        $pdf->Output('D', 'tienda_' . $tienda->numero . '.pdf');
        exit;
    }
}

// Si no coincide ningún tipo redirigimos al dashboard
header('location:index.php');
exit;