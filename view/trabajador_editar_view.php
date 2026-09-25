<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Editar trabajador</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header class="bg-black py-1">
        <div class="container-fluid d-flex justify-content-between align-items-center px-3 px-md-4">

            <button class="btn btn-dark d-lg-none" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#menuMovil"
                aria-controls="menuMovil" aria-label="Abrir menú">☰</button>

            <a href="index.php" class="text-decoration-none d-none d-lg-block">
                <img src="../view/img/logo.png" alt="InventarioApp" class="img-fluid" style="max-height:60px;">
            </a>

            <div class="text-white fw-semibold" style="font-size:40px;">
                <p>Editar Trabajador</p>
            </div>

            <div class="text-white d-flex align-items-center gap-2">
                <div class="d-none d-lg-flex align-items-center gap-2">
                    <img src="../view/img/iconos/usuario.svg" alt="" style="width:40px;height:40px;">
                    <div class="text-end">
                        <div class="small">Usuario:</div>
                        <div><?php echo $_SESSION['usuario']; ?></div>
                    </div>
                </div>

                <button class="btn btn-dark d-lg-none" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#usuarioMovil"
                    aria-controls="usuarioMovil" aria-label="Ver usuario">
                    <img src="../view/img/iconos/usuario.svg" alt="" style="width:28px;height:28px;">
                </button>
            </div>

        </div>
    </header>

    <!-- MENÚ MÓVIL -->
    <div class="offcanvas offcanvas-start bg-black text-white" tabindex="-1" id="menuMovil">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body p-0">
            <div class="p-3">

                <?php if ($menuDashboard) { ?>
                    <a href="index.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'dashboard') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/dashboard.svg" alt="" style="width:20px;height:20px;">
                        <span>Dashboard</span>
                    </a>
                <?php } ?>

                <?php if ($menuTienda) { ?>
                    <a href="tienda.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'tienda') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/tienda.svg" alt="" style="width:20px;height:20px;">
                        <span>Tiendas</span>
                    </a>
                <?php } ?>

                <?php if ($menuOficina) { ?>
                    <a href="oficina.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'oficina') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/oficina.svg" alt="" style="width:20px;height:20px;">
                        <span>Oficina</span>
                    </a>
                    <?php if ($active == 'oficina') { ?>
                        <div class="ms-4 mt-1">
                            <a href="trabajador.php?action=alta&tipo=oficina" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">+ Alta Trabajador</a>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if ($menuAlmacen) { ?>
                    <a href="almacen.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'almacen') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/almacen.svg" alt="" style="width:20px;height:20px;">
                        <span>Almacén</span>
                    </a>
                    <?php if ($active == 'almacen') { ?>
                        <div class="ms-4 mt-1">
                            <a href="trabajador.php?action=alta&tipo=almacen" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">+ Alta Trabajador</a>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if ($menuSerigrafia) { ?>
                    <a href="serigrafia.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'serigrafia') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/serigrafia.svg" alt="" style="width:20px;height:20px;">
                        <span>Serigrafía</span>
                    </a>
                    <?php if ($active == 'serigrafia') { ?>
                        <div class="ms-4 mt-1">
                            <a href="trabajador.php?action=alta&tipo=serigrafia" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">+ Alta Trabajador</a>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if ($menuStock) { ?>
                    <a href="stock.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'stock') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/stock.svg" alt="" style="width:20px;height:20px;">
                        <span>Stock</span>
                    </a>
                <?php } ?>

                <?php if ($menuUsuarios) { ?>
                    <a href="usuarios.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'usuarios') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/usuarios.svg" alt="" style="width:20px;height:20px;">
                        <span>Usuarios</span>
                    </a>
                <?php } ?>

                <?php if ($menuSoporte) { ?>
                    <a href="soporte.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'soporte') ? 'active-menu' : ''; ?>">
                        <img src="../view/img/iconos/soporte.svg" alt="" style="width:20px;height:20px;">
                        <span>Soporte</span>
                    </a>
                <?php } ?>

            </div>

            <hr class="m-0 text-secondary">
            <div class="p-3">
                <a href="logout.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                    <img src="../view/img/iconos/logout.svg" alt="" style="width:20px;height:20px;">
                    <span>Cerrar sesión</span>
                </a>
            </div>
        </div>
    </div>

    <!-- USUARIO MÓVIL -->
    <div class="offcanvas offcanvas-end bg-black text-white" tabindex="-1" id="usuarioMovil">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Usuario:</h5>
            <button type="button" class="btn btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <p><?php echo $_SESSION['usuario']; ?></p>
        </div>
    </div>

    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row flex-grow-1 g-0 w-100">

            <!-- SIDEBAR -->
            <nav class="d-none d-lg-flex flex-column col-lg-2 bg-black text-white p-0">
                <div class="p-3">

                    <?php if ($menuDashboard) { ?>
                        <a href="index.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'dashboard') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/dashboard.svg" alt="" style="width:20px;height:20px;">
                            <span>Dashboard</span>
                        </a>
                    <?php } ?>

                    <?php if ($menuTienda) { ?>
                        <a href="tienda.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'tienda') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/tienda.svg" alt="" style="width:20px;height:20px;">
                            <span>Tiendas</span>
                        </a>
                    <?php } ?>

                    <?php if ($menuOficina) { ?>
                        <a href="oficina.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'oficina') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/oficina.svg" alt="" style="width:20px;height:20px;">
                            <span>Oficina</span>
                        </a>
                        <?php if ($active == 'oficina') { ?>
                            <div class="ms-4 mt-1">
                                <a href="trabajador.php?action=alta&tipo=oficina" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">+ Alta Trabajador</a>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($menuAlmacen) { ?>
                        <a href="almacen.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'almacen') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/almacen.svg" alt="" style="width:20px;height:20px;">
                            <span>Almacén</span>
                        </a>
                        <?php if ($active == 'almacen') { ?>
                            <div class="ms-4 mt-1">
                                <a href="trabajador.php?action=alta&tipo=almacen" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">+ Alta Trabajador</a>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($menuSerigrafia) { ?>
                        <a href="serigrafia.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'serigrafia') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/serigrafia.svg" alt="" style="width:20px;height:20px;">
                            <span>Serigrafía</span>
                        </a>
                        <?php if ($active == 'serigrafia') { ?>
                            <div class="ms-4 mt-1">
                                <a href="trabajador.php?action=alta&tipo=serigrafia" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">+ Alta Trabajador</a>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($menuStock) { ?>
                        <a href="stock.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'stock') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/stock.svg" alt="" style="width:20px;height:20px;">
                            <span>Stock</span>
                        </a>
                    <?php } ?>

                    <?php if ($menuUsuarios) { ?>
                        <a href="usuarios.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'usuarios') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/usuarios.svg" alt="" style="width:20px;height:20px;">
                            <span>Usuarios</span>
                        </a>
                    <?php } ?>

                    <?php if ($menuSoporte) { ?>
                        <a href="soporte.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php echo ($active == 'soporte') ? 'active-menu' : ''; ?>">
                            <img src="../view/img/iconos/soporte.svg" alt="" style="width:20px;height:20px;">
                            <span>Soporte</span>
                        </a>
                    <?php } ?>

                </div>

                <hr class="m-0 text-secondary">

                <div class="p-3">
                    <a href="logout.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                        <img src="../view/img/iconos/logout.svg" alt="" style="width:20px;height:20px;">
                        <span>Cerrar sesión</span>
                    </a>
                </div>
            </nav>

            <!-- CONTENIDO -->
            <section class="col-12 col-lg-10 p-4">

                <!-- MIGAS DE PAN -->
                <div class="mb-2">
                    <small class="text-muted"><a href="../controller/index.php">Inicio</a> &gt; <a href="../controller/<?php echo $tipo; ?>.php"><?php echo $tipo; ?></a> &gt; <strong>Editar trabajador</strong></small>
                </div>

                <h2 class="mb-3">Editar: <?php echo $trabajador->nombre; ?></h2>

                <?php if (!isset($errores)) $errores = []; ?>
                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <form method="post" action="trabajador.php?action=editar&id=<?php echo (int)$trabajador->id; ?>">
                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos del trabajador</div>
                        <div class="p-3">

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="id_centro">Centro</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="id_centro" id="id_centro" required>
                                        <option value="0">Selecciona centro</option>
                                        <?php foreach ($centros as $c) { ?>
                                            <option value="<?php echo (int)$c->id; ?>" <?php echo ((int)$trabajador->id_centro === (int)$c->id) ? 'selected' : ''; ?>>
                                                <?php echo $c->nombre; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="nombre">Nombre</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="nombre" id="nombre"
                                        value="<?php echo $_POST['nombre'] ?? $trabajador->nombre; ?>" required>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="email">Email</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="email" id="email"
                                        value="<?php echo $_POST['email'] ?? ($trabajador->email ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="id_departamento">Departamento</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="id_departamento" id="id_departamento">
                                        <option value="0">Selecciona</option>
                                        <?php foreach ($departamentos as $d) { ?>
                                            <option value="<?php echo (int)$d->id; ?>" <?php echo (($trabajador->id_departamento ?? 0) === (int)$d->id) ? 'selected' : ''; ?>>
                                                <?php echo $d->nombre; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="cargo">Cargo</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="cargo" id="cargo"
                                        value="<?php echo $_POST['cargo'] ?? ($trabajador->cargo ?? ''); ?>">
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="id_producto_numero_movil">Número Móvil (SIM)</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="id_producto_numero_movil" id="id_producto_numero_movil">
                                        <option value="0">Sin SIM</option>
                                        <?php foreach ($numerosMovilDisponibles as $p) { ?>
                                            <?php
                                            $labelSim = $p->codigo;
                                            if (isset($p->numero) && $p->numero != '') {
                                                $labelSim = $p->numero;
                                            }
                                            if (isset($p->tarifa) && $p->tarifa != '') {
                                                $labelSim .= ' - ' . $p->tarifa;
                                            }
                                            $idSimActual = 0;
                                            if (isset($trabajador->id_producto_numero_movil)) {
                                                $idSimActual = $trabajador->id_producto_numero_movil;
                                            }
                                            ?>
                                            <?php if ($idSimActual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected><?php echo $labelSim; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>"><?php echo $labelSim; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <div class="form-text">Incluye disponibles + la SIM actual del trabajador.</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Observaciones</div>
                        <div class="p-3">
                            <textarea class="form-control" name="observaciones" rows="4"><?php echo $_POST['observaciones'] ?? ($trabajador->observaciones ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-primary" type="submit" name="guardarEdicion">Guardar</button>
                        <a class="btn btn-danger" href="trabajador_detalle.php?id=<?php echo (int)$trabajador->id; ?>">Cancelar</a>
                    </div>
                </form>

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