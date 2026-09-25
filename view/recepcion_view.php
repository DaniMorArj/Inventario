<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Recepciones</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
    <style>
        .lote-cabecera .caret { transition: transform .15s; display: inline-block; }
        .lote-cabecera[aria-expanded="true"] .caret { transform: rotate(90deg); }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Recepciones'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Recepciones</strong>
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="mb-0">Material pendiente de recepción</h2>
                        <div class="text-muted small">Material de tiendas cerradas pendiente de recibir y comprobar en oficina.</div>
                    </div>
                    <div>
                        <?php if (isset($verTodos) && $verTodos) { ?>
                            <a href="recepcion.php" class="btn btn-outline-secondary btn-sm">Ver solo pendientes</a>
                        <?php } else { ?>
                            <a href="recepcion.php?ver=todos" class="btn btn-outline-secondary btn-sm">Ver todo el histórico</a>
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($lotes) && count($lotes) > 0) { ?>

                    <?php foreach ($lotes as $lote) { ?>
                        <div class="border rounded mb-4">

                            <!-- Cabecera del cierre (plegable: al pulsar se despliega el material) -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom bg-light lote-cabecera"
                                 data-bs-toggle="collapse" data-bs-target="#body_<?php echo $lote['lote']; ?>"
                                 aria-expanded="false" style="cursor:pointer;">
                                <div>
                                    <div class="fw-bold">
                                        <span class="caret me-1">&#9656;</span>
                                        Tienda
                                        <?php if (isset($lote['tienda_numero'])) { echo $lote['tienda_numero']; } ?>
                                        <?php if (isset($lote['tienda_nombre']) && $lote['tienda_nombre'] != '') { ?>
                                            — <?php echo $lote['tienda_nombre']; ?>
                                        <?php } ?>
                                    </div>
                                    <div class="text-muted small">
                                        Cerrada el <?php echo $lote['fecha_cierre']; ?>
                                        <?php if (isset($lote['usuario_cierre']) && $lote['usuario_cierre'] != '') { ?>
                                            por <?php echo $lote['usuario_cierre']; ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-warning text-dark"><?php echo $lote['pendientes']; ?> pendientes</span>
                                    <span class="badge bg-success"><?php echo $lote['recibidos']; ?> recibidos</span>
                                    <span class="badge bg-danger"><?php echo $lote['incidencias']; ?> incidencias</span>
                                    <?php if ($lote['pendientes'] > 0) { ?>
                                        <a href="recepcion.php?action=recibir_lote&lote=<?php echo $lote['lote']; ?>"
                                           class="btn btn-success btn-sm"
                                           onclick="event.stopPropagation(); return confirm('¿Marcar como recibido TODO el material pendiente de esta tienda?');">
                                            Marcar todo recibido
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Items del cierre (colapsable) -->
                            <div class="collapse table-responsive" id="body_<?php echo $lote['lote']; ?>">
                                <table class="table table-sm mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Modelo</th>
                                            <th>Categoría</th>
                                            <th>Slot origen</th>
                                            <th>Estado</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lote['items'] as $item) { ?>
                                            <tr>
                                                <td><?php echo $item->codigo; ?></td>
                                                <td><?php echo $item->modelo; ?></td>
                                                <td><?php if (isset($item->categoria)) { echo $item->categoria; } ?></td>
                                                <td><?php echo $item->slot_origen; ?></td>
                                                <td>
                                                    <?php if ($item->estado == 'pendiente') { ?>
                                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                                    <?php } ?>
                                                    <?php if ($item->estado == 'recibido') { ?>
                                                        <span class="badge bg-success">Recibido</span>
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
                                                        <a href="recepcion.php?action=recibir&id=<?php echo $item->id; ?>"
                                                           class="btn btn-success btn-sm">Recibir</a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#inc<?php echo $item->id; ?>">
                                                            Incidencia
                                                        </button>
                                                        <div class="collapse mt-2 text-start" id="inc<?php echo $item->id; ?>">
                                                            <form method="post" action="recepcion.php?action=incidencia">
                                                                <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                                                                <textarea name="observaciones" class="form-control form-control-sm mb-2"
                                                                          rows="2" placeholder="Qué ha pasado (no llegó, dañado...)"></textarea>
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

                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <div class="alert alert-success">No hay material pendiente de recepción. Todo comprobado. 🎉</div>
                <?php } ?>

            </section>
        </div>
    </main>

    <script src="../view/js/bootstrap.bundle.min.js"></script>
</body>

</html>
