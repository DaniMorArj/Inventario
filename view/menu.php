<?php if (!isset($active)) { $active = 'dashboard'; } ?>
<?php if (!isset($activeSubmenu)) { $activeSubmenu = ''; } ?>
<?php if (!isset($tituloPagina)) { $tituloPagina = 'InventarioApp'; } ?>

<!-- HEADER -->
<header class="bg-black py-1">
    <div class="container-fluid d-flex justify-content-between align-items-center px-3 px-md-4">

        <button class="btn btn-dark d-lg-none" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#menuMovil"
            aria-controls="menuMovil" aria-label="Abrir menú">
            ☰
        </button>

        <a href="index.php" class="text-decoration-none d-none d-lg-block">
            <img src="../view/img/logo.png" alt="InventarioApp" class="img-fluid" style="max-height:60px;">
        </a>

        <div class="text-white fw-semibold" style="font-size:40px;">
            <p><?php echo $tituloPagina; ?></p>
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
<div class="offcanvas offcanvas-start bg-black text-white" tabindex="-1" id="menuMovil" aria-labelledby="menuMovilLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="menuMovilLabel">Menú</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body p-0">
        <div class="p-3">

            <?php if (isset($menuDashboard) && $menuDashboard) { ?>
                <?php if ($active == 'dashboard') { ?>
                    <a href="index.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="index.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/dashboard.svg" alt="" style="width:20px;height:20px;">
                    <span>Dashboard</span>
                </a>
            <?php } ?>

            <?php if (isset($menuTienda) && $menuTienda) { ?>
                <?php if ($active == 'tienda') { ?>
                    <a href="tienda.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="tienda.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/tienda.svg" alt="" style="width:20px;height:20px;">
                    <span>Tiendas</span>
                </a>

                <?php if (isset($submenuTienda) && $submenuTienda && $_SESSION['rol'] == 'admin') { ?>
                    <div class="ms-4 mt-1">
                        <?php if ($activeSubmenu == 'alta') { ?>
                            <a href="tienda.php?action=alta" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded active-menu">
                        <?php } else { ?>
                            <a href="tienda.php?action=alta" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded">
                        <?php } ?>
                            + Alta Tienda
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (isset($menuOficina) && $menuOficina) { ?>
                <?php if ($active == 'oficina') { ?>
                    <a href="oficina.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="oficina.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/oficina.svg" alt="" style="width:20px;height:20px;">
                    <span>Oficina</span>
                </a>

                <?php if (isset($submenuOficina) && $submenuOficina) { ?>
                    <div class="ms-4 mt-1">
                        <?php if (isset($activeSubmenuOficina) && $activeSubmenuOficina == 'alta_trabajador') { ?>
                            <a href="trabajador.php?action=alta&tipo=oficina" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">
                        <?php } else { ?>
                            <a href="trabajador.php?action=alta&tipo=oficina" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded">
                        <?php } ?>
                            + Alta Trabajador
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (isset($menuAlmacen) && $menuAlmacen) { ?>
                <?php if ($active == 'almacen') { ?>
                    <a href="almacen.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="almacen.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/almacen.svg" alt="" style="width:20px;height:20px;">
                    <span>Almacén</span>
                </a>

                <?php if (isset($submenuAlmacen) && $submenuAlmacen) { ?>
                    <div class="ms-4 mt-1">
                        <?php if (isset($activeSubmenuAlmacen) && $activeSubmenuAlmacen == 'alta_trabajador') { ?>
                            <a href="trabajador.php?action=alta&tipo=almacen" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">
                        <?php } else { ?>
                            <a href="trabajador.php?action=alta&tipo=almacen" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded">
                        <?php } ?>
                            + Alta Trabajador
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (isset($menuSerigrafia) && $menuSerigrafia) { ?>
                <?php if ($active == 'serigrafia') { ?>
                    <a href="serigrafia.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="serigrafia.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/serigrafia.svg" alt="" style="width:20px;height:20px;">
                    <span>Serigrafía</span>
                </a>

                <?php if (isset($submenuSerigrafia) && $submenuSerigrafia) { ?>
                    <div class="ms-4 mt-1">
                        <?php if (isset($activeSubmenuSerigrafia) && $activeSubmenuSerigrafia == 'alta_trabajador') { ?>
                            <a href="trabajador.php?action=alta&tipo=serigrafia" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu">
                        <?php } else { ?>
                            <a href="trabajador.php?action=alta&tipo=serigrafia" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded">
                        <?php } ?>
                            + Alta Trabajador
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (isset($menuStock) && $menuStock) { ?>
                <?php if ($active == 'stock' && $activeSubmenu == '') { ?>
                    <a href="stock.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="stock.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/stock.svg" alt="" style="width:20px;height:20px;">
                    <span>Stock</span>
                </a>

                <?php if (isset($submenuStock) && $submenuStock) { ?>
                    <div class="ms-4 mt-1">
                        <?php if ($activeSubmenu == 'ordenador') { ?><a href="stock.php?action=alta&cat=ordenador" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=ordenador" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Ordenador</a>
                        <?php if ($activeSubmenu == 'monitor') { ?><a href="stock.php?action=alta&cat=monitor" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=monitor" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Monitor</a>
                        <?php if ($activeSubmenu == 'impresora') { ?><a href="stock.php?action=alta&cat=impresora" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=impresora" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Impresora</a>
                        <?php if ($activeSubmenu == 'periferico') { ?><a href="stock.php?action=alta&cat=periferico" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=periferico" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Periferico</a>
                        <?php if ($activeSubmenu == 'lector') { ?><a href="stock.php?action=alta&cat=lector" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=lector" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Lector</a>
                        <?php if ($activeSubmenu == 'telefono_fijo') { ?><a href="stock.php?action=alta&cat=telefono_fijo" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=telefono_fijo" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Telefono Fijo</a>
                    <?php if ($activeSubmenu == 'telefono_movil') { ?><a href="stock.php?action=alta&cat=telefono_movil" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=telefono_movil" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Telefono Movil</a>
                        <?php if ($activeSubmenu == 'movil') { ?><a href="stock.php?action=alta&cat=movil" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=movil" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Numero Movil</a>
                        <?php if ($activeSubmenu == 'router') { ?><a href="stock.php?action=alta&cat=router" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=router" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Router</a>
                        <?php if ($activeSubmenu == 'camara') { ?><a href="stock.php?action=alta&cat=camara" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=camara" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Camara</a>
                        <?php if ($activeSubmenu == 'licencia') { ?><a href="stock.php?action=alta&cat=licencia" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=licencia" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ Licencia</a>
                        <?php if ($activeSubmenu == 'tpv') { ?><a href="stock.php?action=alta&cat=tpv" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded active-menu"><?php } else { ?><a href="stock.php?action=alta&cat=tpv" class="menu-link d-flex align-items-center text-decoration-none text-white py-1 px-2 rounded"><?php } ?>+ TPV</a>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (isset($menuUsuarios) && $menuUsuarios) { ?>
                <?php if ($active == 'usuarios') { ?>
                    <a href="usuarios.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="usuarios.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/usuarios.svg" alt="" style="width:20px;height:20px;">
                    <span>Usuarios</span>
                </a>

                <?php if (isset($submenuUsuarios) && $submenuUsuarios) { ?>
                    <div class="ms-4 mt-1">
                        <?php if (isset($activeSubmenu) && $activeSubmenu == 'alta') { ?>
                            <a href="usuarios.php?action=alta" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded active-menu">
                        <?php } else { ?>
                            <a href="usuarios.php?action=alta" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded">
                        <?php } ?>
                            <span>+ Alta Usuario</span>
                        </a>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if (isset($menuSoporte) && $menuSoporte) { ?>
                <?php if ($active == 'soporte') { ?>
                    <a href="https://ejemplo.com/soporte" target="_blank" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
                <?php } else { ?>
                    <a href="https://ejemplo.com/soporte" target="_blank" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
                <?php } ?>
                    <img src="../view/img/iconos/soporte.svg" alt="" style="width:20px;height:20px;">
                    <span>Soporte</span>
                </a>
            <?php } ?>

        </div>

            <?php if (isset($menuConfiguracion) && $menuConfiguracion) { ?>
            <?php if ($active == 'configuracion') { ?>
                <a href="../controller/configuracion.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
            <?php } else { ?>
                <a href="../controller/configuracion.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
            <?php } ?>
                <img src="../view/img/iconos/configuracion.svg" alt="" style="width:20px;height:20px;">
                <span>Configuración</span>
            </a>
        <?php } ?>

            <?php if ($active == 'buscar') { ?>
                <a href="buscar.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
            <?php } else { ?>
                <a href="buscar.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
            <?php } ?>
                <img src="../view/img/iconos/lupa.svg" alt="" style="width:20px;height:20px;">
                <span>Buscador</span>
            </a>

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
<div class="offcanvas offcanvas-end bg-black text-white" tabindex="-1" id="usuarioMovil" aria-labelledby="usuarioMovilLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="usuarioMovilLabel">Usuario:</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>
    <div class="offcanvas-body">
        <p><?php echo $_SESSION['usuario']; ?></p>
    </div>
</div>