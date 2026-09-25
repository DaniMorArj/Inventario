<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Stock</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Stock'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row flex-grow-1 g-0 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="col-12 col-lg-10 p-4">

            <!-- MIGAS DE PAN -->
                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <a href="stock.php">Stock</a> &gt; <strong>Asignar</strong>
                    </small>
                </div>

                <h2 class="mb-3">Asignar Producto</h2>

                <?php if (!isset($errores)) {
                    $errores = array();
                } ?>
                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php
                // El formulario solo se pinta si el producto es asignable y llegó la lista de
                // tiendas. En los cortes previos (no encontrado, ya asignado, condición no
                // asignable) no se carga $tiendas, así que se muestra solo el aviso + Volver.
                $mostrarFormulario = false;
                if (isset($producto) && $producto != '' && isset($tiendas)) {
                    $mostrarFormulario = true;
                }
                ?>

                <?php if (!$mostrarFormulario) { ?>
                    <?php if (isset($producto->id)) { ?>
                        <a href="stock.php?action=ver&id=<?php echo $producto->id; ?>" class="btn btn-secondary">Volver al producto</a>
                    <?php } else { ?>
                        <a href="stock.php" class="btn btn-secondary">Volver a Stock</a>
                    <?php } ?>
                <?php } ?>

                <?php if ($mostrarFormulario) { ?>

                    <?php if (!isset($slotsPermitidos)) {
                        $slotsPermitidos = array();
                    } ?>

                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Producto</div>
                        <div class="p-3">
                            <div><b>Código:</b> <?php echo $producto->codigo; ?></div>
                            <div><b>Categoría:</b> <?php echo $producto->categoria_nombre; ?></div>
                            <div><b>Subtipo:</b> <?php echo $producto->subtipo; ?></div>
                            <div><b>Modelo:</b> <?php echo $producto->modelo; ?></div>
                        </div>
                    </div>

                    <form method="post" action="stock.php?action=asignar&id=<?php echo $producto->id; ?>">
                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Destino</div>
                            <div class="p-3">

                                <input type="hidden" name="destino_tipo" value="tienda">

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="destino_id">Tienda</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <select class="form-select" name="destino_id" id="destino_id" required>
                                            <option value="0">Selecciona tienda</option>
                                            <?php foreach ($tiendas as $t) { ?>
                                                <option value="<?php echo $t->id; ?>">
                                                    <?php echo $t->numero . " - " . $t->nombre; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="slot">Slot</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <select class="form-select" name="slot" id="slot" required>
                                            <option value="">Selecciona</option>
                                            <?php foreach ($slotsPermitidos as $k => $label) { ?>
                                                <option value="<?php echo $k; ?>">
                                                    <?php echo $label; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <div class="form-text">Slots disponibles según el tipo de producto.</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-primary" type="submit" name="guardarAsignacion">Asignar</button>
                                    <a class="btn btn-danger" href="stock.php">Cancelar</a>
                                </div>

                            </div>
                        </div>
                    </form>

                    <script>
                        const destinoId = document.getElementById('destino_id');
                        const slotSelect = document.getElementById('slot');

                        const slotsPermitidos = <?php echo json_encode($slotsPermitidos); ?>;

                        function reconstruirSlots() {
                            slotSelect.innerHTML = '<option value="">Selecciona</option>';

                            for (const k in slotsPermitidos) {
                                const opt = document.createElement('option');
                                opt.value = k;
                                opt.textContent = slotsPermitidos[k];
                                slotSelect.appendChild(opt);
                            }
                        }

                        async function cargarSlots() {
                            reconstruirSlots();
                            const id = parseInt(destinoId.value);
                            if (!id) return;
                            try {
                                const url = 'stock.php?action=slots_ocupados&destino_tipo=tienda&destino_id=' + id;
                                const resp = await fetch(url);
                                if (!resp.ok) { throw new Error('Error HTTP'); }
                                const data = await resp.json();
                                if (data.ok === true) {
                                    const ocupados = data.ocupados;
                                    for (let i = 0; i < slotSelect.options.length; i++) {
                                        const opt = slotSelect.options[i];
                                        if (ocupados[opt.value]) {
                                            opt.disabled = true;
                                            opt.textContent = opt.textContent + ' (ocupado)';
                                        }
                                    }
                                }
                            } catch (e) {
                                console.log('Error cargando slots');
                            }
                        }

                        destinoId.addEventListener('change', cargarSlots);
                        cargarSlots();
                    </script>

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