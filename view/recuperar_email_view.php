<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">   
    <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
    <title>Recuperar contraseña</title>
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

                    <h4 class="mb-3">Recuperar contraseña</h4>
                    <p class="text-muted small mb-4">
                        Introduce tu email y te enviaremos un enlace para restablecer tu contraseña.
                    </p>

                    <?php if (isset($error) && $error != '') { ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <?php if (isset($ok) && $ok != '') { ?>
                        <div class="alert alert-success"><?php echo $ok; ?></div>
                    <?php } ?>

                    <?php if (!isset($ok) || $ok == '') { ?>
                        <form method="post" action="recuperar_password.php">
                            <div class="mb-3 text-start">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
                        </form>
                    <?php } ?>

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