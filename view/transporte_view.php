<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Transporte</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Transporte'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Transporte</strong>
                    </small>
                </div>

                <h2 class="mb-1">Transporte</h2>
                <div class="text-muted mb-4">Movimientos de material entre oficina y tiendas: envíos, recepciones y devoluciones.</div>

                <?php $esAdmin = ($_SESSION['rol'] == 'admin'); ?>

                <!-- TARJETAS RESUMEN -->
                <div class="row g-3 mb-4">

                    <?php if ($esAdmin) { ?>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <img src="../view/img/iconos/envio.svg" alt="" style="width:22px;height:22px;">
                                    <div class="text-muted small">Envíos en curso</div>
                                </div>
                                <div class="fw-bold" style="font-size:2rem;"><?php echo $enviosEnCurso; ?></div>
                                <div class="mt-2 text-muted small">Material que oficina envía a tiendas</div>
                                <a href="envio.php" class="btn btn-outline-secondary btn-sm mt-3">Ver envíos</a>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <img src="../view/img/iconos/recepcionar.svg" alt="" style="width:22px;height:22px;">
                                <div class="text-muted small">Recepciones pendientes</div>
                            </div>
                            <div class="fw-bold" style="font-size:2rem;"><?php echo $recepcionPendientes; ?></div>
                            <div class="mt-2 text-muted small">Material de tiendas cerradas por recibir</div>
                            <a href="recepcion.php" class="btn btn-outline-secondary btn-sm mt-3">Ver recepciones</a>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <img src="../view/img/iconos/devolucion.svg" alt="" style="width:22px;height:22px;">
                                <div class="text-muted small">Devoluciones pendientes</div>
                            </div>
                            <div class="fw-bold" style="font-size:2rem;"><?php echo $devolucionesPendientes; ?></div>
                            <div class="mt-2 text-muted small">Material (avería) que vuelve a oficina</div>
                            <a href="devolucion.php" class="btn btn-outline-secondary btn-sm mt-3">Ver devoluciones</a>
                        </div>
                    </div>

                </div>

                <!-- ENVÍOS EN CURSO -->
                <?php if ($esAdmin) { ?>
                    <div class="border rounded mb-4">
                        <div class="bg-primary bg-opacity-10 p-2 fw-semibold">Envíos en curso</div>
                        <div class="p-3">
                            <?php if (isset($enviosLista) && count($enviosLista) > 0) { ?>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr><th>Código</th><th>Modelo</th><th>Tienda destino</th><th>Estado</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($enviosLista as $e) { ?>
                                                <tr>
                                                    <td><?php echo $e->codigo; ?></td>
                                                    <td><?php echo $e->modelo; ?></td>
                                                    <td>
                                                        <?php if (isset($e->tienda_numero)) { echo $e->tienda_numero; } ?>
                                                        <?php if (isset($e->tienda_nombre) && $e->tienda_nombre != '') { ?>
                                                            — <?php echo $e->tienda_nombre; ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($e->estado == 'preparando') { ?>
                                                            <span class="badge bg-secondary">Preparando</span>
                                                        <?php } else { ?>
                                                            <span class="badge bg-primary">Enviado</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <div class="text-muted">No hay envíos en curso.</div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <!-- RECEPCIONES PENDIENTES -->
                <div class="border rounded mb-4">
                    <div class="bg-warning bg-opacity-25 p-2 fw-semibold">Recepciones pendientes</div>
                    <div class="p-3">
                        <?php if (isset($recepcionesLotes) && count($recepcionesLotes) > 0) { ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr><th>Tienda</th><th>Cerrada</th><th>Pendientes</th><th>Incidencias</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recepcionesLotes as $lote) { ?>
                                            <tr>
                                                <td>
                                                    <?php if (isset($lote['tienda_numero'])) { echo $lote['tienda_numero']; } ?>
                                                    <?php if (isset($lote['tienda_nombre']) && $lote['tienda_nombre'] != '') { ?>
                                                        — <?php echo $lote['tienda_nombre']; ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-muted small"><?php echo $lote['fecha_cierre']; ?></td>
                                                <td><span class="badge bg-warning text-dark"><?php echo $lote['pendientes']; ?></span></td>
                                                <td><span class="badge bg-danger"><?php echo $lote['incidencias']; ?></span></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="text-muted">No hay material pendiente de recepción.</div>
                        <?php } ?>
                    </div>
                </div>

                <!-- DEVOLUCIONES PENDIENTES -->
                <div class="border rounded mb-4">
                    <div class="bg-warning bg-opacity-25 p-2 fw-semibold">Devoluciones pendientes</div>
                    <div class="p-3">
                        <?php if (isset($devolucionesLista) && count($devolucionesLista) > 0) { ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr><th>Código</th><th>Modelo</th><th>Tienda origen</th><th>Motivo</th><th>Estado</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($devolucionesLista as $d) { ?>
                                            <tr>
                                                <td><?php echo $d->codigo; ?></td>
                                                <td><?php echo $d->modelo; ?></td>
                                                <td>
                                                    <?php if (isset($d->tienda_numero)) { echo $d->tienda_numero; } ?>
                                                    <?php if (isset($d->tienda_nombre) && $d->tienda_nombre != '') { ?>
                                                        — <?php echo $d->tienda_nombre; ?>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if (isset($d->motivo) && $d->motivo != '') { echo $d->motivo; } else { ?>
                                                        <span class="text-muted">—</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if ($d->estado == 'pendiente') { ?>
                                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-danger">Incidencia</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="text-muted">No hay devoluciones pendientes.</div>
                        <?php } ?>
                    </div>
                </div>

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
