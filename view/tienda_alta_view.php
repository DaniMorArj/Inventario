<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Alta Tienda</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Alta Tienda'; include_once 'menu.php'; ?>

  <!-- CUERPO -->
  <main class="container-fluid flex-grow-1 d-flex p-0">
    <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>

            <section class="col-12 col-lg-10 p-4">

                <!-- MIGAS DE PAN -->
                <div class="mb-2">
                    <small class="text-muted">
                        <a href="../controller/index.php">Inicio</a> &gt; <a href="../controller/tienda.php">Tiendas</a> &gt; <strong>Alta Tienda</strong>
                    </small>
                </div>

                <h2 class="mb-3">Alta Tienda</h2>

                <?php if (!isset($errores)) {
                    $errores = [];
                } ?>
                <?php if (isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <form action="tienda.php?action=alta" method="post">

                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos Tienda</div>

                        <div class="p-3">

                            <!-- Nº Tienda -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="numero">Nº tienda</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="number" name="numero" id="numero"
                                        value="<?php echo isset($_POST['numero']) ? $_POST['numero'] : ''; ?>" required>
                                </div>
                            </div>

                            <!-- Nombre -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="nombre">Nombre</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="nombre" id="nombre"
                                        value="<?php echo isset($_POST['nombre']) ? $_POST['nombre'] : ''; ?>" required>
                                </div>
                            </div>

                            <!-- Sociedad -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="sociedad">Sociedad</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <select class="form-select" name="sociedad" id="sociedad" required>
                                        <option value="">Selecciona sociedad</option>
                                        <?php foreach ($sociedades as $s) { ?>
                                            <option value="<?php echo $s->id; ?>"
                                                <?php echo (isset($_POST['sociedad']) && $_POST['sociedad'] == $s->id) ? 'selected' : ''; ?>>
                                                <?php echo $s->nombre . " (" . $s->cif . ")"; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Móvil -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="movil">Móvil</label>
                                </div>
                                <div class="col-12 col-md-6">
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
                                                <?php if (isset($_POST['movil']) && $_POST['movil'] == $p->codigo) { ?>
                                                    <option value="<?php echo $p->codigo; ?>" selected><?php echo $labelMovil; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $p->codigo; ?>"><?php echo $labelMovil; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Fijo -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="fijo">Fijo</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="fijo" id="fijo"
                                        value="<?php echo isset($_POST['fijo']) ? $_POST['fijo'] : ''; ?>">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="email">Email</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="email" name="email" id="email"
                                        value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="observaciones">Observaciones</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <textarea class="form-control" name="observaciones" id="observaciones" rows="3"><?php echo isset($_POST['observaciones']) ? $_POST['observaciones'] : ''; ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-end gap-3">
                        <button class="btn btn-primary" type="submit" name="guardarTienda">Guardar</button>
                        <a class="btn btn-danger" href="tienda.php">Cancelar</a>
                    </div>

                </form>

            </section>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white py-2">
        <div class="container-fluid px-3 px-md-4">
            <div class="d-flex align-items-center gap-3">

                <!-- Logo pequeño -->
                <a href="index.php" class="text-decoration-none">
                    <img src="../view/img/logo.png" alt="InventarioApp" style="height:35px;">
                </a>

                <!-- Texto -->
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