<?php
// Albarán PDF de un pack de apertura (FPDF). Para imprimir y meter en la caja.
session_start();
require_once __DIR__ . '/../lib/AccessGate.php';

if (!isset($_SESSION['usuario'])) {
    header('location:login.php');
    exit;
}
if ($_SESSION['rol'] != 'admin') {
    header('location:index.php');
    exit;
}

include_once '../model/InventarioDB.php';
include_once '../model/Pack.php';
include_once '../lib/fpdf/fpdf.php';

$idPack = 0;
if (isset($_GET['id'])) {
    $idPack = (int)$_GET['id'];
}

$conexion = InventarioDB::connectDB();
$pack = Pack::obtener($conexion, $idPack);

if (!$pack) {
    $conexion = null;
    header('location:pack.php');
    exit;
}

$lineas = Pack::lineas($conexion, $idPack);
$conexion = null;

function utf($texto)
{
    return iconv("UTF-8", "ISO-8859-1//TRANSLIT", $texto);
}

$tiendaTexto = '';
if (isset($pack->tienda_numero)) { $tiendaTexto = 'Tienda ' . $pack->tienda_numero; }
if (isset($pack->tienda_nombre) && $pack->tienda_nombre != '') { $tiendaTexto = $tiendaTexto . ' - ' . $pack->tienda_nombre; }

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Cabecera
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf('Albarán - Pack de Apertura'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, utf($pack->codigo), 0, 1, 'C');
$pdf->Ln(3);

// Datos del pack
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 7, utf('Destino:'), 0);
$pdf->Cell(0, 7, utf($tiendaTexto), 0, 1);

$pdf->Cell(40, 7, utf('Estado:'), 0);
$pdf->Cell(0, 7, utf($pack->estado), 0, 1);

$pdf->Cell(40, 7, utf('Fecha creación:'), 0);
$pdf->Cell(0, 7, utf($pack->fecha_creacion), 0, 1);

$usu = '';
if (isset($pack->usuario_creacion)) { $usu = $pack->usuario_creacion; }
$pdf->Cell(40, 7, utf('Preparado por:'), 0);
$pdf->Cell(0, 7, utf($usu), 0, 1);

$pdf->Ln(4);

// Tabla de material
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(0, 8, utf('Material del pack (' . count($lineas) . ' items)'), 1, 1, 'L', true);

$pdf->SetFont('Arial', 'B', 9);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(45, 8, utf('Código'), 1, 0, 'C', true);
$pdf->Cell(90, 8, utf('Modelo'), 1, 0, 'C', true);
$pdf->Cell(55, 8, utf('Categoría'), 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
foreach ($lineas as $l) {
    $cat = '';
    if (isset($l->categoria)) { $cat = $l->categoria; }
    $pdf->Cell(45, 7, utf($l->codigo), 1);
    $pdf->Cell(90, 7, utf($l->modelo), 1);
    $pdf->Cell(55, 7, utf($cat), 1, 1);
}

$pdf->Ln(10);

// Firma de recepción
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 7, utf('Recibido por: ______________________________     Fecha: ____________'), 0, 1);

$pdf->Output('I', 'albaran_' . $pack->codigo . '.pdf');
exit;
