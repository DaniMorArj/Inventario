<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Tiendas</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Tiendas'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="flex-grow-1 p-4" style="min-width:0;">

                <!-- MIGAS DE PAN -->
                <div class="mb-2">
                    <small class="text-muted">
                        <a href="../controller/index.php">Inicio</a> &gt; <strong>Tiendas</strong>
                    </small>
                </div>

                <h2 class="mb-3">Tiendas</h2>

                <form method="get" action="../controller/tienda.php" class="mb-3">
                    <div class="d-flex flex-column flex-lg-row gap-2 justify-content-between">
                        <!-- FILTROS -->
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <input class="form-control" type="text" name="buscar" placeholder="Buscar (nº o nombre)"
                                value="<?php echo $buscar; ?>">

                            <select class="form-select" name="sociedad">
                                <option value="">Todas las sociedades</option>
                                <?php foreach ($sociedades as $s) { ?>
                                    <?php if ($idSociedad == $s->id) { ?>
                                        <option value="<?php echo $s->id; ?>" selected>
                                    <?php } else { ?>
                                        <option value="<?php echo $s->id; ?>">
                                    <?php } ?>
                                        <?php echo $s->nombre . " (" . $s->cif . ")"; ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <select class="form-select" name="estado">
                                <?php if ($filtroEstado == '') { ?>
                                    <option value="" selected>Todas</option>
                                <?php } else { ?>
                                    <option value="">Todas</option>
                                <?php } ?>
                                <?php if ($filtroEstado == 'activa') { ?>
                                    <option value="activa" selected>Abiertas</option>
                                <?php } else { ?>
                                    <option value="activa">Abiertas</option>
                                <?php } ?>
                                <?php if ($filtroEstado == 'cerrada') { ?>
                                    <option value="cerrada" selected>Cerradas</option>
                                <?php } else { ?>
                                    <option value="cerrada">Cerradas</option>
                                <?php } ?>
                            </select>

                            <button class="btn btn-primary" type="submit">Filtrar</button>
                            <a class="btn btn-secondary" href="../controller/tienda.php">Limpiar</a>
                        </div>

                        <?php if ($_SESSION['rol'] == 'admin') { ?>
                            <?php if ($_SESSION['rol'] == 'admin') { ?><a class="btn btn-primary" href="../controller/tienda.php?action=alta">Alta Tienda</a><?php } ?>
                        <?php } ?>
                    </div>
                </form>

                <div class="border rounded overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nº</th>
                                    <th>Nombre</th>
                                    <th>Sociedad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!isset($tiendas) || count($tiendas) == 0) { ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No hay tiendas.</td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($tiendas as $t) { ?>
                                        <?php if (isset($t->estado) && $t->estado == 'cerrada') { ?>
                                            <tr class="table-danger" style="cursor:pointer;" onclick="window.location='../controller/tienda.php?action=ver&id=<?php echo $t->id; ?>'">
                                        <?php } else { ?>
                                            <tr style="cursor:pointer;" onclick="window.location='../controller/tienda.php?action=ver&id=<?php echo $t->id; ?>'">
                                        <?php } ?>
                                            <td><strong><?php echo $t->numero; ?></strong></td>
                                            <td>
                                                <strong><?php echo $t->nombre; ?></strong>
                                                <?php if (isset($t->estado) && $t->estado == 'cerrada') { ?>
                                                    <span class="badge bg-danger ms-2">Cerrada</span>
                                                <?php } ?>
                                                <?php if (isset($t->estado) && $t->estado == 'en_apertura') { ?>
                                                    <span class="badge bg-info ms-2">En apertura</span>
                                                <?php } ?>
                                            </td>
                                            <td><strong><?php echo $t->sociedad . " (" . $t->cif . ")"; ?></strong></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Paginador -->
                <?php if ($totalPaginas > 1) { ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center mb-0">

                            <?php if ($pagina <= 1) { ?>
                                <li class="page-item disabled">
                            <?php } else { ?>
                                <li class="page-item">
                            <?php } ?>
                                <a class="page-link" href="../controller/tienda.php?pagina=<?php echo $pagina - 1; ?>&buscar=<?php echo $buscar; ?>&sociedad=<?php echo $idSociedad; ?>&estado=<?php echo $filtroEstado; ?>">Anterior</a>
                            </li>

                            <?php for ($p = 1; $p <= $totalPaginas; $p++) { ?>
                                <?php if ($p == $pagina) { ?>
                                    <li class="page-item active">
                                <?php } else { ?>
                                    <li class="page-item">
                                <?php } ?>
                                    <a class="page-link" href="../controller/tienda.php?pagina=<?php echo $p; ?>&buscar=<?php echo $buscar; ?>&sociedad=<?php echo $idSociedad; ?>&estado=<?php echo $filtroEstado; ?>">
                                        <?php echo $p; ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($pagina >= $totalPaginas) { ?>
                                <li class="page-item disabled">
                            <?php } else { ?>
                                <li class="page-item">
                            <?php } ?>
                                <a class="page-link" href="../controller/tienda.php?pagina=<?php echo $pagina + 1; ?>&buscar=<?php echo $buscar; ?>&sociedad=<?php echo $idSociedad; ?>&estado=<?php echo $filtroEstado; ?>">Siguiente</a>
                            </li>

                        </ul>
                    </nav>
                <?php } ?>

            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white py-2">
        <div class="container-fluid px-3 px-md-4">
            <div class="d-flex align-items-center gap-3">

                <!-- Logo pequeño -->
                <a href="../controller/index.php" class="text-decoration-none">
                    <img src="../view/img/logo.png" alt="InventarioApp" style="height:35px;">
                </a>

                <!-- Texto -->
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