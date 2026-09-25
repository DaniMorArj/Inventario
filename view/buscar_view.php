<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">    
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Buscador</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Buscador'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row flex-grow-1 g-0 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="col-12 col-lg-10 p-4">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Buscador</strong>
                    </small>
                </div>

                <h2 class="mb-4">Buscador global</h2>

                <!-- FORMULARIO DE BÚSQUEDA -->
                <form method="get" action="buscar.php" class="mb-4">
                    <div class="input-group" style="max-width:600px;">
                        <input type="text" class="form-control form-control-lg" name="q"
                            placeholder="Buscar trabajador, tienda o producto..."
                            value="<?php echo $texto; ?>" autofocus>
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </form>

                <?php if ($texto == '') { ?>

                    <div class="text-muted">Introduce un nombre, número de tienda o código de producto para buscar.</div>

                <?php } else { ?>

                    <?php
                    $totalResultados = count($trabajadores) + count($tiendas) + count($productos);
                    ?>

                    <p class="text-muted mb-4">
                        <?php if ($totalResultados == 0) { ?>
                            No se han encontrado resultados para <strong><?php echo $texto; ?></strong>.
                        <?php } else { ?>
                            <?php echo $totalResultados; ?> resultado<?php if ($totalResultados != 1) { echo 's'; } ?> para <strong><?php echo $texto; ?></strong>
                        <?php } ?>
                    </p>

                    <!-- TRABAJADORES -->
                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold d-flex justify-content-between align-items-center">
                            <span>Trabajadores</span>
                            <span class="badge bg-secondary"><?php echo count($trabajadores); ?></span>
                        </div>

                        <?php if (!isset($trabajadores) || count($trabajadores) == 0) { ?>
                            <div class="p-3 text-muted">Sin resultados.</div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cargo</th>
                                            <th>Centro</th>
                                            <th class="text-end">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trabajadores as $t) { ?>
                                            <tr>
                                                <td><?php echo $t->nombre; ?></td>
                                                <td>
                                                    <?php if (isset($t->cargo) && $t->cargo != '') { ?>
                                                        <?php echo $t->cargo; ?>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $t->centro_nombre; ?></td>
                                                <td class="text-end">
                                                    <a href="trabajador_detalle.php?id=<?php echo $t->id; ?>"
                                                        class="btn btn-sm btn-primary">Ver</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- TIENDAS -->
                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold d-flex justify-content-between align-items-center">
                            <span>Tiendas</span>
                            <span class="badge bg-secondary"><?php echo count($tiendas); ?></span>
                        </div>

                        <?php if (!isset($tiendas) || count($tiendas) == 0) { ?>
                            <div class="p-3 text-muted">Sin resultados.</div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nº</th>
                                            <th>Nombre</th>
                                            <th>Sociedad</th>
                                            <th class="text-end">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tiendas as $t) { ?>
                                            <tr>
                                                <td><?php echo $t->numero; ?></td>
                                                <td><?php echo $t->nombre; ?></td>
                                                <td><?php echo $t->sociedad; ?></td>
                                                <td class="text-end">
                                                    <a href="tienda.php?action=ver&id=<?php echo $t->id; ?>"
                                                        class="btn btn-sm btn-primary">Ver</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- PRODUCTOS -->
                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold d-flex justify-content-between align-items-center">
                            <span>Productos</span>
                            <span class="badge bg-secondary"><?php echo count($productos); ?></span>
                        </div>

                        <?php if (!isset($productos) || count($productos) == 0) { ?>
                            <div class="p-3 text-muted">Sin resultados.</div>
                        <?php } else { ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Código</th>
                                            <th>Modelo</th>
                                            <th>Tipo</th>
                                            <th>Categoría</th>
                                            <th>Asignado a</th>
                                            <th class="text-end">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos as $p) { ?>
                                            <tr>
                                                <td><?php echo $p->codigo; ?></td>
                                                <td><?php echo $p->modelo; ?></td>
                                                <td><?php echo $p->subtipo; ?></td>
                                                <td><?php echo $p->categoria; ?></td>
                                                <td>
                                                    <?php if (!isset($p->destino_tipo) || $p->destino_tipo == '') { ?>
                                                        <span class="text-success">Disponible</span>
                                                    <?php } elseif ($p->destino_tipo == 'tienda') { ?>
                                                        <a href="tienda.php?action=ver&id=<?php echo $p->destino_id; ?>"><?php echo $p->nombre_tienda; ?></a>
                                                        <span class="text-muted small">(tienda)</span>
                                                    <?php } else { ?>
                                                        <a href="trabajador_detalle.php?id=<?php echo $p->destino_id; ?>"><?php echo $p->nombre_trabajador; ?></a>
                                                        <span class="text-muted small">(trabajador)</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-end">
                                                    <a href="stock.php?action=ver&id=<?php echo $p->id; ?>"
                                                        class="btn btn-sm btn-primary">Ver</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>

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