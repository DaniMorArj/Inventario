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

    <?php $tituloPagina = 'Usuarios'; include_once 'menu.php'; ?>

    <!-- CUERPO -->
    <main class="container-fluid flex-grow-1 d-flex p-0">
        <div class="row flex-grow-1 g-0 w-100">
            <?php include_once 'sidebar.php'; ?>


            <!-- CONTENIDO -->
            <section class="col-12 col-lg-10 p-4">

                <?php if (isset($errores) && isset($errores) && count($errores) > 0) { ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $e) { ?>
                            <div><?php echo $e; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <a href="usuarios.php">Usuarios</a> &gt; <strong>Eliminar</strong>
                    </small>
                </div>

                <h2 class="mb-3 text-danger">Eliminar Usuario</h2>

                <div class="alert alert-warning">
                    <p>
                        ¿Estás seguro de que deseas eliminar al usuario:
                        <strong><?php echo $usuario->nombre; ?></strong>
                        (<?php echo $usuario->email; ?>)?
                    </p>
                    <p class="mb-0">Esta acción no se puede deshacer.</p>
                </div>

                <form method="post" action="usuarios.php?action=eliminar_confirmado">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-danger">
                            Sí, eliminar
                        </button>

                        <a href="usuarios.php" class="btn btn-secondary">
                            Cancelar
                        </a>
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