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

    <?php $tituloPagina = 'Alta Usuario'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="flex-grow-1 p-4" style="min-width:0;">

                <?php if (isset($errores) && isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (isset($ok) && $ok) { ?>
                    <div class="alert alert-success">Usuario creado correctamente.</div>
                <?php } ?>

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <a href="usuarios.php">Usuarios</a> &gt; <strong>Alta Usuario</strong>
                    </small>
                </div>

                <h2 class="mb-3">Alta Usuario</h2>

                <form action="usuarios.php?action=alta" method="post">

                    <div class="border rounded mb-4">
                        <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos Usuario</div>

                        <div class="p-3">

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="nombre">Nombre</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="nombre" id="nombre" required>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="email">Email</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="email" name="email" id="email" required>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="pass">Contraseña</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="password" name="pass" id="pass" required>
                                </div>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-2">
                                    <label class="form-label m-0" for="departamento">Departamento</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input class="form-control" type="text" name="departamento" id="departamento" required>
                                </div>
                            </div>

                        </div>

                        <div class="border rounded mb-4">
                            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Permisos</div>

                            <div class="p-3">
                                <div class="row g-3">

                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-3">
                                            <input class="form-check-input" type="radio" name="rol" id="rol_admin" value="admin" required>
                                            <label class="form-check-label" for="rol_admin" style="min-width:120px;">Admin</label>
                                            <span class="text-muted">Permisos Totales</span>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check d-flex align-items-center gap-3">
                                            <input class="form-check-input" type="radio" name="rol" id="rol_user" value="user" required>
                                            <label class="form-check-label" for="rol_user" style="min-width:120px;">User</label>
                                            <span class="text-muted">Solo lectura</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button class="btn btn-primary" type="submit" name="guardarUsuario">Añadir Usuario</button>
                            <a class="btn btn-danger" href="usuarios.php">Cancelar</a>
                        </div>

                </form>

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