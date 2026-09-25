<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">   
  <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
  <title>Editar Producto</title>
  <link rel="stylesheet" href="../view/css/bootstrap.min.css">
  <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Editar Stock'; include_once 'menu.php'; ?>

  <!-- CUERPO -->
  <main class="container-fluid flex-grow-1 d-flex p-0">
    <div class="row flex-grow-1 g-0 w-100">
            <?php include_once 'sidebar.php'; ?>


      <!-- CONTENIDO -->
      <section class="col-12 col-lg-10 p-4">

        <div class="mb-2">
          <small class="text-muted">
              <a href="index.php">Inicio</a> &gt; <a href="stock.php">Stock</a> &gt; <strong>Editar</strong>
          </small>
        </div>

        <h2 class="mb-3">Editar Producto</h2>

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
        $valorCodigo = '';
        if (isset($_POST['codigo'])) {
          $valorCodigo = $_POST['codigo'];
        } else {
          if (isset($producto->codigo)) {
            $valorCodigo = $producto->codigo;
          }
        }

        $valorModelo = '';
        if (isset($_POST['modelo'])) {
          $valorModelo = $_POST['modelo'];
        } else {
          if (isset($producto->modelo)) {
            $valorModelo = $producto->modelo;
          }
        }

        $valorSubtipo = '';
        if (isset($_POST['subtipo'])) {
          $valorSubtipo = $_POST['subtipo'];
        } else {
          if (isset($producto->subtipo)) {
            $valorSubtipo = $producto->subtipo;
          }
        }
        ?>

        <form method="post">
          <div class="border rounded p-3 mb-4">
            <div class="fw-semibold mb-2">Datos base</div>

            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label">Código</label>
                <input class="form-control" name="codigo" value="<?php echo $valorCodigo; ?>" required>
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label">Modelo</label>
                <input class="form-control" name="modelo" value="<?php echo $valorModelo; ?>" required>
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label">Subtipo</label>
                <input class="form-control" name="subtipo" value="<?php echo $valorSubtipo; ?>" required>
              </div>

              <div class="col-12 col-md-4">
                <label class="form-label">Condición</label>
                <?php
                  $valorEstado = '';
                  if (isset($producto->estado)) { $valorEstado = $producto->estado; }
                ?>
                <select class="form-select" name="estado" required>
                  <?php if (isset($condiciones)) { ?>
                    <?php foreach ($condiciones as $valor => $etiqueta) { ?>
                      <?php if ($valor == $valorEstado) { ?>
                        <option value="<?php echo $valor; ?>" selected><?php echo $etiqueta; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div class="border rounded p-3 mb-4">
            <div class="fw-semibold mb-2">Ficha técnica</div>

            <?php if (!isset($atributosPermitidos)) {
              $atributosPermitidos = array();
            } ?>
            <?php if (!isset($atributosPermitidos) || $atributosPermitidos == '') { ?>
              <div class="text-muted">No hay atributos configurados para esta categoría.</div>
            <?php } else { ?>
              <div class="row g-3">
                <?php foreach ($atributosPermitidos as $att) { ?>
                  <?php
                  $idAtt = 0;
                  if (isset($att->id)) {
                    $idAtt = $att->id;
                  }

                  $key = 'att_' . $idAtt;

                  $valorDefault = '';
                  if (isset($valoresActuales[$idAtt])) {
                    $valorDefault = $valoresActuales[$idAtt];
                  }

                  $valor = $valorDefault;
                  if (isset($_POST[$key])) {
                    $valor = $_POST[$key];
                  }

                  $nombreAtt = '';
                  if (isset($att->nombre)) {
                    $nombreAtt = $att->nombre;
                  }

                  $unidadAtt = '';
                  if (isset($att->unidad)) {
                    $unidadAtt = $att->unidad;
                  }

                  $tipoAtt = '';
                  if (isset($att->tipo)) {
                    $tipoAtt = $att->tipo;
                  }
                  ?>

                  <div class="col-12 col-md-6">
                    <label class="form-label">
                      <?php echo $nombreAtt; ?>
                      <?php if (isset($unidadAtt) && $unidadAtt != '') { ?>
                        <span class="text-muted">(<?php echo $unidadAtt; ?>)</span>
                      <?php } ?>
                    </label>

                    <input class="form-control" name="<?php echo $key; ?>" value="<?php echo $valor; ?>">

                    <?php if (isset($tipoAtt) && $tipoAtt != '') { ?>
                      <div class="form-text"><?php echo $tipoAtt; ?></div>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>
          </div>

          <?php
          $idProducto = 0;
          if (isset($producto->id)) {
            $idProducto = $producto->id;
          }
          ?>

          <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-primary" type="submit" name="guardarEdicion">Guardar</button>
            <a class="btn btn-secondary" href="stock.php?action=ver&id=<?php echo $idProducto; ?>">Cancelar</a>
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