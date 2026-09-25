<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Editar Usuario'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="flex-grow-1 p-4" style="min-width:0;">

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

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <a href="usuarios.php">Usuarios</a> &gt; <strong>Editar Usuario</strong>
                    </small>
                </div>

                <h2 class="mb-3">Editar Usuario</h2>

                <?php if (!$usuario) { ?>
                    <div class="alert alert-danger">Usuario no encontrado.</div>
                    <a href="usuarios.php" class="btn btn-secondary">Volver</a>

                <?php } else { ?>

                    <form action="usuarios.php?action=editar_guardar" method="post">
                        <input type="hidden" name="id" value="<?php echo $usuario->id; ?>">

                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos Usuario</div>

                            <div class="p-3">

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="nombre">Nombre</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="text" name="nombre" id="nombre"
                                            value="<?php echo $usuario->nombre; ?>" required>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="email">Email</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="email" name="email" id="email"
                                            value="<?php echo $usuario->email; ?>" required>
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="pass">Nueva Contraseña</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="password" placeholder="Déjalo vacío si no quieres cambiarla." name="pass" id="pass">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label m-0" for="departamento">Departamento</label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="text" name="departamento" id="departamento"
                                            value="<?php echo $usuario->departamento; ?>" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Permisos</div>

                            <div class="p-3">
                                <div class="form-check mb-2">
                                    <?php if ($usuario->rol == 'admin') { ?>
                                        <input class="form-check-input" type="radio" name="rol" id="rol_admin" value="admin" required checked>
                                    <?php } else { ?>
                                        <input class="form-check-input" type="radio" name="rol" id="rol_admin" value="admin" required>
                                    <?php } ?>
                                    <label class="form-check-label" for="rol_admin">Admin</label>
                                </div>

                                <div class="form-check">
                                    <?php if ($usuario->rol == 'user') { ?>
                                        <input class="form-check-input" type="radio" name="rol" id="rol_user" value="user" required checked>
                                    <?php } else { ?>
                                        <input class="form-check-input" type="radio" name="rol" id="rol_user" value="user" required>
                                    <?php } ?>
                                    <label class="form-check-label" for="rol_user">User</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                            <a class="btn btn-danger" href="usuarios.php">Cancelar</a>
                        </div>

                    </form>

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