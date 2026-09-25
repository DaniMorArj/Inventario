<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">   
  <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
  <title>Eliminar Trabajador</title>
  <link rel="stylesheet" href="../view/css/bootstrap.min.css">
</head>

<body class="bg-light">

  <div class="container py-5">

    <div class="card shadow-sm">
      <div class="card-body text-center">

        <h3 class="text-danger mb-4">
          ¿Estás seguro que deseas eliminar este trabajador?
        </h3>

        <p class="mb-4">
          <strong><?php echo $trabajador->nombre; ?></strong><br>
          Esta acción eliminará al trabajador y liberará todo el equipo asignado.
        </p>

        <form method="post">
          <button type="submit" name="confirmar_baja" class="btn btn-danger me-3">
            Sí, eliminar
          </button>

          <a href="trabajador_detalle.php?id=<?php echo $trabajador->id; ?>" class="btn btn-secondary">
            Cancelar
          </a>
        </form>

        <?php if (isset($error)) { ?>
          <div class="alert alert-danger mt-4">
            <?php echo $error; ?>
          </div>
        <?php } ?>

      </div>
    </div>

  </div>

  <script src="../view/js/bootstrap.bundle.min.js"></script>
</body>

</html>