<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Packs de apertura</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Packs de apertura'; include_once 'menu.php'; ?>

    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt;
                        <a href="pack.php">Packs de apertura</a>
                        <?php if (isset($modo) && $modo == 'detalle') { ?>
                            &gt; <strong>Detalle</strong>
                        <?php } ?>
                    </small>
                </div>

                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (isset($modo) && $modo == 'detalle') { ?>

                    <?php
                    $editable = false;
                    if (isset($pack->estado) && ($pack->estado == 'preparacion' || $pack->estado == 'completo')) {
                        $editable = true;
                    }
                    ?>

                    <!-- Cabecera del pack -->
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                        <div>
                            <h2 class="mb-1"><?php echo $pack->codigo; ?></h2>
                            <div class="text-muted">
                                Tienda
                                <?php if (isset($pack->tienda_numero)) { echo $pack->tienda_numero; } ?>
                                <?php if (isset($pack->tienda_nombre) && $pack->tienda_nombre != '') { ?>
                                    — <?php echo $pack->tienda_nombre; ?>
                                <?php } ?>
                            </div>
                            <div class="text-muted small">
                                Creado el <?php echo $pack->fecha_creacion; ?>
                                <?php if (isset($pack->usuario_creacion) && $pack->usuario_creacion != '') { ?>
                                    por <?php echo $pack->usuario_creacion; ?>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <?php if ($pack->estado == 'preparacion') { ?><span class="badge bg-warning text-dark">En preparación</span><?php } ?>
                            <?php if ($pack->estado == 'completo') { ?><span class="badge bg-info">Completo</span><?php } ?>
                            <?php if ($pack->estado == 'enviado') { ?><span class="badge bg-primary">Enviado</span><?php } ?>
                            <?php if ($pack->estado == 'entregado') { ?><span class="badge bg-success">Entregado</span><?php } ?>
                            <?php if ($pack->estado == 'cancelado') { ?><span class="badge bg-secondary">Cancelado</span><?php } ?>
                        </div>
                    </div>

                    <?php if ($checklist['completo']) { ?>
                        <div class="alert alert-success py-2">Pack completo: tiene todo el material de la plantilla. ✅</div>
                    <?php } else { ?>
                        <div class="alert alert-warning py-2">Faltan ítems por añadir según la plantilla.</div>
                    <?php } ?>

                    <!-- Checklist -->
                    <h5 class="mt-3">Checklist de material</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th class="text-center">Requerido</th>
                                    <th class="text-center">En el pack</th>
                                    <th>Estado</th>
                                    <?php if ($editable) { ?><th>Añadir</th><?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($checklist['lineas'] as $indice => $ln) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $ln['categoria']; ?>
                                            <?php if (isset($ln['subtipo']) && $ln['subtipo'] != '') { ?>
                                                <span class="text-muted small">(<?php echo $ln['subtipo']; ?>)</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center"><?php echo $ln['cantidad']; ?></td>
                                        <td class="text-center"><?php echo $ln['tiene']; ?></td>
                                        <td>
                                            <?php if ($ln['falta'] == 0) { ?>
                                                <span class="badge bg-success">Completo</span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger">Faltan <?php echo $ln['falta']; ?></span>
                                            <?php } ?>
                                        </td>
                                        <?php if ($editable) { ?>
                                            <td>
                                                <?php if ($ln['falta'] > 0) { ?>
                                                    <?php if (isset($disponiblesPorLinea[$indice]) && count($disponiblesPorLinea[$indice]) > 0) { ?>
                                                        <form method="get" action="pack.php" class="d-flex gap-1">
                                                            <input type="hidden" name="action" value="anadir">
                                                            <input type="hidden" name="pack" value="<?php echo $pack->id; ?>">
                                                            <select name="producto" class="form-select form-select-sm" style="max-width:220px;">
                                                                <?php foreach ($disponiblesPorLinea[$indice] as $prod) { ?>
                                                                    <option value="<?php echo $prod->id; ?>"><?php echo $prod->codigo; ?> · <?php echo $prod->modelo; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-primary">Añadir</button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <span class="text-danger small">Sin stock disponible</span>
                                                    <?php } ?>
                                                <?php } ?>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Material dentro del pack -->
                    <h5>Material en el pack (<?php echo count($lineas); ?>)</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Modelo</th>
                                    <th>Categoría</th>
                                    <?php if ($editable) { ?><th class="text-end">Acción</th><?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($lineas) > 0) { ?>
                                    <?php foreach ($lineas as $l) { ?>
                                        <tr>
                                            <td><?php echo $l->codigo; ?></td>
                                            <td><?php echo $l->modelo; ?></td>
                                            <td><?php if (isset($l->categoria)) { echo $l->categoria; } ?></td>
                                            <?php if ($editable) { ?>
                                                <td class="text-end">
                                                    <a href="pack.php?action=quitar&pack=<?php echo $pack->id; ?>&producto=<?php echo $l->id_producto; ?>"
                                                       class="btn btn-sm btn-outline-danger">Quitar</a>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr><td colspan="4" class="text-muted">El pack está vacío.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Acciones -->
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="albaran.php?id=<?php echo $pack->id; ?>" target="_blank" class="btn btn-outline-dark">Albarán PDF</a>
                        <a href="etiqueta.php?codigo=<?php echo $pack->codigo; ?>" target="_blank" class="btn btn-outline-dark">Etiqueta OR</a>
                        <?php if ($editable && $checklist['completo'] && $pack->estado != 'enviado') { ?>
                            <a href="pack.php?action=enviar&id=<?php echo $pack->id; ?>" class="btn btn-primary">Marcar enviado</a>
                        <?php } ?>
                        <?php if ($checklist['completo'] && $pack->estado != 'entregado' && $pack->estado != 'cancelado') { ?>
                            <a href="pack.php?action=abrir&id=<?php echo $pack->id; ?>" class="btn btn-success"
                               onclick="return confirm('¿Abrir la tienda? El material del pack pasará a ser el inventario de la tienda y la tienda quedará activa.');">
                                Abrir tienda (materializar)
                            </a>
                        <?php } ?>
                        <?php if ($pack->estado != 'entregado' && $pack->estado != 'cancelado') { ?>
                            <a href="pack.php?action=cancelar&id=<?php echo $pack->id; ?>" class="btn btn-outline-danger"
                               onclick="return confirm('¿Cancelar el pack? Se liberará todo el material de vuelta a stock.');">
                                Cancelar pack
                            </a>
                        <?php } ?>
                        <?php if ($pack->estado == 'entregado') { ?>
                            <span class="align-self-center text-success">Pack entregado. La tienda está activa con este material.</span>
                        <?php } ?>
                    </div>

                <?php } else { ?>

                    <!-- LISTADO -->
                    <h2 class="mb-1">Packs de apertura</h2>
                    <div class="text-muted mb-4">Material informático preparado para tiendas que van a abrir.</div>

                    <!-- Nuevo pack -->
                    <div class="border rounded p-3 mb-4 bg-light">
                        <div class="fw-bold mb-2">Preparar nuevo pack</div>
                        <form method="post" action="pack.php?action=crear" class="row g-2 align-items-end">
                            <div class="col-12 col-md-2">
                                <label class="form-label small mb-1">Nº tienda</label>
                                <input type="text" name="numero" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small mb-1">Nombre tienda</label>
                                <input type="text" name="nombre" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small mb-1">Sociedad</label>
                                <select name="sociedad" class="form-select form-select-sm" required>
                                    <option value="">Selecciona...</option>
                                    <?php if (isset($sociedades)) { ?>
                                        <?php foreach ($sociedades as $s) { ?>
                                            <option value="<?php echo $s->id; ?>"><?php echo $s->nombre; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Crear pack</button>
                            </div>
                        </form>
                        <div class="text-muted small mt-2">Se crea la tienda en estado "en apertura" y su pack con código PACK automático.</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Tienda</th>
                                    <th>Estado</th>
                                    <th class="text-center">Items</th>
                                    <th>Creado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($packs) && count($packs) > 0) { ?>
                                    <?php foreach ($packs as $p) { ?>
                                        <tr>
                                            <td><strong><?php echo $p->codigo; ?></strong></td>
                                            <td>
                                                <?php if (isset($p->tienda_numero)) { echo $p->tienda_numero; } ?>
                                                <?php if (isset($p->tienda_nombre) && $p->tienda_nombre != '') { ?>
                                                    — <?php echo $p->tienda_nombre; ?>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($p->estado == 'preparacion') { ?><span class="badge bg-warning text-dark">En preparación</span><?php } ?>
                                                <?php if ($p->estado == 'completo') { ?><span class="badge bg-info">Completo</span><?php } ?>
                                                <?php if ($p->estado == 'enviado') { ?><span class="badge bg-primary">Enviado</span><?php } ?>
                                                <?php if ($p->estado == 'entregado') { ?><span class="badge bg-success">Entregado</span><?php } ?>
                                                <?php if ($p->estado == 'cancelado') { ?><span class="badge bg-secondary">Cancelado</span><?php } ?>
                                            </td>
                                            <td class="text-center"><?php echo $p->items; ?></td>
                                            <td class="small text-muted"><?php echo $p->fecha_creacion; ?></td>
                                            <td class="text-end">
                                                <a href="pack.php?action=ver&id=<?php echo $p->id; ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr><td colspan="6" class="text-muted">No hay packs todavía.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>

            </section>
        </div>
    </main>

    <script src="../view/js/bootstrap.bundle.min.js"></script>
</body>

</html>
