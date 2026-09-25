<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <?php if (isset($tituloPagina)) { ?>
        <title><?php echo $tituloPagina; ?></title>
    <?php } else { ?>
        <title>Centro</title>
    <?php } ?>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong><?php echo $tituloPagina; ?></strong>
                    </small>
                </div>

                <?php $verBajasFlag = (isset($verBajas) && $verBajas); ?>

                <!-- CONMUTADOR ACTIVOS / BAJAS -->
                <div class="btn-group mb-3" role="group">
                    <?php if ($verBajasFlag) { ?>
                        <a href="<?php echo $active; ?>.php" class="btn btn-outline-primary">Activos</a>
                        <a href="<?php echo $active; ?>.php?ver=bajas" class="btn btn-primary">Dados de baja</a>
                    <?php } else { ?>
                        <a href="<?php echo $active; ?>.php" class="btn btn-primary">Activos</a>
                        <a href="<?php echo $active; ?>.php?ver=bajas" class="btn btn-outline-primary">Dados de baja</a>
                    <?php } ?>
                </div>

                <!-- FILTROS -->
                <form class="d-flex flex-wrap gap-2 align-items-center mb-3" method="get">
                    <?php if ($verBajasFlag) { ?>
                        <input type="hidden" name="ver" value="bajas">
                    <?php } ?>
                    <div class="input-group" style="max-width:320px;">
                        <input class="form-control" type="text" name="buscar" placeholder="Buscar"
                            value="<?php echo $buscar; ?>">
                    </div>

                    <select class="form-select" name="depto" style="max-width:220px;">
                        <option value="0">Departamento</option>
                        <?php foreach ($departamentos as $d) { ?>
                            <?php if ($idDepto == $d->id) { ?>
                                <option value="<?php echo $d->id; ?>" selected>
                            <?php } else { ?>
                                <option value="<?php echo $d->id; ?>">
                            <?php } ?>
                                <?php echo $d->nombre; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <select class="form-select" name="orden" style="max-width:220px;">
                        <option value="">Ordenar por..</option>
                        <?php if ($orden == 'nombre_asc') { ?>
                            <option value="nombre_asc" selected>Nombre (A-Z)</option>
                        <?php } else { ?>
                            <option value="nombre_asc">Nombre (A-Z)</option>
                        <?php } ?>
                        <?php if ($orden == 'nombre_desc') { ?>
                            <option value="nombre_desc" selected>Nombre (Z-A)</option>
                        <?php } else { ?>
                            <option value="nombre_desc">Nombre (Z-A)</option>
                        <?php } ?>
                        <?php if ($orden == 'depto_asc') { ?>
                            <option value="depto_asc" selected>Departamento (A-Z)</option>
                        <?php } else { ?>
                            <option value="depto_asc">Departamento (A-Z)</option>
                        <?php } ?>
                        <?php if ($orden == 'depto_desc') { ?>
                            <option value="depto_desc" selected>Departamento (Z-A)</option>
                        <?php } else { ?>
                            <option value="depto_desc">Departamento (Z-A)</option>
                        <?php } ?>
                    </select>

                    <button class="btn btn-primary" type="submit">Filtrar</button>
                    <a class="btn btn-outline-secondary" href="<?php echo $active; ?>.php">Limpiar</a>
                    <!-- BOTONES EXPORTAR -->
                    <a href="../controller/exportar.php?tipo=trabajadores&formato=pdf" target="_blank" class="btn btn-danger btn-sm">Exportar PDF</a>
                    <a href="../controller/exportar.php?tipo=trabajadores&formato=excel" class="btn btn-success btn-sm">Exportar Excel</a>
                </form>


                <!-- TABLA -->
                <div class="table-responsive border rounded">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Departamento</th>
                                <?php if ($verBajasFlag) { ?>
                                    <th>Fecha de baja</th>
                                    <th class="text-end">Acción</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!isset($trabajadores) || count($trabajadores) == 0) { ?>
                                <tr>
                                    <td colspan="<?php if ($verBajasFlag) { echo 4; } else { echo 2; } ?>" class="text-muted">No hay trabajadores.</td>
                                </tr>
                            <?php } else { ?>
                                <?php foreach ($trabajadores as $t) { ?>
                                    <tr style="cursor:pointer;" onclick="window.location='trabajador_detalle.php?id=<?php echo $t->id; ?>'">
                                        <td><strong><?php echo $t->nombre; ?></strong></td>
                                        <td>
                                            <strong>
                                            <?php if (isset($t->departamento)) { ?>
                                                <?php echo $t->departamento; ?>
                                            <?php } ?>
                                            </strong>
                                        </td>
                                        <?php if ($verBajasFlag) { ?>
                                            <td>
                                                <?php if (isset($t->fecha_baja) && $t->fecha_baja != '') { ?>
                                                    <?php echo $t->fecha_baja; ?>
                                                <?php } ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($_SESSION['rol'] == 'admin') { ?>
                                                    <a href="trabajador.php?action=reactivar&id=<?php echo $t->id; ?>"
                                                       class="btn btn-sm btn-success"
                                                       onclick="event.stopPropagation(); return confirm('¿Reactivar a este trabajador?');">Reactivar</a>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                <div class="d-flex justify-content-center my-4">
                    <nav>
                        <ul class="pagination">
                            <?php
                            $qBase = "buscar=" . $buscar . "&depto=" . $idDepto . "&orden=" . $orden;
                            if ($verBajasFlag) { $qBase .= "&ver=bajas"; }
                            $self = $active . ".php";
                            ?>
                            <?php if ($pagina <= 1) { ?>
                                <li class="page-item disabled">
                            <?php } else { ?>
                                <li class="page-item">
                            <?php } ?>
                                <a class="page-link" href="<?php echo $self; ?>?<?php echo $qBase; ?>&pagina=<?php echo $pagina - 1; ?>">Anterior</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                                <?php if ($i == $pagina) { ?>
                                    <li class="page-item active">
                                <?php } else { ?>
                                    <li class="page-item">
                                <?php } ?>
                                    <a class="page-link" href="<?php echo $self; ?>?<?php echo $qBase; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>

                            <?php if ($pagina >= $totalPaginas) { ?>
                                <li class="page-item disabled">
                            <?php } else { ?>
                                <li class="page-item">
                            <?php } ?>
                                <a class="page-link" href="<?php echo $self; ?>?<?php echo $qBase; ?>&pagina=<?php echo $pagina + 1; ?>">Siguiente</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- BOTÓN ALTA -->
                <?php if ($_SESSION['rol'] == 'admin') { ?>
                <div class="d-flex justify-content-center">
                    <a href="trabajador.php?action=alta&tipo=<?php echo $active; ?>" class="btn btn-primary">
                        Alta Trabajador
                    </a>
                </div>
                <?php } ?>

            </section>
        </div>
    </main>

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