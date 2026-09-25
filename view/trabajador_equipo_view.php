<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Equipo trabajador</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Equipo Trabajador'; include_once 'menu.php'; ?>

    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row flex-grow-1 g-0 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="col-12 col-lg-10 p-4">

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Equipo trabajador</strong>
                    </small>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h2 class="m-0"><?php echo $trabajador->nombre; ?></h2>
                    <a class="btn btn-outline-secondary" href="trabajador_detalle.php?id=<?php echo $trabajador->id; ?>">
                        Volver al detalle
                    </a>
                </div>

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
                $idEquipo1Actual = 0;
                if (isset($idEquipo1)) {
                    $idEquipo1Actual = $idEquipo1;
                }

                $idEquipo2Actual = 0;
                if (isset($idEquipo2)) {
                    $idEquipo2Actual = $idEquipo2;
                }

                $valorAnydesk1 = '';
                if (isset($anydesk_equipo1)) {
                    $valorAnydesk1 = $anydesk_equipo1;
                }

                $valorAnydesk2 = '';
                if (isset($anydesk_equipo2)) {
                    $valorAnydesk2 = $anydesk_equipo2;
                }

                $idMonitor1Actual = 0;
                if (isset($idMonitor1)) {
                    $idMonitor1Actual = $idMonitor1;
                }

                $idMonitor2Actual = 0;
                if (isset($idMonitor2)) {
                    $idMonitor2Actual = $idMonitor2;
                }

                $idTecladoActual = 0;
                if (isset($idTeclado)) {
                    $idTecladoActual = $idTeclado;
                }

                $idRatonActual = 0;
                if (isset($idRaton)) {
                    $idRatonActual = $idRaton;
                }

                $idTelefonoMovilActual = 0;
                if (isset($idTelefonoMovil)) {
                    $idTelefonoMovilActual = $idTelefonoMovil;
                }

                $idMaletinActual = 0;
                if (isset($idMaletin)) {
                    $idMaletinActual = $idMaletin;
                }
                ?>

                <form method="post" action="trabajador.php?action=equipo&id=<?php echo $trabajador->id; ?>">

                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Equipos</div>
                        <div class="p-3">

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="equipo1">Equipo 1</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="equipo1" id="equipo1">
                                        <option value="0">Sin equipo</option>
                                        <?php foreach ($equiposDisponibles as $p) { ?>
                                            <?php if ($idEquipo1Actual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="equipo2">Equipo 2</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="equipo2" id="equipo2">
                                        <option value="0">Sin equipo</option>
                                        <?php foreach ($equiposDisponibles2 as $p) { ?>
                                            <?php if ($idEquipo2Actual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="anydesk1">AnyDesk Equipo 1</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="form-control" name="anydesk_equipo1"
                                        value="<?php echo $valorAnydesk1; ?>">
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-12 col-md-3">
                                    <label class="form-label m-0" for="anydesk2">AnyDesk Equipo 2</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="form-control" name="anydesk_equipo2"
                                        value="<?php echo $valorAnydesk2; ?>">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Periféricos</div>
                        <div class="p-3">

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3"><label class="form-label m-0" for="monitor1">Monitor 1</label></div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="monitor1" id="monitor1">
                                        <option value="0">Sin monitor</option>
                                        <?php foreach ($monitoresDisponibles as $p) { ?>
                                            <?php if ($idMonitor1Actual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3"><label class="form-label m-0" for="monitor2">Monitor 2</label></div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="monitor2" id="monitor2">
                                        <option value="0">Sin monitor</option>
                                        <?php foreach ($monitoresDisponibles2 as $p) { ?>
                                            <?php if ($idMonitor2Actual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3"><label class="form-label m-0" for="teclado">Teclado</label></div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="teclado" id="teclado">
                                        <option value="0">Sin teclado</option>
                                        <?php foreach ($tecladosDisponibles as $p) { ?>
                                            <?php if ($idTecladoActual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3"><label class="form-label m-0" for="raton">Ratón</label></div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="raton" id="raton">
                                        <option value="0">Sin ratón</option>
                                        <?php foreach ($ratonesDisponibles as $p) { ?>
                                            <?php if ($idRatonActual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-3"><label class="form-label m-0" for="telefono_movil">Teléfono móvil</label></div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="telefono_movil" id="telefono_movil">
                                        <option value="0">Sin teléfono</option>
                                        <?php foreach ($movilesDisponibles as $p) { ?>
                                            <?php if ($idTelefonoMovilActual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-12 col-md-3"><label class="form-label m-0" for="maletin">Maletín portátil</label></div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="maletin" id="maletin">
                                        <option value="0">Sin maletín</option>
                                        <?php foreach ($maletinesDisponibles as $p) { ?>
                                            <?php if ($idMaletinActual == $p->id) { ?>
                                                <option value="<?php echo $p->id; ?>" selected>
                                            <?php } else { ?>
                                                <option value="<?php echo $p->id; ?>">
                                            <?php } ?>
                                                <?php echo $p->codigo . " - " . $p->modelo; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-primary" type="submit" name="guardarEquipo">Guardar cambios</button>
                        <a class="btn btn-danger" href="trabajador_detalle.php?id=<?php echo $trabajador->id; ?>">Cancelar</a>
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