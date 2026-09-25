<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="../view/css/bootstrap.min.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header class="bg-black py-2">
        <div class="container text-center">
            <img src="../view/img/logo.png" alt="InventarioApp" class="img-fluid" style="max-height:80px;">
        </div>
    </header>

    <main class="container my-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                <div class="p-4 bg-light border text-center">

                    <h4 class="mb-3">Nueva contraseña</h4>
                    <p class="text-muted small mb-4">
                        Introduce tu nueva contraseña. Debe tener al menos 6 caracteres.
                    </p>

                    <?php if (isset($error) && $error != '') { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <form method="post" action="recuperar_password.php">

                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="mb-3 text-start">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" name="nueva_pass" required>
                        </div>

                        <div class="mb-3 text-start">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-control" name="confirmar_pass" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Guardar nueva contraseña</button>

                    </form>

                    <div class="mt-3">
                        <a href="../login.php">Volver al login</a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="bg-black text-white py-2">
        <div class="container text-center small">
            Gestión de inventario. Todos los derechos reservados <br>
            Dirección: Calle Ejemplo, 1 · 00000 Ciudad <br>
            Teléfono: +34 600 000 000
        </div>
    </footer>

</body>
</html>