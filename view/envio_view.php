<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Envíos</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Envíos'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">

            <?php include_once 'sidebar.php'; ?>

            <section class="flex-grow-1 p-4" style="min-width:0;">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Envíos</strong>
                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h2 class="mb-0">Envíos a tiendas</h2>
                        <div class="text-muted small">Material que oficina envía a una tienda (reposición / sustitución). Al llegar se coloca en su hueco.</div>
                    </div>
                    <div>
                        <?php if (isset($verTodos) && $verTodos) { ?>
                            <a href="envio.php" class="btn btn-outline-secondary btn-sm">Ver solo en curso</a>
                        <?php } else { ?>
                            <a href="envio.php?ver=todos" class="btn btn-outline-secondary btn-sm">Ver todo el histórico</a>
                        <?php } ?>
                    </div>
                </div>

                <!-- Nuevo envío -->
                <div class="border rounded mb-4">
                    <div class="bg-primary bg-opacity-10 p-2 fw-semibold">Nuevo envío</div>
                    <div class="p-3">
                        <form method="post" action="envio.php?action=crear" class="row g-2 align-items-end">
                            <div class="col-12 col-md-4">
                                <label class="form-label m-0">Tienda destino</label>
                                <select name="id_tienda" class="form-select" required>
                                    <option value="">Selecciona tienda</option>
                                    <?php if (isset($tiendasActivas)) { ?>
                                        <?php foreach ($tiendasActivas as $t) { ?>
                                            <option value="<?php echo $t->id; ?>"><?php echo $t->numero; ?> — <?php echo $t->nombre; ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label m-0">Material a enviar</label>
                                <select name="id_producto" class="form-select" required>
                                    <option value="">Selecciona material disponible</option>
                                    <?php if (isset($productosDisponibles)) { ?>
                                        <?php foreach ($productosDisponibles as $p) { ?>
                                            <option value="<?php echo $p->id; ?>">
                                                <?php echo $p->codigo; ?> - <?php echo $p->modelo; ?> (<?php echo $p->categoria; ?>)
                                            </option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label m-0">Motivo (opcional)</label>
                                <input type="text" name="motivo" class="form-control" placeholder="Reposición, sustitución...">
                            </div>
                            <div class="col-12 col-md-1 d-grid">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                        <div class="form-text mt-2">El envío se crea en estado <strong>Preparando</strong>. El hueco de la tienda se elige al marcar <strong>Llegado</strong>.</div>
                    </div>
                </div>

                <?php include 'envio_lista_parcial.php'; ?>

            </section>
        </div>
    </main>

    <script src="../view/js/bootstrap.bundle.min.js"></script>
</body>

</html>
