<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Detalle Producto</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Detalle Stock'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <a href="stock.php">Stock</a> &gt; <strong>Detalle</strong>
                    </small>
                </div>

                <h2 class="mb-3">Detalle Producto</h2>

                <?php if (!isset($errores)) {
                    $errores = array();
                } ?>
                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                    <a href="stock.php" class="btn btn-secondary">Volver</a>
                <?php } else { ?>

                    <?php
                    $asignado = false;
                    if (isset($producto->destino_tipo) && $producto->destino_tipo != '' && isset($producto->destino_id) && $producto->destino_id != '') {
                        $asignado = true;
                    }

                    // Estados en tránsito: no son una asignación "fija" y no se liberan a mano.
                    $esTransito = false;

                    if ($asignado && $producto->destino_tipo == 'envio') {
                        $envioEstado = '';
                        if (isset($producto->envio_estado)) { $envioEstado = $producto->envio_estado; }
                        if ($envioEstado == 'preparando') {
                            $estadoTexto = 'Preparando envío';
                            $badgeClass = 'bg-secondary';
                        } else {
                            $estadoTexto = 'Enviado';
                            $badgeClass = 'bg-primary';
                        }
                        $ubicacion = 'En envío a tienda';
                        $esTransito = true;
                    } else if ($asignado && $producto->destino_tipo == 'devolucion') {
                        $estadoTexto = 'En devolución';
                        $badgeClass = 'bg-warning text-dark';
                        $ubicacion = 'En devolución a oficina';
                        $esTransito = true;
                    } else if ($asignado && $producto->destino_tipo == 'recepcion') {
                        $estadoTexto = 'En recepción';
                        $badgeClass = 'bg-warning text-dark';
                        $ubicacion = 'En recepción';
                        $esTransito = true;
                    } else if ($asignado && $producto->destino_tipo == 'pack') {
                        $estadoTexto = 'En pack';
                        $badgeClass = 'bg-primary';
                        $ubicacion = 'En pack de apertura';
                        $esTransito = true;
                    } else if ($asignado) {
                        $estadoTexto = 'Asignado';
                        $badgeClass = 'bg-success';
                        $ubicacion = $producto->destino_tipo;
                    } else {
                        $estadoTexto = 'Disponible';
                        $badgeClass = 'bg-info';
                        $ubicacion = 'No Ubicado';
                    }

                    // Condición / ciclo de vida (mismo criterio que el listado de Stock)
                    $cond = '';
                    if (isset($producto->estado)) { $cond = $producto->estado; }
                    $condLabel = $cond;
                    if (isset($condiciones[$cond])) { $condLabel = $condiciones[$cond]; }
                    $condClass = 'bg-secondary';
                    if ($cond == 'nuevo') { $condClass = 'bg-success'; }
                    if ($cond == 'reacondicionado') { $condClass = 'bg-info text-dark'; }
                    if ($cond == 'averiado') { $condClass = 'bg-warning text-dark'; }
                    if ($cond == 'en_garantia') { $condClass = 'bg-primary'; }
                    if ($cond == 'en_reparacion') { $condClass = 'bg-info text-dark'; }
                    if ($cond == 'para_piezas') { $condClass = 'bg-dark'; }
                    if ($cond == 'desechado') { $condClass = 'bg-danger'; }

                    $codigo = '';
                    if (isset($producto->codigo)) {
                        $codigo = $producto->codigo;
                    }

                    $categoria = '';
                    if (isset($producto->categoria_nombre)) {
                        $categoria = $producto->categoria_nombre;
                    }

                    $subtipo = '';
                    if (isset($producto->subtipo)) {
                        $subtipo = $producto->subtipo;
                    }

                    $modelo = '';
                    if (isset($producto->modelo)) {
                        $modelo = $producto->modelo;
                    }
                    ?>

                    <div class="border rounded p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-semibold" style="font-size:18px;">
                                <?php echo $codigo; ?>
                            </div>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $estadoTexto; ?></span>
                        </div>

                        <div><strong>Categoría:</strong> <?php echo $categoria; ?></div>
                        <div><strong>Subtipo:</strong> <?php echo $subtipo; ?></div>
                        <div><strong>Modelo:</strong> <?php echo $modelo; ?></div>
                        <div><strong>Ubicación:</strong> <?php echo $ubicacion; ?></div>
                        <div class="mt-1"><strong>Condición:</strong> <span class="badge <?php echo $condClass; ?>"><?php echo $condLabel; ?></span></div>

                        <div class="mt-3 d-flex gap-2 flex-wrap">

                            <!--BOTON IMPRIMIR ETIQUETA-->
                            <a class="btn btn-outline-dark btn-sm" target="_blank"
                                href="../controller/etiqueta.php?codigo=<?php echo $producto->codigo; ?>&modelo=<?php echo $producto->modelo; ?>">
                                <img src="../view/img/iconos/etiqueta.svg" alt="" style="width:16px;height:16px;">
                            </a>
                            <?php if ($_SESSION['rol'] == 'admin') { ?>
                                <a class="btn btn-outline-secondary" href="stock.php?action=editar&id=<?php echo $producto->id; ?>">Editar</a>
                            <?php } ?>

                            <?php if ($asignado) { ?>
                                <?php if (isset($linkDestino) && $linkDestino != '') { ?>
                                    <a class="btn btn-outline-primary" href="<?php echo $linkDestino; ?>"><?php echo $linkDestinoTexto; ?></a>
                                <?php } ?>
                                <?php if ($_SESSION['rol'] == 'admin' && !$esTransito) { ?>
                                    <a class="btn btn-danger" href="stock.php?action=liberar&id=<?php echo $producto->id; ?>"
                                        onclick="return confirm('¿Seguro que quieres liberar este producto?');">Liberar</a>
                                <?php } ?>
                                <?php if ($esTransito) { ?>
                                    <span class="text-muted small align-self-center">Gestiónalo desde su panel (<?php echo $linkDestinoTexto; ?>).</span>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if ($_SESSION['rol'] == 'admin') { ?>
                                    <?php if (isset($puedeAsignar) && $puedeAsignar) { ?>
                                        <a class="btn btn-primary" href="stock.php?action=asignar&id=<?php echo $producto->id; ?>">Asignar</a>
                                    <?php } else { ?>
                                        <span class="text-muted small align-self-center">
                                            No asignable por su condición (<?php echo $condLabel; ?>). Cambia la condición desde <strong>Editar</strong> para poder asignarlo.
                                        </span>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Ficha técnica -->
                    <div class="border rounded p-3 mb-4">
                        <div class="fw-semibold mb-2">Ficha técnica</div>

                        <?php if (!isset($atributosProducto)) {
                            $atributosProducto = array();
                        } ?>
                        <?php if (!isset($atributosProducto) || $atributosProducto == '') { ?>
                            <div class="text-muted">Sin ficha técnica guardada.</div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <tbody>
                                        <?php foreach ($atributosProducto as $a) { ?>
                                            <?php
                                            $valor = '';
                                            if (isset($a->valor)) {
                                                $valor = $a->valor;
                                            }
                                            ?>
                                            <tr>
                                                <td style="width:35%"><strong><?php echo $a->nombre; ?></strong></td>
                                                <td>
                                                    <?php echo $valor; ?>
                                                    <?php if (isset($a->unidad) && $a->unidad != '') { ?>
                                                        <span class="text-muted"><?php echo $a->unidad; ?></span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

                    <a href="stock.php" class="btn btn-secondary">Volver</a>
                    
                <?php } ?>

            </section>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white py-2">
        <div class="container-fluid px-3 px-md-4">
            <div class="d-flex align-items-center gap-3">
                <a href="index.php" class="text-decoration-none">
                    <img src="../view/img/logo.png" alt="InventarioApp" style="height:35px;">
                </a>
                <div class="container text-center small">
                    Gestión de inventario. Todos los derechos reservados <br>
                    Dirección: Calle Ejemplo, 1 · 00000 Ciudad <br>
                    Teléfono: +34 600 000 000
                </div>
            </div>
        </div>
    </footer>

    <script src="../view/js/bootstrap.bundle.min.js"></script>
</body>

</html>