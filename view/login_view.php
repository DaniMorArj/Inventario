<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Login</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css"><!-- Bootstrap para el diseño web -->
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <header class="bg-black py-2">
        <div class="container text-center">
            <img src="../view/img/logo.png" alt="InventarioApp" class="img-fluid" style="max-height:80px;">
        </div>
    </header>

    <!-- Contenedor -->
    <main class="container my-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">

                <div class="p-4 bg-light border text-center">
                    <form method="post" action="login.php">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <?php if (isset($error)) { ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php } ?>

                        <button type="submit" class="btn btn-primary w-100">
                            Entrar
                        </button>

                        <div class="mt-3">
                            <a href="recuperar_password.php">Recuperar contraseña</a>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white py-2">
        <div class="container text-center small">
            Gestión de inventario. Todos los derechos reservados <br>
            Dirección: Calle Ejemplo, 1 · 00000 Ciudad <br>
            Teléfono: +34 600 000 000
        </div>
    </footer>

</body>

</html>