<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Tiendas</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
    <style>
        /* Cabeceras de seccion contraibles (Envios / Devolver / Compra directa) */
        .seccion-toggle .caret-sec { transition: transform .15s; display: inline-block; }
        .seccion-toggle[aria-expanded="true"] .caret-sec { transform: rotate(90deg); }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Detalle Tienda'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="flex-grow-1 p-4" style="min-width:0;">

                <?php
                if (!isset($errores)) { $errores = array(); }

                $soloLectura = false;
                if ($_SESSION['rol'] != 'admin') { $soloLectura = true; }

                $equipo1Actual = 0;
                if (isset($asignaciones['equipo1'])) { $equipo1Actual = $asignaciones['equipo1']->id_producto; }
                $equipo2Actual = 0;
                if (isset($asignaciones['equipo2'])) { $equipo2Actual = $asignaciones['equipo2']->id_producto; }
                $tickets1Actual = 0;
                if (isset($asignaciones['tickets1'])) { $tickets1Actual = $asignaciones['tickets1']->id_producto; }
                $tickets2Actual = 0;
                if (isset($asignaciones['tickets2'])) { $tickets2Actual = $asignaciones['tickets2']->id_producto; }
                $multifuncionActual = 0;
                if (isset($asignaciones['multifuncion'])) { $multifuncionActual = $asignaciones['multifuncion']->id_producto; }
                $cajonActual = 0;
                if (isset($asignaciones['cajon_portamonedas'])) { $cajonActual = $asignaciones['cajon_portamonedas']->id_producto; }
                $lectorCodigo1Actual = 0;
                if (isset($asignaciones['lector_codigo1'])) { $lectorCodigo1Actual = $asignaciones['lector_codigo1']->id_producto; }
                $lectorCodigo2Actual = 0;
                if (isset($asignaciones['lector_codigo2'])) { $lectorCodigo2Actual = $asignaciones['lector_codigo2']->id_producto; }
                $lectorBillete1Actual = 0;
                if (isset($asignaciones['lector_billete1'])) { $lectorBillete1Actual = $asignaciones['lector_billete1']->id_producto; }
                $lectorBillete2Actual = 0;
                if (isset($asignaciones['lector_billete2'])) { $lectorBillete2Actual = $asignaciones['lector_billete2']->id_producto; }
                $telefonoFijoActual = 0;
                if (isset($asignaciones['telefono_fijo'])) { $telefonoFijoActual = $asignaciones['telefono_fijo']->id_producto; }
                $telefonoMovilActual = 0;
                if (isset($asignaciones['telefono_movil'])) { $telefonoMovilActual = $asignaciones['telefono_movil']->id_producto; }
                $datafonoActual = 0;
                if (isset($asignaciones['datafono'])) { $datafonoActual = $asignaciones['datafono']->id_producto; }
                $pinpadActual = 0;
                if (isset($asignaciones['pinpad'])) { $pinpadActual = $asignaciones['pinpad']->id_producto; }
                $routerSOSActual = 0;
                if (isset($asignaciones['router_sos'])) { $routerSOSActual = $asignaciones['router_sos']->id_producto; }
                // Camaras 360 dinamicas
                $camaras360Actuales = array();
                for ($ci = 1; $ci <= 10; $ci++) {
                    $key = 'camara360_' . $ci;
                    if (isset($asignaciones[$key])) {
                        $camaras360Actuales[$ci] = $asignaciones[$key]->id_producto;
                    }
                }
                $camara360_1_Actual = 0;
                if (isset($camaras360Actuales[1])) { $camara360_1_Actual = $camaras360Actuales[1]; }
                $camaraFija_1_Actual = 0;
                if (isset($asignaciones['camara_fija_1'])) { $camaraFija_1_Actual = $asignaciones['camara_fija_1']->id_producto; }

                // Camaras fijas dinamicas
                $camarasFijasActuales = array();
                for ($ci = 1; $ci <= 10; $ci++) {
                    $key = 'camara_fija_' . $ci;
                    if (isset($asignaciones[$key])) {
                        $camarasFijasActuales[$ci] = $asignaciones[$key]->id_producto;
                    }
                }

                $posEquipo1 = '';
                if (isset($tienda->pos_equipo1)) { $posEquipo1 = $tienda->pos_equipo1; }
                $cajaEquipo1 = '';
                if (isset($tienda->caja_equipo1)) { $cajaEquipo1 = $tienda->caja_equipo1; }
                $anydeskEquipo1 = '';
                if (isset($tienda->anydesk_equipo1)) { $anydeskEquipo1 = $tienda->anydesk_equipo1; }
                $posEquipo2 = '';
                if (isset($tienda->pos_equipo2)) { $posEquipo2 = $tienda->pos_equipo2; }
                $cajaEquipo2 = '';
                if (isset($tienda->caja_equipo2)) { $cajaEquipo2 = $tienda->caja_equipo2; }
                $anydeskEquipo2 = '';
                if (isset($tienda->anydesk_equipo2)) { $anydeskEquipo2 = $tienda->anydesk_equipo2; }
                $operadoraActual = '';
                if (isset($tienda->operadora)) { $operadoraActual = $tienda->operadora; }
                $ipFijaActual = '';
                if (isset($tienda->ip_fija)) { $ipFijaActual = $tienda->ip_fija; }
                $vpnActual = '';
                if (isset($tienda->vpn)) { $vpnActual = $tienda->vpn; }

                $operadoras = array('Movistar', 'Vodafone', 'Orange', 'TIM', 'ZIGGO', 'Virgin');
                $vpnOpciones = array('Nordlayer');
                $posOpciones = array('Comerzzia', 'Odoo');
                ?>

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <a href="tienda.php">Tiendas</a> &gt; <strong>Detalle Tienda</strong>
                    </small>
                </div>

                <h2 class="mb-3">Detalle Tienda</h2>

                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['error']) && $_GET['error'] == '1') { ?>
                    <div class="alert alert-danger">Ha ocurrido un error guardando las asignaciones.</div>
                <?php } ?>

                <?php if (!$tienda) { ?>
                    <div class="alert alert-danger">Tienda no encontrada.</div>
                    <a href="tienda.php" class="btn btn-secondary">Volver</a>
                <?php } else { ?>

                    <!-- FORM 1: DATOS TIENDA -->
                    <form action="tienda.php?action=guardar" method="post">
                        <input type="hidden" name="id" value="<?php echo $tienda->id; ?>">

                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos Tienda</div>
                            <div class="p-3">

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="numero">Nº tienda</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <input class="form-control" type="number" name="numero" id="numero" value="<?php echo $tienda->numero; ?>" readonly>
                                        <?php } else { ?>
                                            <input class="form-control" type="number" name="numero" id="numero" value="<?php echo $tienda->numero; ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="nombre">Nombre</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <input class="form-control" type="text" name="nombre" id="nombre" value="<?php echo $tienda->nombre; ?>" readonly>
                                        <?php } else { ?>
                                            <input class="form-control" type="text" name="nombre" id="nombre" value="<?php echo $tienda->nombre; ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="sociedad">Sociedad</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <input class="form-control" type="text" value="<?php echo $tienda->sociedad . ' (' . $tienda->cif . ')'; ?>" readonly>
                                        <?php } else { ?>
                                            <select class="form-select" name="sociedad" id="sociedad">
                                                <option value="">Selecciona sociedad</option>
                                                <?php foreach ($sociedades as $s) { ?>
                                                    <?php if ($tienda->id_sociedad == $s->id) { ?>
                                                        <option value="<?php echo $s->id; ?>" selected>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $s->id; ?>">
                                                    <?php } ?>
                                                        <?php echo $s->nombre . " (" . $s->cif . ")"; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="movil">Móvil</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <input class="form-control" type="text" value="<?php echo $tienda->movil; ?>" readonly>
                                        <?php } else { ?>
                                            <select class="form-select" name="movil" id="movil">
                                                <option value="">Sin número móvil</option>
                                                <?php if (isset($numerosMovilDisponibles)) { ?>
                                                    <?php foreach ($numerosMovilDisponibles as $p) { ?>
                                                        <?php
                                                        $labelMovil = $p->codigo;
                                                        if (isset($p->numero) && $p->numero != '') {
                                                            $labelMovil = $p->numero;
                                                        }
                                                        if (isset($p->tarifa) && $p->tarifa != '') {
                                                            $labelMovil .= ' - ' . $p->tarifa;
                                                        }
                                                        ?>
                                                        <?php if ($tienda->movil == $p->codigo) { ?>
                                                            <option value="<?php echo $p->codigo; ?>" selected><?php echo $labelMovil; ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $p->codigo; ?>"><?php echo $labelMovil; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if (isset($tienda->movil) && $tienda->movil != '') { ?>
                                            <?php
                                            $idMovilActual = 0;
                                            if (isset($numerosMovilDisponibles)) {
                                                foreach ($numerosMovilDisponibles as $p) {
                                                    if ($p->codigo == $tienda->movil) {
                                                        $idMovilActual = $p->id;
                                                    }
                                                }
                                            }
                                            ?>
                                            <?php if ($idMovilActual > 0) { ?>
                                                <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $idMovilActual; ?>">Ver</a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="fijo">Fijo</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <input class="form-control" type="text" name="fijo" id="fijo" value="<?php echo $tienda->fijo; ?>" readonly>
                                        <?php } else { ?>
                                            <input class="form-control" type="text" name="fijo" id="fijo" value="<?php echo $tienda->fijo; ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="email">Email</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <input class="form-control" type="email" name="email" id="email" value="<?php echo $tienda->email; ?>" readonly>
                                        <?php } else { ?>
                                            <input class="form-control" type="email" name="email" id="email" value="<?php echo $tienda->email; ?>">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="observaciones">Observaciones</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <textarea class="form-control" name="observaciones" id="observaciones" rows="3" readonly><?php echo $tienda->observaciones; ?></textarea>
                                        <?php } else { ?>
                                            <textarea class="form-control" name="observaciones" id="observaciones" rows="3"><?php echo $tienda->observaciones; ?></textarea>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- CLUSTER MANAGER -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="id_cluster_manager">Cluster Manager</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php
                                        $idCMActual = 0;
                                        if (isset($tienda->id_cluster_manager)) {
                                            $idCMActual = $tienda->id_cluster_manager;
                                        }
                                        ?>
                                        <?php if ($soloLectura) { ?>
                                            <?php
                                            $nombreCM = 'Sin asignar';
                                            if (isset($clusterManagers)) {
                                                foreach ($clusterManagers as $cm) {
                                                    if ($cm->id == $idCMActual) {
                                                        $nombreCM = $cm->nombre;
                                                    }
                                                }
                                            }
                                            ?>
                                            <input class="form-control" type="text" value="<?php echo $nombreCM; ?>" readonly>
                                        <?php } else { ?>
                                            <select class="form-select" name="id_cluster_manager" id="id_cluster_manager">
                                                <option value="0">Sin Cluster Manager</option>
                                                <?php if (isset($clusterManagers)) { ?>
                                                    <?php foreach ($clusterManagers as $cm) { ?>
                                                        <?php if ($idCMActual == $cm->id) { ?>
                                                            <option value="<?php echo $cm->id; ?>"
                                                                data-movil="<?php echo $cm->movil_numero; ?>"
                                                                selected>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $cm->id; ?>"
                                                                data-movil="<?php echo $cm->movil_numero; ?>">
                                                        <?php } ?>
                                                            <?php echo $cm->nombre; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($idCMActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="trabajador_detalle.php?id=<?php echo $idCMActual; ?>">Ver</a>
                                        <?php } ?>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <?php if (isset($tienda->estado) && $tienda->estado == 'cerrada') { ?>
                            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                                <strong>⚠ Esta tienda está cerrada.</strong> Todo el material fue liberado al cerrarla.
                            </div>
                        <?php } ?>

                        <div class="d-flex justify-content-end gap-3 mb-4">
                            <a href="tienda.php" class="btn btn-secondary">Volver</a>
                            <?php if (!$soloLectura) { ?>
                                <?php if (isset($tienda->estado) && $tienda->estado == 'cerrada') { ?>
                                    <a class="btn btn-success" href="../controller/tienda.php?action=reabrir&id=<?php echo $tienda->id; ?>"
                                        onclick="return confirm('¿Reabrir esta tienda?');">Reabrir tienda</a>
                                <?php } else { ?>
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                    <a class="btn btn-danger" href="../controller/tienda.php?action=cerrar&id=<?php echo $tienda->id; ?>"
                                        onclick="return confirm('¿Cerrar esta tienda? Se liberará todo el material asignado.');">Cerrar tienda</a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </form>

                    <!-- FORM 2: CONFIGURACIÓN + ASIGNACIONES -->
                    <form action="tienda.php?action=guardar_asignaciones" method="post">
                        <input type="hidden" name="id" value="<?php echo $tienda->id; ?>">

                        <!-- EQUIPO -->
                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Equipo</div>
                            <div class="p-3">
                                <div class="row">

                                    <!-- EQUIPO 1 -->
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label m-0" for="equipo1">Equipo 1</label>
                                            <div class="d-flex gap-2 mt-1">
                                                <?php if ($soloLectura) { ?>
                                                    <select class="form-select" name="equipo1" id="equipo1" disabled>
                                                <?php } else { ?>
                                                    <select class="form-select" name="equipo1" id="equipo1">
                                                <?php } ?>
                                                    <option value="0">No tiene</option>
                                                    <?php if (isset($asignaciones['equipo1'])) { ?>
                                                        <option value="<?php echo $asignaciones['equipo1']->id_producto; ?>" selected>
                                                            <?php echo $asignaciones['equipo1']->codigo . ' - ' . $asignaciones['equipo1']->modelo; ?>
                                                        </option>
                                                    <?php } ?>
                                                    <?php foreach ($ordenadoresDisponibles as $p) { ?>
                                                        <?php if ($p->id != $equipo1Actual) { ?>
                                                            <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php if ($equipo1Actual > 0) { ?>
                                                    <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $equipo1Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $equipo1Actual); if ($__cod != '') { ?>
                                                    <!-- BOTON IMPRIMIR ETIQUETA -->
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="anydesk_equipo1">Anydesk</label>
                                            <?php if ($soloLectura) { ?>
                                                <input class="form-control mt-1" type="text" name="anydesk_equipo1" id="anydesk_equipo1" value="<?php echo $anydeskEquipo1; ?>" readonly>
                                            <?php } else { ?>
                                                <input class="form-control mt-1" type="text" name="anydesk_equipo1" id="anydesk_equipo1" value="<?php echo $anydeskEquipo1; ?>">
                                            <?php } ?>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="pos_equipo1">POS</label>
                                            <?php if ($soloLectura) { ?>
                                                <select class="form-select mt-1" name="pos_equipo1" id="pos_equipo1" disabled>
                                            <?php } else { ?>
                                                <select class="form-select mt-1" name="pos_equipo1" id="pos_equipo1">
                                            <?php } ?>
                                                <option value="">Selecciona</option>
                                                <?php foreach ($posOpciones as $pos) { ?>
                                                    <?php if ($posEquipo1 == $pos) { ?>
                                                        <option value="<?php echo $pos; ?>" selected><?php echo $pos; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $pos; ?>"><?php echo $pos; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="caja_equipo1">Caja</label>
                                            <?php if ($soloLectura) { ?>
                                                <input class="form-control mt-1" type="text" name="caja_equipo1" id="caja_equipo1" value="<?php echo $cajaEquipo1; ?>" readonly>
                                            <?php } else { ?>
                                                <input class="form-control mt-1" type="text" name="caja_equipo1" id="caja_equipo1" value="<?php echo $cajaEquipo1; ?>">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <!-- EQUIPO 2 -->
                                    <div class="col-12 col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label m-0" for="equipo2">Equipo 2</label>
                                            <div class="d-flex gap-2 mt-1">
                                                <?php if ($soloLectura) { ?>
                                                    <select class="form-select" name="equipo2" id="equipo2" disabled>
                                                <?php } else { ?>
                                                    <select class="form-select" name="equipo2" id="equipo2">
                                                <?php } ?>
                                                    <option value="0">No tiene</option>
                                                    <?php if (isset($asignaciones['equipo2'])) { ?>
                                                        <option value="<?php echo $asignaciones['equipo2']->id_producto; ?>" selected>
                                                            <?php echo $asignaciones['equipo2']->codigo . ' - ' . $asignaciones['equipo2']->modelo; ?>
                                                        </option>
                                                    <?php } ?>
                                                    <?php foreach ($ordenadoresDisponibles as $p) { ?>
                                                        <?php if ($p->id != $equipo2Actual) { ?>
                                                            <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php if ($equipo2Actual > 0) { ?>
                                                    <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $equipo2Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $equipo2Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="anydesk_equipo2">Anydesk</label>
                                            <?php if ($soloLectura) { ?>
                                                <input class="form-control mt-1" type="text" name="anydesk_equipo2" id="anydesk_equipo2" value="<?php echo $anydeskEquipo2; ?>" readonly>
                                            <?php } else { ?>
                                                <input class="form-control mt-1" type="text" name="anydesk_equipo2" id="anydesk_equipo2" value="<?php echo $anydeskEquipo2; ?>">
                                            <?php } ?>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="pos_equipo2">POS</label>
                                            <?php if ($soloLectura) { ?>
                                                <select class="form-select mt-1" name="pos_equipo2" id="pos_equipo2" disabled>
                                            <?php } else { ?>
                                                <select class="form-select mt-1" name="pos_equipo2" id="pos_equipo2">
                                            <?php } ?>
                                                <option value="">Selecciona</option>
                                                <?php foreach ($posOpciones as $pos) { ?>
                                                    <?php if ($posEquipo2 == $pos) { ?>
                                                        <option value="<?php echo $pos; ?>" selected><?php echo $pos; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $pos; ?>"><?php echo $pos; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="caja_equipo2">Caja</label>
                                            <?php if ($soloLectura) { ?>
                                                <input class="form-control mt-1" type="text" name="caja_equipo2" id="caja_equipo2" value="<?php echo $cajaEquipo2; ?>" readonly>
                                            <?php } else { ?>
                                                <input class="form-control mt-1" type="text" name="caja_equipo2" id="caja_equipo2" value="<?php echo $cajaEquipo2; ?>">
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- PERIFÉRICOS -->
                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Periféricos</div>
                            <div class="p-3">

                                <!-- Impresora Tickets 1 -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="tickets1">Impresora Tickets 1</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="tickets1" id="tickets1" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="tickets1" id="tickets1">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['tickets1'])) { ?>
                                                <option value="<?php echo $asignaciones['tickets1']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['tickets1']->codigo . ' - ' . $asignaciones['tickets1']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($impTicketsDisponibles as $p) { ?>
                                                <?php if ($p->id != $tickets1Actual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($tickets1Actual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $tickets1Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $tickets1Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Impresora Tickets 2 -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="tickets2">Impresora Tickets 2</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="tickets2" id="tickets2" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="tickets2" id="tickets2">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['tickets2'])) { ?>
                                                <option value="<?php echo $asignaciones['tickets2']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['tickets2']->codigo . ' - ' . $asignaciones['tickets2']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($impTicketsDisponibles as $p) { ?>
                                                <?php if ($p->id != $tickets2Actual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($tickets2Actual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $tickets2Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $tickets2Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Impresora Multifunción -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="multifuncion">Impresora Multifunción</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="multifuncion" id="multifuncion" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="multifuncion" id="multifuncion">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['multifuncion'])) { ?>
                                                <option value="<?php echo $asignaciones['multifuncion']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['multifuncion']->codigo . ' - ' . $asignaciones['multifuncion']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($impMultiDisponibles as $p) { ?>
                                                <?php if ($p->id != $multifuncionActual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($multifuncionActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $multifuncionActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $multifuncionActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Cajón Portamonedas -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="cajon_portamonedas">Cajón Portamonedas</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="cajon_portamonedas" id="cajon_portamonedas" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="cajon_portamonedas" id="cajon_portamonedas">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['cajon_portamonedas'])) { ?>
                                                <option value="<?php echo $asignaciones['cajon_portamonedas']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['cajon_portamonedas']->codigo . ' - ' . $asignaciones['cajon_portamonedas']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($cajonesDisponibles as $p) { ?>
                                                <?php if ($p->id != $cajonActual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($cajonActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $cajonActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $cajonActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Lector Código 1 -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="lector_codigo1">Lector Código 1</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="lector_codigo1" id="lector_codigo1" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="lector_codigo1" id="lector_codigo1">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['lector_codigo1'])) { ?>
                                                <option value="<?php echo $asignaciones['lector_codigo1']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['lector_codigo1']->codigo . ' - ' . $asignaciones['lector_codigo1']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($lectorCodigoDisponibles as $p) { ?>
                                                <?php if ($p->id != $lectorCodigo1Actual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($lectorCodigo1Actual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $lectorCodigo1Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $lectorCodigo1Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Lector Código 2 -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="lector_codigo2">Lector Código 2</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="lector_codigo2" id="lector_codigo2" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="lector_codigo2" id="lector_codigo2">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['lector_codigo2'])) { ?>
                                                <option value="<?php echo $asignaciones['lector_codigo2']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['lector_codigo2']->codigo . ' - ' . $asignaciones['lector_codigo2']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($lectorCodigoDisponibles as $p) { ?>
                                                <?php if ($p->id != $lectorCodigo2Actual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($lectorCodigo2Actual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $lectorCodigo2Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $lectorCodigo2Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Lector Billete 1 -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="lector_billete1">Lector Billete 1</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="lector_billete1" id="lector_billete1" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="lector_billete1" id="lector_billete1">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['lector_billete1'])) { ?>
                                                <option value="<?php echo $asignaciones['lector_billete1']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['lector_billete1']->codigo . ' - ' . $asignaciones['lector_billete1']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($lectorBilleteDisponibles as $p) { ?>
                                                <?php if ($p->id != $lectorBillete1Actual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($lectorBillete1Actual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $lectorBillete1Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $lectorBillete1Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Lector Billete 2 -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="lector_billete2">Lector Billete 2</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="lector_billete2" id="lector_billete2" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="lector_billete2" id="lector_billete2">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['lector_billete2'])) { ?>
                                                <option value="<?php echo $asignaciones['lector_billete2']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['lector_billete2']->codigo . ' - ' . $asignaciones['lector_billete2']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($lectorBilleteDisponibles as $p) { ?>
                                                <?php if ($p->id != $lectorBillete2Actual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($lectorBillete2Actual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $lectorBillete2Actual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $lectorBillete2Actual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Teléfono Fijo -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="telefono_fijo">Teléfono Fijo</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="telefono_fijo" id="telefono_fijo" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="telefono_fijo" id="telefono_fijo">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['telefono_fijo'])) { ?>
                                                <option value="<?php echo $asignaciones['telefono_fijo']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['telefono_fijo']->codigo . ' - ' . $asignaciones['telefono_fijo']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($telefonosFijosDisponibles as $p) { ?>
                                                <?php if ($p->id != $telefonoFijoActual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($telefonoFijoActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $telefonoFijoActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $telefonoFijoActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Teléfono Móvil -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="telefono_movil">Teléfono Móvil</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="telefono_movil" id="telefono_movil" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="telefono_movil" id="telefono_movil">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['telefono_movil'])) { ?>
                                                <option value="<?php echo $asignaciones['telefono_movil']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['telefono_movil']->codigo . ' - ' . $asignaciones['telefono_movil']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($telefonosMovilesDisponibles as $p) { ?>
                                                <?php if ($p->id != $telefonoMovilActual) { ?>
                                                    <?php
                                                    $labelMovil = $p->codigo;
                                                    if (isset($p->numero) && $p->numero != '') {
                                                        $labelMovil = $p->numero;
                                                    }
                                                    if (isset($p->tarifa) && $p->tarifa != '') {
                                                        $labelMovil .= ' - ' . $p->tarifa;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $labelMovil; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($telefonoMovilActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $telefonoMovilActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $telefonoMovilActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Datáfono -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="datafono">Datáfono</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="datafono" id="datafono" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="datafono" id="datafono">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['datafono'])) { ?>
                                                <option value="<?php echo $asignaciones['datafono']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['datafono']->codigo . ' - ' . $asignaciones['datafono']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($datafonosDisponibles as $p) { ?>
                                                <?php if ($p->id != $datafonoActual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($datafonoActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $datafonoActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $datafonoActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- Pinpad -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3"><label class="form-label m-0" for="pinpad">Pinpad</label></div>
                                    <div class="col-12 col-md-6">
                                        <?php if ($soloLectura) { ?>
                                            <select class="form-select" name="pinpad" id="pinpad" disabled>
                                        <?php } else { ?>
                                            <select class="form-select" name="pinpad" id="pinpad">
                                        <?php } ?>
                                            <option value="0">No tiene</option>
                                            <?php if (isset($asignaciones['pinpad'])) { ?>
                                                <option value="<?php echo $asignaciones['pinpad']->id_producto; ?>" selected>
                                                    <?php echo $asignaciones['pinpad']->codigo . ' - ' . $asignaciones['pinpad']->modelo; ?>
                                                </option>
                                            <?php } ?>
                                            <?php foreach ($pinpadsDisponibles as $p) { ?>
                                                <?php if ($p->id != $pinpadActual) { ?>
                                                    <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <?php if ($pinpadActual > 0) { ?>
                                            <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $pinpadActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $pinpadActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- CONECTIVIDAD -->
                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Conectividad</div>
                            <div class="p-3">
                                <div class="row">
                                    <div class="col-12 col-lg-6">

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="operadora">Operadora</label>
                                            <?php if ($soloLectura) { ?>
                                                <select class="form-select mt-1" name="operadora" id="operadora" disabled>
                                            <?php } else { ?>
                                                <select class="form-select mt-1" name="operadora" id="operadora">
                                            <?php } ?>
                                                <option value="">Selecciona</option>
                                                <?php foreach ($operadoras as $op) { ?>
                                                    <?php if ($operadoraActual == $op) { ?>
                                                        <option value="<?php echo $op; ?>" selected><?php echo $op; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $op; ?>"><?php echo $op; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="router_sos">Router SOS</label>
                                            <div class="d-flex gap-2 mt-1">
                                                <?php if ($soloLectura) { ?>
                                                    <select class="form-select" name="router_sos" id="router_sos" disabled>
                                                <?php } else { ?>
                                                    <select class="form-select" name="router_sos" id="router_sos">
                                                <?php } ?>
                                                    <option value="0">No tiene</option>
                                                    <?php if (isset($asignaciones['router_sos'])) { ?>
                                                        <option value="<?php echo $asignaciones['router_sos']->id_producto; ?>" selected>
                                                            <?php echo $asignaciones['router_sos']->codigo . ' - ' . $asignaciones['router_sos']->modelo; ?>
                                                        </option>
                                                    <?php } ?>
                                                    <?php foreach ($routersDisponibles as $p) { ?>
                                                        <?php if ($p->id != $routerSOSActual) { ?>
                                                            <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <?php if ($routerSOSActual > 0) { ?>
                                                    <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $routerSOSActual; ?>">Ver</a>
                                                <?php $__cod = getCodigoPorId($asignaciones, $routerSOSActual); if ($__cod != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank" href="../controller/etiqueta.php?codigo=<?php echo $__cod; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-12 col-lg-6">

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="ip_fija">IP fija</label>
                                            <?php if ($soloLectura) { ?>
                                                <input class="form-control mt-1" type="text" name="ip_fija" id="ip_fija" value="<?php echo $ipFijaActual; ?>" readonly>
                                            <?php } else { ?>
                                                <input class="form-control mt-1" type="text" name="ip_fija" id="ip_fija" value="<?php echo $ipFijaActual; ?>">
                                            <?php } ?>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label m-0" for="vpn">VPN</label>
                                            <?php if ($soloLectura) { ?>
                                                <select class="form-select mt-1" name="vpn" id="vpn" disabled>
                                            <?php } else { ?>
                                                <select class="form-select mt-1" name="vpn" id="vpn">
                                            <?php } ?>
                                                <option value="">Selecciona</option>
                                                <?php foreach ($vpnOpciones as $vpnItem) { ?>
                                                    <?php if ($vpnActual == $vpnItem) { ?>
                                                        <option value="<?php echo $vpnItem; ?>" selected><?php echo $vpnItem; ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $vpnItem; ?>"><?php echo $vpnItem; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CÁMARAS -->
                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Cámaras</div>
                            <div class="p-3">

                                <!-- CÁMARAS 360 -->
                                <div id="wrap360">
                                    <?php
                                    $maxCam360 = max(count($camaras360Actuales), 1);
                                    for ($ci = 1; $ci <= $maxCam360; $ci++) {
                                        $idActual = 0;
                                        if (isset($camaras360Actuales[$ci])) { $idActual = $camaras360Actuales[$ci]; }
                                    ?>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-12 col-md-3">
                                            <label class="form-label m-0">Cámara 360 <?php echo $ci; ?></label>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <?php if ($soloLectura) { ?>
                                                <select class="form-select" name="camara360[]" disabled>
                                            <?php } else { ?>
                                                <select class="form-select" name="camara360[]">
                                            <?php } ?>
                                                <option value="0">No tiene</option>
                                                <?php if ($idActual > 0 && isset($asignaciones['camara360_' . $ci])) { ?>
                                                    <option value="<?php echo $idActual; ?>" selected>
                                                        <?php echo $asignaciones['camara360_' . $ci]->codigo . ' - ' . $asignaciones['camara360_' . $ci]->modelo; ?>
                                                    </option>
                                                <?php } ?>
                                                <?php foreach ($camaras360Disponibles as $p) { ?>
                                                    <?php if ($p->id != $idActual) { ?>
                                                        <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-3 mt-2 mt-md-0 d-flex gap-2">
                                            <?php if ($idActual > 0) { ?>
                                                <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $idActual; ?>">Ver</a>
                                                <?php
                                                $codigoActual = '';
                                                foreach ($asignaciones as $slotKey => $asig) {
                                                    if ($asig->id_producto == $idActual) {
                                                        $codigoActual = $asig->codigo;
                                                    }
                                                }
                                                ?>
                                                <?php if ($codigoActual != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank"
                                                        href="../controller/etiqueta.php?codigo=<?php echo $codigoActual; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (!$soloLectura && $ci == $maxCam360) { ?>
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="añadirCamara('360')">+ Añadir</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <!-- CÁMARAS FIJAS -->
                                <div id="wrapFija">
                                    <?php
                                    $maxCamFija = max(count($camarasFijasActuales), 1);
                                    for ($ci = 1; $ci <= $maxCamFija; $ci++) {
                                        $idActual = 0;
                                        if (isset($camarasFijasActuales[$ci])) { $idActual = $camarasFijasActuales[$ci]; }
                                    ?>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-12 col-md-3">
                                            <label class="form-label m-0">Cámara Fija <?php echo $ci; ?></label>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <?php if ($soloLectura) { ?>
                                                <select class="form-select" name="camara_fija[]" disabled>
                                            <?php } else { ?>
                                                <select class="form-select" name="camara_fija[]">
                                            <?php } ?>
                                                <option value="0">No tiene</option>
                                                <?php if ($idActual > 0 && isset($asignaciones['camara_fija_' . $ci])) { ?>
                                                    <option value="<?php echo $idActual; ?>" selected>
                                                        <?php echo $asignaciones['camara_fija_' . $ci]->codigo . ' - ' . $asignaciones['camara_fija_' . $ci]->modelo; ?>
                                                    </option>
                                                <?php } ?>
                                                <?php foreach ($camarasFijasDisponibles as $p) { ?>
                                                    <?php if ($p->id != $idActual) { ?>
                                                        <option value="<?php echo $p->id; ?>"><?php echo $p->codigo . ' - ' . $p->modelo; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-3 mt-2 mt-md-0 d-flex gap-2">
                                            <?php if ($idActual > 0) { ?>
                                                <a class="btn btn-outline-primary btn-sm" target="_blank" href="stock.php?action=ver&id=<?php echo $idActual; ?>">Ver</a>
                                                <?php
                                                $codigoActual = '';
                                                foreach ($asignaciones as $slotKey => $asig) {
                                                    if ($asig->id_producto == $idActual) {
                                                        $codigoActual = $asig->codigo;
                                                    }
                                                }
                                                ?>
                                                <?php if ($codigoActual != '') { ?>
                                                    <a class="btn btn-outline-dark btn-sm" target="_blank"
                                                        href="../controller/etiqueta.php?codigo=<?php echo $codigoActual; ?>"><img src="../view/img/iconos/etiqueta.svg" alt="Etiqueta" style="width:16px;height:16px;"></a>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (!$soloLectura && $ci == $maxCamFija) { ?>
                                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="añadirCamara('fija')">+ Añadir</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                                                <?php if (!$soloLectura) { ?>
                            <div class="d-flex justify-content-end gap-2">
                                <!-- BOTONES EXPORTAR -->
                                <a href="../controller/exportar.php?tipo=tienda&formato=pdf&id=<?php echo $tienda->id; ?>" target="_blank" class="btn btn-danger btn-sm">Exportar PDF</a>
                                <a href="../controller/exportar.php?tipo=tienda&formato=excel&id=<?php echo $tienda->id; ?>" class="btn btn-success btn-sm">Exportar Excel</a>
                                <?php if (!$soloLectura) { ?>
                                <button class="btn btn-primary" type="submit">Guardar asignaciones</button>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </form>

                    <?php
                    // Material adicional: cualquier asignación cuyo slot no sea uno de los
                    // huecos con nombre del detalle. Aquí caen las piezas del pack de apertura
                    // y el material asignado desde Stock cuya categoría no tiene hueco propio
                    // (cámara, monitor, ratón, teclado, HUB, número móvil, lector suelto...).
                    $slotsConocidos = array(
                        'equipo1', 'equipo2', 'tickets1', 'tickets2', 'multifuncion',
                        'cajon_portamonedas', 'lector_codigo1', 'lector_codigo2',
                        'lector_billete1', 'lector_billete2', 'telefono_fijo',
                        'telefono_movil', 'datafono', 'pinpad', 'router_sos', 'movil_tienda'
                    );
                    $materialAdicional = array();
                    if (isset($asignaciones)) {
                        foreach ($asignaciones as $slotAsig => $asig) {
                            $conocido = false;
                            if (in_array($slotAsig, $slotsConocidos)) { $conocido = true; }
                            if (substr($slotAsig, 0, 11) == 'camara_360_') { $conocido = true; }
                            if (substr($slotAsig, 0, 12) == 'camara_fija_') { $conocido = true; }
                            if (!$conocido) { $materialAdicional[] = $asig; }
                        }
                    }
                    ?>
                    <?php if (count($materialAdicional) > 0) { ?>
                        <div class="mt-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Material adicional</div>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr><th>Código</th><th>Modelo</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materialAdicional as $ma) { ?>
                                            <tr>
                                                <td><?php echo $ma->codigo; ?></td>
                                                <td><?php echo $ma->modelo; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>

                    <?php
                    $tiendaCerrada = false;
                    if (isset($tienda->estado) && $tienda->estado == 'cerrada') { $tiendaCerrada = true; }
                    ?>

                    <?php
                    // Envios a esta tienda (reposicion / sustitucion). Solo admin y tienda operativa.
                    // El envio se crea "Preparando"; al marcar "Llegado" se elige el hueco y, si estaba
                    // ocupado, se puede devolver el equipo antiguo a oficina en el mismo paso.
                    ?>
                    <?php if (!$soloLectura && !$tiendaCerrada) { ?>
                        <div class="mt-4 border rounded">
                            <div class="bg-primary bg-opacity-10 p-2 fw-semibold seccion-toggle" data-bs-toggle="collapse" data-bs-target="#secEnvios" role="button" aria-expanded="true" style="cursor:pointer;"><span class="caret-sec">&#9656;</span> Envíos a esta tienda</div>
                            <div class="collapse show p-3" id="secEnvios">

                                <?php if (isset($_GET['envio_ocupado']) && $_GET['envio_ocupado'] == '1') { ?>
                                    <div class="alert alert-warning">
                                        Ese hueco ya tenía un equipo. Marca <strong>"devolver el antiguo a oficina"</strong>
                                        al confirmar la llegada, o elige otro hueco.
                                    </div>
                                <?php } ?>

                                <div class="text-muted small mb-3">
                                    Envía material a esta tienda. Se crea en estado <strong>Preparando</strong>; al llegar,
                                    eliges el hueco y, si estaba ocupado, puedes devolver el equipo antiguo a
                                    <a href="devolucion.php">Devoluciones</a>.
                                </div>

                                <form method="post" action="envio.php?action=crear" class="row g-2 align-items-end mb-3">
                                    <input type="hidden" name="id_tienda" value="<?php echo $tienda->id; ?>">
                                    <input type="hidden" name="volver" value="<?php echo $tienda->id; ?>">
                                    <div class="col-12 col-md-7">
                                        <label class="form-label m-0">Material a enviar</label>
                                        <select name="id_producto" class="form-select" required>
                                            <option value="">Selecciona material disponible</option>
                                            <?php if (isset($enviosDisponibles)) { ?>
                                                <?php foreach ($enviosDisponibles as $p) { ?>
                                                    <option value="<?php echo $p->id; ?>">
                                                        <?php echo $p->codigo; ?> - <?php echo $p->modelo; ?> (<?php echo $p->categoria; ?>)
                                                    </option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label m-0">Motivo (opcional)</label>
                                        <input type="text" name="motivo" class="form-control" placeholder="Reposición, sustitución...">
                                    </div>
                                    <div class="col-12 col-md-1 d-grid">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </form>

                                <?php
                                $envios = array();
                                if (isset($enviosTienda)) { $envios = $enviosTienda; }
                                $enviosMostrarTienda = false;
                                $envioVolver = $tienda->id;
                                include 'envio_lista_parcial.php';
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (!$soloLectura && !$tiendaCerrada && isset($asignaciones) && count($asignaciones) > 0) { ?>
                        <div class="mt-4 border rounded">
                            <div class="bg-warning bg-opacity-25 p-2 fw-semibold seccion-toggle" data-bs-toggle="collapse" data-bs-target="#secDevolver" role="button" aria-expanded="true" style="cursor:pointer;"><span class="caret-sec">&#9656;</span> Devolver material a oficina (avería)</div>
                            <div class="collapse show p-3" id="secDevolver">
                                <div class="text-muted small mb-3">
                                    Marca aquí el material que la tienda devuelve a oficina. Saldrá del inventario de la
                                    tienda y quedará pendiente de recibir en <a href="devolucion.php">Devoluciones</a>.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr><th>Código</th><th>Modelo</th><th>Ubicación</th><th class="text-end">Acción</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($asignaciones as $slotAsig => $asig) { ?>
                                                <?php if ($slotAsig != 'movil_tienda') { ?>
                                                    <tr>
                                                        <td><?php echo $asig->codigo; ?></td>
                                                        <td><?php echo $asig->modelo; ?></td>
                                                        <td><span class="text-muted small"><?php echo $slotAsig; ?></span></td>
                                                        <td class="text-end">
                                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#dev<?php echo $asig->id_producto; ?>">
                                                                Devolver a oficina
                                                            </button>
                                                            <div class="collapse mt-2 text-start" id="dev<?php echo $asig->id_producto; ?>">
                                                                <form method="post" action="tienda.php?action=devolver"
                                                                      onsubmit="return confirm('¿Devolver este material a oficina? Saldrá del inventario de la tienda.');">
                                                                    <input type="hidden" name="id_producto" value="<?php echo $asig->id_producto; ?>">
                                                                    <input type="hidden" name="id_tienda" value="<?php echo $tienda->id; ?>">
                                                                    <input type="hidden" name="slot" value="<?php echo $slotAsig; ?>">
                                                                    <textarea name="motivo" class="form-control form-control-sm mb-2"
                                                                              rows="2" placeholder="Motivo (avería de pantalla, no enciende...)"></textarea>
                                                                    <button type="submit" class="btn btn-warning btn-sm">Confirmar devolución</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php
                    // Compra directa: material comprado que llega directo a la tienda (sin pasar por
                    // oficina). Crea el producto y lo asigna al hueco en un paso. Solo admin y tienda operativa.
                    ?>
                    <?php if (!$soloLectura && !$tiendaCerrada) { ?>
                        <div class="mt-4 border rounded">
                            <div class="bg-success bg-opacity-25 p-2 fw-semibold seccion-toggle" data-bs-toggle="collapse" data-bs-target="#secCompra" role="button" aria-expanded="true" style="cursor:pointer;"><span class="caret-sec">&#9656;</span> Registrar material comprado (directo)</div>
                            <div class="collapse show p-3" id="secCompra">

                                <?php if (isset($_GET['cd_ok']) && $_GET['cd_ok'] != '') { ?>
                                    <div class="alert alert-success">Material registrado con el código <strong><?php echo $_GET['cd_ok']; ?></strong> y asignado a la tienda.</div>
                                <?php } ?>
                                <?php if (isset($_GET['cd_error'])) { ?>
                                    <div class="alert alert-danger">
                                        <?php if ($_GET['cd_error'] == 'datos') { ?>
                                            Faltan datos: categoría, modelo y subtipo son obligatorios.
                                        <?php } else if ($_GET['cd_error'] == 'codigo') { ?>
                                            Ese código ya existe. Usa otro o déjalo vacío para autogenerarlo.
                                        <?php } else if ($_GET['cd_error'] == 'ocupado') { ?>
                                            Ese hueco ya tiene material. Elige otro hueco (o "Material adicional").
                                        <?php } else if ($_GET['cd_error'] == 'tienda') { ?>
                                            La tienda no está operativa.
                                        <?php } else { ?>
                                            No se pudo registrar el material.
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <div class="text-muted small mb-3">
                                    Da de alta material que ha llegado directamente a la tienda. Si no lleva código pegado,
                                    deja el código vacío y se generará uno automático (CD…).
                                </div>

                                <form method="post" action="tienda.php?action=compra_directa" class="row g-2 align-items-end">
                                    <input type="hidden" name="id_tienda" value="<?php echo $tienda->id; ?>">

                                    <div class="col-12 col-md-4">
                                        <label class="form-label m-0">Categoría</label>
                                        <select name="id_categoria" class="form-select" required>
                                            <option value="">Selecciona categoría</option>
                                            <?php if (isset($categorias)) { ?>
                                                <?php foreach ($categorias as $c) { ?>
                                                    <option value="<?php echo $c->id; ?>"><?php echo $c->nombre; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label m-0">Modelo</label>
                                        <input type="text" name="modelo" class="form-control" required>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label m-0">Subtipo</label>
                                        <input type="text" name="subtipo" class="form-control" placeholder="Ej: portatil, tickets, teclado..." required>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0">Condición</label>
                                        <select name="condicion" class="form-select">
                                            <?php if (isset($condicionesAsignables)) { ?>
                                                <?php foreach ($condicionesAsignables as $valor => $etiqueta) { ?>
                                                    <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0">Código (opcional)</label>
                                        <input type="text" name="codigo" class="form-control" placeholder="Vacío = autogenerar">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label m-0">Hueco destino</label>
                                        <select name="slot" class="form-select">
                                            <?php if (isset($slotsTienda)) { ?>
                                                <?php foreach ($slotsTienda as $valor => $etiqueta) { ?>
                                                    <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2 d-grid">
                                        <button type="submit" class="btn btn-success">Registrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>

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
    <script>
        // Opciones de camaras disponibles generadas desde PHP
        var opciones360 = '<option value="0">No tiene</option><?php foreach ($camaras360Disponibles as $p) { echo "<option value=\"" . $p->id . "\">" . $p->codigo . " - " . $p->modelo . "</option>"; } ?>';
        var opcionesFija = '<option value="0">No tiene</option><?php foreach ($camarasFijasDisponibles as $p) { echo "<option value=\"" . $p->id . "\">" . $p->codigo . " - " . $p->modelo . "</option>"; } ?>';

        function eliminarCamara(btn) {
            var fila = btn.closest('.row');
            var wrap = fila.parentElement;
            fila.remove();
            // Renumerar etiquetas
            var tipo = wrap.id === 'wrap360' ? 'Cámara 360' : 'Cámara Fija';
            var filas = wrap.querySelectorAll('.row');
            for (var i = 0; i < filas.length; i++) {
                var label = filas[i].querySelector('label');
                if (label) { label.textContent = tipo + ' ' + (i + 1); }
            }
        }

        function añadirCamara(tipo) {
            var wrap = document.getElementById(tipo === '360' ? 'wrap360' : 'wrapFija');
            var filas = wrap.querySelectorAll('.row');
            var num = filas.length + 1;
            var nombre = tipo === '360' ? 'Cámara 360' : 'Cámara Fija';
            var inputName = tipo === '360' ? 'camara360[]' : 'camara_fija[]';
            var opts = tipo === '360' ? opciones360 : opcionesFija;

            var div = document.createElement('div');
            div.className = 'row align-items-center mb-3';
            div.innerHTML =
                '<div class="col-12 col-md-3">' +
                    '<label class="form-label m-0">' + nombre + ' ' + num + '</label>' +
                '</div>' +
                '<div class="col-12 col-md-6">' +
                    '<select class="form-select" name="' + inputName + '">' + opts + '</select>' +
                '</div>' +
                '<div class="col-12 col-md-3 d-flex gap-2">' +
                    '<button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarCamara(this)">Eliminar</button>' +
                '</div>';

            wrap.appendChild(div);
        }

        // Añadir boton eliminar a filas existentes (excepto la primera de cada grupo)
        document.addEventListener('DOMContentLoaded', function() {
            ['wrap360', 'wrapFija'].forEach(function(wrapId) {
                var wrap = document.getElementById(wrapId);
                if (!wrap) return;
                var filas = wrap.querySelectorAll('.row');
                filas.forEach(function(fila, idx) {
                    if (idx === 0) return; // La primera no se puede eliminar
                    var col = fila.querySelector('.col-12.col-md-3.mt-2');
                    if (!col) { col = fila.querySelector('.col-12.col-md-3'); }
                    if (col) {
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-outline-danger btn-sm';
                        btn.textContent = 'Eliminar';
                        btn.setAttribute('onclick', 'eliminarCamara(this)');
                        col.appendChild(btn);
                    }
                });
            });
        });
    </script>
</body>

</html>