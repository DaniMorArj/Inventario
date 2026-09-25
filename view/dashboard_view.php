<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Dashboard'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Dashboard</strong>
                    </small>
                </div>

                <h2 class="mb-4">Dashboard</h2>

                <!-- TARJETAS RESUMEN -->
                <div class="row g-3 mb-4">

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small mb-1">Stock total</div>
                            <div class="fw-bold" style="font-size:2rem;">
                                <?php if (isset($resumenStock->total)) { echo $resumenStock->total; } else { echo 0; } ?>
                            </div>
                            <div class="mt-2 d-flex gap-3">
                                <span class="text-success small">
                                    <?php if (isset($resumenStock->disponibles)) { echo $resumenStock->disponibles; } else { echo 0; } ?> disponibles
                                </span>
                                <span class="text-secondary small">
                                    <?php if (isset($resumenStock->asignados)) { echo $resumenStock->asignados; } else { echo 0; } ?> asignados
                                </span>
                            </div>
                            <?php if (isset($menuStock) && $menuStock) { ?>
                                <a href="stock.php" class="btn btn-outline-secondary btn-sm mt-3">Ver stock</a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small mb-1">Tiendas</div>
                            <div class="fw-bold" style="font-size:2rem;">
                                <?php echo $totalTiendas; ?>
                            </div>
                            <div class="mt-2 text-muted small">Tiendas registradas</div>
                            <a href="tienda.php" class="btn btn-outline-secondary btn-sm mt-3">Ver tiendas</a>
                        </div>
                    </div>

                    <?php foreach ($resumenTrabajadores as $centro) { ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small mb-1"><?php echo $centro->nombre; ?></div>
                                <div class="fw-bold" style="font-size:2rem;">
                                    <?php echo $centro->total; ?>
                                </div>
                                <div class="mt-2 text-muted small">Trabajadores activos</div>
                                <a href="<?php echo $centro->tipo; ?>.php" class="btn btn-outline-secondary btn-sm mt-3">Ver trabajadores</a>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($_SESSION['rol'] == 'admin') { ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small mb-1">Packs de apertura</div>
                            <div class="fw-bold" style="font-size:2rem;">
                                <?php if (isset($packsPendientes)) { echo $packsPendientes; } else { echo 0; } ?>
                            </div>
                            <div class="mt-2 text-muted small">Pendientes / en curso</div>
                            <a href="pack.php" class="btn btn-outline-secondary btn-sm mt-3">Ver packs</a>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small mb-1">Recepciones</div>
                            <div class="fw-bold" style="font-size:2rem;">
                                <?php if (isset($recepcionesPendientes)) { echo $recepcionesPendientes; } else { echo 0; } ?>
                            </div>
                            <div class="mt-2 text-muted small">Material pendiente de recibir</div>
                            <a href="recepcion.php" class="btn btn-outline-secondary btn-sm mt-3">Ver recepciones</a>
                        </div>
                    </div>

                </div>

                <!-- ÚLTIMAS ASIGNACIONES -->
                <div class="border rounded mb-4">
                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Últimas asignaciones</div>
                    <div class="p-3">

                        <?php if (!isset($ultimasAsignaciones) || $ultimasAsignaciones == '') { ?>
                            <div class="text-muted">No hay asignaciones registradas.</div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código</th>
                                            <th>Modelo</th>
                                            <th>Categoría</th>
                                            <th>Destino</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ultimasAsignaciones as $a) { ?>
                                            <tr>
                                                <td><?php echo $a->codigo; ?></td>
                                                <td><?php echo $a->modelo; ?></td>
                                                <td><?php echo $a->subtipo; ?></td>
                                                <td>
                                                    <?php if ($a->destino_tipo == 'tienda') { ?>
                                                        <?php if (isset($a->nombre_tienda)) { echo $a->nombre_tienda; } ?>
                                                    <?php } else { ?>
                                                        <?php if (isset($a->nombre_trabajador)) { echo $a->nombre_trabajador; } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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