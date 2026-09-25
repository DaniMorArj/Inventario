<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Configuración</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Configuración'; include_once 'menu.php'; ?>

    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row flex-grow-1 g-0 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="col-12 col-lg-10 p-4">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Configuración</strong>
                    </small>
                </div>

                <h2 class="mb-4">Configuración</h2>

                <!-- TABS -->
                <ul class="nav nav-tabs mb-4">
                    <?php if ($seccion == 'sociedades') { ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../controller/configuracion.php?seccion=sociedades">Sociedades</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../controller/configuracion.php?seccion=sociedades">Sociedades</a>
                        </li>
                    <?php } ?>
                    <?php if ($seccion == 'departamentos') { ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../controller/configuracion.php?seccion=departamentos">Departamentos</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../controller/configuracion.php?seccion=departamentos">Departamentos</a>
                        </li>
                    <?php } ?>
                    <?php if ($seccion == 'plantilla') { ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="../controller/configuracion.php?seccion=plantilla">Plantilla packs</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../controller/configuracion.php?seccion=plantilla">Plantilla packs</a>
                        </li>
                    <?php } ?>
                </ul>

                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ($ok != '') { ?>
                    <div class="alert alert-success">
                        <?php if ($ok == 'sociedad_creada') { echo 'Sociedad creada correctamente.'; } ?>
                        <?php if ($ok == 'sociedad_editada') { echo 'Sociedad actualizada correctamente.'; } ?>
                        <?php if ($ok == 'sociedad_eliminada') { echo 'Sociedad eliminada correctamente.'; } ?>
                        <?php if ($ok == 'departamento_creado') { echo 'Departamento creado correctamente.'; } ?>
                        <?php if ($ok == 'departamento_editado') { echo 'Departamento actualizado correctamente.'; } ?>
                        <?php if ($ok == 'departamento_eliminado') { echo 'Departamento eliminado correctamente.'; } ?>
                        <?php if ($ok == 'plantilla_creada') { echo 'Línea añadida a la plantilla.'; } ?>
                        <?php if ($ok == 'plantilla_editada') { echo 'Línea de plantilla actualizada.'; } ?>
                        <?php if ($ok == 'plantilla_eliminada') { echo 'Línea de plantilla eliminada.'; } ?>
                    </div>
                <?php } ?>

                <?php if ($errorGet != '') { ?>
                    <div class="alert alert-danger">
                        <?php if ($errorGet == 'sociedad_con_tiendas') { echo 'No se puede eliminar la sociedad porque tiene tiendas asociadas.'; } ?>
                        <?php if ($errorGet == 'departamento_con_trabajadores') { echo 'No se puede eliminar el departamento porque tiene trabajadores asociados.'; } ?>
                    </div>
                <?php } ?>

                <!-- ============================================================ -->
                <!-- SOCIEDADES -->
                <!-- ============================================================ -->
                <?php if ($seccion == 'sociedades') { ?>

                    <div class="row g-4">

                        <!-- LISTA -->
                        <div class="col-12 col-lg-7">
                            <div class="border rounded">
                                <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Sociedades</div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>CIF</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!isset($sociedades) || count($sociedades) == 0) { ?>
                                                <tr>
                                                    <td colspan="3" class="text-muted p-3">No hay sociedades.</td>
                                                </tr>
                                            <?php } else { ?>
                                                <?php foreach ($sociedades as $s) { ?>
                                                    <tr>
                                                        <td><?php echo $s->nombre; ?></td>
                                                        <td><?php echo $s->cif; ?></td>
                                                        <td class="text-end">
                                                            <a href="../controller/configuracion.php?action=editar_sociedad&id=<?php echo $s->id; ?>&seccion=sociedades"
                                                                class="btn btn-sm btn-primary">Editar</a>
                                                            <a href="../controller/configuracion.php?action=eliminar_sociedad&id=<?php echo $s->id; ?>"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('¿Eliminar esta sociedad?');">Eliminar</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- FORMULARIO ALTA / EDITAR -->
                        <div class="col-12 col-lg-5">
                            <div class="border rounded">
                                <?php if ($sociedadEditar) { ?>
                                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Editar Sociedad</div>
                                    <div class="p-3">
                                        <form method="post" action="../controller/configuracion.php?action=editar_sociedad&seccion=sociedades">
                                            <input type="hidden" name="id" value="<?php echo $sociedadEditar->id; ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input class="form-control" type="text" name="nombre"
                                                    value="<?php echo $sociedadEditar->nombre; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">CIF</label>
                                                <input class="form-control" type="text" name="cif"
                                                    value="<?php echo $sociedadEditar->cif; ?>" required>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary" type="submit">Guardar</button>
                                                <a class="btn btn-secondary" href="../controller/configuracion.php?seccion=sociedades">Cancelar</a>
                                            </div>
                                        </form>
                                    </div>
                                <?php } else { ?>
                                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Nueva Sociedad</div>
                                    <div class="p-3">
                                        <form method="post" action="../controller/configuracion.php?action=nueva_sociedad&seccion=sociedades">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input class="form-control" type="text" name="nombre" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">CIF</label>
                                                <input class="form-control" type="text" name="cif" required>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Añadir</button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                <?php } ?>

                <!-- ============================================================ -->
                <!-- DEPARTAMENTOS -->
                <!-- ============================================================ -->
                <?php if ($seccion == 'departamentos') { ?>

                    <div class="row g-4">

                        <!-- LISTA -->
                        <div class="col-12 col-lg-7">
                            <div class="border rounded">
                                <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Departamentos</div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nombre</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!isset($departamentos) || $departamentos == '') { ?>
                                                <tr>
                                                    <td colspan="2" class="text-muted p-3">No hay departamentos.</td>
                                                </tr>
                                            <?php } else { ?>
                                                <?php foreach ($departamentos as $d) { ?>
                                                    <tr>
                                                        <td><?php echo $d->nombre; ?></td>
                                                        <td class="text-end">
                                                            <a href="../controller/configuracion.php?action=editar_departamento&id=<?php echo $d->id; ?>&seccion=departamentos"
                                                                class="btn btn-sm btn-primary">Editar</a>
                                                            <a href="../controller/configuracion.php?action=eliminar_departamento&id=<?php echo $d->id; ?>"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('¿Eliminar este departamento?');">Eliminar</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- FORMULARIO ALTA / EDITAR -->
                        <div class="col-12 col-lg-5">
                            <div class="border rounded">
                                <?php if ($departamentoEditar) { ?>
                                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Editar Departamento</div>
                                    <div class="p-3">
                                        <form method="post" action="../controller/configuracion.php?action=editar_departamento&seccion=departamentos">
                                            <input type="hidden" name="id" value="<?php echo $departamentoEditar->id; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input class="form-control" type="text" name="nombre"
                                                    value="<?php echo $departamentoEditar->nombre; ?>" required>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary" type="submit">Guardar</button>
                                                <a class="btn btn-secondary" href="../controller/configuracion.php?seccion=departamentos">Cancelar</a>
                                            </div>
                                        </form>
                                    </div>
                                <?php } else { ?>
                                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Nuevo Departamento</div>
                                    <div class="p-3">
                                        <form method="post" action="../controller/configuracion.php?action=nuevo_departamento&seccion=departamentos">
                                            <div class="mb-3">
                                                <label class="form-label">Nombre</label>
                                                <input class="form-control" type="text" name="nombre" required>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Añadir</button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

                <?php } ?>

                <!-- ============================================================ -->
                <!-- PLANTILLA DE PACKS -->
                <!-- ============================================================ -->
                <?php if ($seccion == 'plantilla') { ?>

                    <div class="text-muted mb-3">Material estándar que debe llevar un pack de apertura. El checklist del pack valida contra esta lista.</div>

                    <div class="row g-4">

                        <!-- LISTA -->
                        <div class="col-12 col-lg-7">
                            <div class="border rounded">
                                <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Plantilla del pack</div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Categoría</th>
                                                <th>Subtipo</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-end">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!isset($plantillaPack) || count($plantillaPack) == 0) { ?>
                                                <tr>
                                                    <td colspan="4" class="text-muted p-3">La plantilla está vacía.</td>
                                                </tr>
                                            <?php } else { ?>
                                                <?php foreach ($plantillaPack as $pl) { ?>
                                                    <tr>
                                                        <td><?php echo $pl->categoria; ?></td>
                                                        <td>
                                                            <?php if (isset($pl->subtipo) && $pl->subtipo != '') { ?>
                                                                <?php echo $pl->subtipo; ?>
                                                            <?php } else { ?>
                                                                <span class="text-muted">cualquiera</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center"><?php echo $pl->cantidad; ?></td>
                                                        <td class="text-end">
                                                            <a href="../controller/configuracion.php?action=editar_plantilla&id=<?php echo $pl->id; ?>&seccion=plantilla"
                                                                class="btn btn-sm btn-primary">Editar</a>
                                                            <a href="../controller/configuracion.php?action=eliminar_plantilla&id=<?php echo $pl->id; ?>"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('¿Eliminar esta línea de la plantilla?');">Eliminar</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- FORMULARIO ALTA / EDITAR -->
                        <div class="col-12 col-lg-5">
                            <div class="border rounded">
                                <?php if ($plantillaEditar) { ?>
                                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Editar línea</div>
                                    <div class="p-3">
                                        <form method="post" action="../controller/configuracion.php?action=editar_plantilla&seccion=plantilla">
                                            <input type="hidden" name="id" value="<?php echo $plantillaEditar->id; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Categoría</label>
                                                <select class="form-select" name="id_categoria" required>
                                                    <?php foreach ($categorias as $c) { ?>
                                                        <?php if ($c->id == $plantillaEditar->id_categoria) { ?>
                                                            <option value="<?php echo $c->id; ?>" selected><?php echo $c->nombre; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Subtipo <span class="text-muted small">(opcional)</span></label>
                                                <input class="form-control" type="text" name="subtipo" value="<?php if (isset($plantillaEditar->subtipo)) { echo $plantillaEditar->subtipo; } ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Cantidad</label>
                                                <input class="form-control" type="number" name="cantidad" min="1" value="<?php echo $plantillaEditar->cantidad; ?>" required>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-primary" type="submit">Guardar</button>
                                                <a class="btn btn-secondary" href="../controller/configuracion.php?seccion=plantilla">Cancelar</a>
                                            </div>
                                        </form>
                                    </div>
                                <?php } else { ?>
                                    <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Nueva línea</div>
                                    <div class="p-3">
                                        <form method="post" action="../controller/configuracion.php?action=nueva_plantilla&seccion=plantilla">
                                            <div class="mb-3">
                                                <label class="form-label">Categoría</label>
                                                <select class="form-select" name="id_categoria" required>
                                                    <option value="">Selecciona...</option>
                                                    <?php foreach ($categorias as $c) { ?>
                                                        <option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Subtipo <span class="text-muted small">(opcional)</span></label>
                                                <input class="form-control" type="text" name="subtipo" placeholder="Dejar vacío = cualquiera">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Cantidad</label>
                                                <input class="form-control" type="number" name="cantidad" min="1" value="1" required>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Añadir</button>
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

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