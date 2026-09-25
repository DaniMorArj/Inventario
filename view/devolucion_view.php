<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Devoluciones</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Devoluciones'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Devoluciones</strong>
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="mb-0">Material devuelto a oficina</h2>
                        <div class="text-muted small">Material (normalmente averiado) que las tiendas devuelven a oficina, pendiente de recibir y comprobar.</div>
                    </div>
                    <div>
                        <?php if (isset($verTodas) && $verTodas) { ?>
                            <a href="devolucion.php" class="btn btn-outline-secondary btn-sm">Ver solo pendientes</a>
                        <?php } else { ?>
                            <a href="devolucion.php?ver=todas" class="btn btn-outline-secondary btn-sm">Ver todo el histórico</a>
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($devoluciones) && count($devoluciones) > 0) { ?>

                    <div class="border rounded table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Modelo</th>
                                    <th>Categoría</th>
                                    <th>Tienda origen</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($devoluciones as $item) { ?>
                                    <tr>
                                        <td><?php echo $item->codigo; ?></td>
                                        <td><?php echo $item->modelo; ?></td>
                                        <td><?php if (isset($item->categoria)) { echo $item->categoria; } ?></td>
                                        <td>
                                            <?php if (isset($item->tienda_numero)) { echo $item->tienda_numero; } ?>
                                            <?php if (isset($item->tienda_nombre) && $item->tienda_nombre != '') { ?>
                                                — <?php echo $item->tienda_nombre; ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if (isset($item->motivo) && $item->motivo != '') { ?>
                                                <?php echo $item->motivo; ?>
                                            <?php } else { ?>
                                                <span class="text-muted">—</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($item->estado == 'pendiente') { ?>
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                                <div class="text-muted small">
                                                    <?php echo $item->fecha_solicitud; ?>
                                                    <?php if (isset($item->usuario_solicitud) && $item->usuario_solicitud != '') { ?>
                                                        · <?php echo $item->usuario_solicitud; ?>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php if ($item->estado == 'recibido') { ?>
                                                <span class="badge bg-success">Recibido</span>
                                                <?php if (isset($item->condicion_final) && $item->condicion_final != '') { ?>
                                                    <?php
                                                        $etqFinal = $item->condicion_final;
                                                        if (isset($condicionesDesenlace[$item->condicion_final])) {
                                                            $etqFinal = $condicionesDesenlace[$item->condicion_final];
                                                        }
                                                    ?>
                                                    <span class="badge bg-secondary"><?php echo $etqFinal; ?></span>
                                                <?php } ?>
                                                <div class="text-muted small">
                                                    <?php echo $item->fecha_recepcion; ?>
                                                    <?php if (isset($item->usuario_recepcion) && $item->usuario_recepcion != '') { ?>
                                                        · <?php echo $item->usuario_recepcion; ?>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                            <?php if ($item->estado == 'incidencia') { ?>
                                                <span class="badge bg-danger">Incidencia</span>
                                                <?php if (isset($item->observaciones) && $item->observaciones != '') { ?>
                                                    <div class="text-danger small"><?php echo $item->observaciones; ?></div>
                                                <?php } ?>
                                                <div class="text-muted small">
                                                    <?php echo $item->fecha_recepcion; ?>
                                                    <?php if (isset($item->usuario_recepcion) && $item->usuario_recepcion != '') { ?>
                                                        · <?php echo $item->usuario_recepcion; ?>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($item->estado == 'pendiente') { ?>
                                                <!-- Recibir: libera a stock y fija la condicion de desenlace -->
                                                <form method="post" action="devolucion.php?action=recibir" class="d-inline-flex align-items-center gap-1">
                                                    <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                                                    <select name="condicion_final" class="form-select form-select-sm" style="width:auto;">
                                                        <?php foreach ($condicionesDesenlace as $valor => $etiqueta) { ?>
                                                            <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-success btn-sm">Recibir</button>
                                                </form>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#inc<?php echo $item->id; ?>">
                                                    Incidencia
                                                </button>
                                                <div class="collapse mt-2 text-start" id="inc<?php echo $item->id; ?>">
                                                    <form method="post" action="devolucion.php?action=incidencia">
                                                        <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                                                        <textarea name="observaciones" class="form-control form-control-sm mb-2"
                                                                  rows="2" placeholder="Qué ha pasado (no llegó, se perdió...)"></textarea>
                                                        <button type="submit" class="btn btn-danger btn-sm">Registrar incidencia</button>
                                                    </form>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } else { ?>
                    <div class="alert alert-success">No hay devoluciones pendientes. Todo comprobado. 🎉</div>
                <?php } ?>

            </section>
        </div>
    </main>

    <script src="../view/js/bootstrap.bundle.min.js"></script>
</body>

</html>
