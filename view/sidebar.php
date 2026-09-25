<?php
// Contadores para el menú (material pendiente de recepción y packs en curso).
// Se usa la conexión del controlador si existe; si no (Dashboard, Configuración,
// Buscador... que usan modelos con su propia conexión), se abre una aquí.
$recepcionPendientes = 0;
$packsEnCurso = 0;
$conexionSidebar = null;
if (isset($conexion)) {
    $conexionSidebar = $conexion;
} else {
    include_once __DIR__ . '/../model/InventarioDB.php';
    $conexionSidebar = InventarioDB::connectDB();
}
include_once __DIR__ . '/../model/Recepcion.php';
$recepcionPendientes = Recepcion::contarPendientes($conexionSidebar);
include_once __DIR__ . '/../model/Pack.php';
$packsEnCurso = Pack::contarEnCurso($conexionSidebar);
include_once __DIR__ . '/../model/Devolucion.php';
$devolucionesPendientes = Devolucion::contarPendientes($conexionSidebar);
include_once __DIR__ . '/../model/Envio.php';
$enviosEnCurso = Envio::contarEnCurso($conexionSidebar);
?>
<!-- SIDEBAR ESCRITORIO -->
<nav class="d-none d-lg-flex flex-column bg-black text-white p-0" style="width:220px; min-width:220px;">
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
                <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">
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
                <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">
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
                <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">
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
                <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">
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
                <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">
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

        <?php if ($_SESSION['rol'] == 'admin') { ?>
            <?php if ($active == 'pack') { ?>
                <a href="pack.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded active-menu">
            <?php } else { ?>
                <a href="pack.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded">
            <?php } ?>
                <img src="../view/img/iconos/pack.svg" alt="" style="width:20px;height:20px;">
                <span>Packs apertura</span>
                <?php if (isset($packsEnCurso) && $packsEnCurso > 0) { ?>
                    <span class="badge bg-info ms-auto"><?php echo $packsEnCurso; ?></span>
                <?php } ?>
            </a>
        <?php } ?>

        <?php
        // Grupo "Transporte": movimientos de material (Envíos [admin] + Recepciones + Devoluciones).
        // Desplegable con Bootstrap collapse; se abre solo si estás dentro de uno de ellos.
        $transporteActivo = ($active == 'transporte' || $active == 'envio' || $active == 'recepcion' || $active == 'devolucion');

        $totalTransporte = 0;
        if ($_SESSION['rol'] == 'admin' && isset($enviosEnCurso)) { $totalTransporte += $enviosEnCurso; }
        if (isset($recepcionPendientes)) { $totalTransporte += $recepcionPendientes; }
        if (isset($devolucionesPendientes)) { $totalTransporte += $devolucionesPendientes; }
        ?>
        <a href="transporte.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-2 px-2 rounded <?php if ($transporteActivo) { echo 'active-menu'; } ?>">
            <img src="../view/img/iconos/transporte.svg" alt="" style="width:20px;height:20px;">
            <span>Transporte</span>
            <?php if ($totalTransporte > 0) { ?>
                <span class="badge bg-warning text-dark ms-auto"><?php echo $totalTransporte; ?></span>
            <?php } ?>
        </a>

        <?php if ($transporteActivo) { ?>
            <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">

                <?php if ($_SESSION['rol'] == 'admin') { ?>
                    <a href="envio.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded <?php if ($active == 'envio') { echo 'active-menu'; } ?>">
                        <img src="../view/img/iconos/envio.svg" alt="" style="width:18px;height:18px;">
                        <span>Envíos</span>
                        <?php if (isset($enviosEnCurso) && $enviosEnCurso > 0) { ?>
                            <span class="badge bg-primary ms-auto"><?php echo $enviosEnCurso; ?></span>
                        <?php } ?>
                    </a>
                <?php } ?>

                <a href="recepcion.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded <?php if ($active == 'recepcion') { echo 'active-menu'; } ?>">
                    <img src="../view/img/iconos/recepcionar.svg" alt="" style="width:18px;height:18px;">
                    <span>Recepciones</span>
                    <?php if (isset($recepcionPendientes) && $recepcionPendientes > 0) { ?>
                        <span class="badge bg-warning text-dark ms-auto"><?php echo $recepcionPendientes; ?></span>
                    <?php } ?>
                </a>

                <a href="devolucion.php" class="menu-link d-flex align-items-center gap-2 text-decoration-none text-white py-1 px-2 rounded <?php if ($active == 'devolucion') { echo 'active-menu'; } ?>">
                    <img src="../view/img/iconos/devolucion.svg" alt="" style="width:18px;height:18px;">
                    <span>Devoluciones</span>
                    <?php if (isset($devolucionesPendientes) && $devolucionesPendientes > 0) { ?>
                        <span class="badge bg-warning text-dark ms-auto"><?php echo $devolucionesPendientes; ?></span>
                    <?php } ?>
                </a>

            </div>
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
                <div class="ms-4 mt-1 collapse submenu-anim" data-open="1">
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

    <hr class="m-0 text-secondary ">
    <div class="p-3">
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
</nav>

<?php
// Clave de la seccion activa (los tres flujos comparten la seccion 'transporte').
$seccionMenu = $active;
if ($active == 'transporte' || $active == 'envio' || $active == 'recepcion' || $active == 'devolucion') {
    $seccionMenu = 'transporte';
}
?>
<script>
    // El submenu de la seccion activa se anima (deslizar) SOLO al ENTRAR en la seccion desde
    // fuera. Si navegas DENTRO de la misma seccion (p. ej. pulsar otra categoria de Stock), se
    // muestra al instante sin animar, para que no parpadee/recargue en cada clic.
    (function () {
        var sub = document.querySelector('.submenu-anim');
        if (!sub) { return; }
        var seccion = '<?php echo $seccionMenu; ?>';
        var prev = null;
        try {
            prev = sessionStorage.getItem('menuSeccion');
            sessionStorage.setItem('menuSeccion', seccion);
        } catch (e) {}

        if (prev === seccion) {
            sub.classList.add('show');   // misma seccion: visible ya, sin animacion (evita el parpadeo)
        } else {
            window.__animarSubmenu = sub; // seccion nueva: se animara cuando cargue Bootstrap
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {
        var sub = window.__animarSubmenu;
        if (!sub) { return; }
        try {
            bootstrap.Collapse.getOrCreateInstance(sub, { toggle: false }).show();
        } catch (e) {
            sub.classList.add('show'); // fallback: visible sin animacion
        }
    });
</script>