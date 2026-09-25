<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">   
  <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
  <title>Stock</title>
  <link rel="stylesheet" href="../view/css/bootstrap.min.css">
  <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Alta Stock'; include_once 'menu.php'; ?>

  <!-- CUERPO -->
  <main class="container-fluid flex-grow-1 d-flex p-0">
    <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>

            <section class="col-12 col-lg-10 p-4">

        <!-- MIGAS DE PAN -->
        <div class="mb-2">
          <small class="text-muted">
              <a href="index.php">Inicio</a> &gt; <a href="stock.php">Stock</a> &gt; <strong>Alta</strong>
          </small>
        </div>

        <h2 class="mb-3">Alta Producto</h2>

        <?php if (!isset($errores)) {
          $errores = array();
        } ?>

        <?php if (isset($errores) && count($errores) > 0) { ?>
          <div class="alert alert-danger">
            <?php foreach ($errores as $e) { ?>
              <div><?php echo $e; ?></div>
            <?php } ?>
          </div>
          <a href="stock.php" class="btn btn-secondary">Volver</a>
        <?php } ?>

        <?php
        $tieneMulti = false;
        $tieneUna = false;

        if (isset($categoriasAlta) && count($categoriasAlta) > 1) {
          $tieneMulti = true;
        }

        if (isset($categoriasAlta) && count($categoriasAlta) == 1) {
          $tieneUna = true;
        }
        ?>

        <?php if (isset($categoriasAlta) && isset($categoriasAlta) && $categoriasAlta != '') { ?>

          <form method="post">
            <div class="border rounded mb-4">
              <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos del producto</div>

              <div class="p-3">

                <?php if ($tieneMulti) { ?>
                  <div class="row align-items-center mb-3">
                    <div class="col-12 col-md-3">
                      <label class="form-label m-0" for="id_categoria">Tipo</label>
                    </div>
                    <div class="col-12 col-md-6">
                      <select class="form-select" name="id_categoria" id="id_categoria" required onchange="this.form.submit()">
                        <option value="0">Selecciona</option>

                        <?php foreach ($categoriasAlta as $cc) { ?>
                          <?php
                          $selected = '';
                          if (isset($_POST['id_categoria']) && $_POST['id_categoria'] == $cc->id) {
                            $selected = 'selected';
                          }
                          ?>
                          <option value="<?php echo $cc->id; ?>" <?php echo $selected; ?>>
                            <?php echo $cc->nombre; ?>
                          </option>
                        <?php } ?>

                      </select>
                      <div class="form-text">Este submenú agrupa varias categorías reales.</div>
                    </div>
                  </div>
                <?php } ?>

                <?php if ($tieneUna) { ?>
                  <div class="alert alert-info">
                    Categoría: <strong><?php echo $categoriasAlta[0]->nombre; ?></strong>
                  </div>
                <?php } ?>

                <?php
                $valorCodigo = '';
                if (isset($_POST['codigo'])) {
                  $valorCodigo = $_POST['codigo'];
                }

                $valorModelo = '';
                if (isset($_POST['modelo'])) {
                  $valorModelo = $_POST['modelo'];
                }

                $valorSubtipo = '';
                if (isset($_POST['subtipo'])) {
                  $valorSubtipo = $_POST['subtipo'];
                }
                ?>

                <div class="row align-items-center mb-3">
                  <div class="col-12 col-md-3">
                    <label class="form-label m-0" for="codigo">Código</label>
                  </div>
                  <div class="col-12 col-md-6">
                    <input class="form-control" type="text" name="codigo" id="codigo"
                      value="<?php echo $valorCodigo; ?>" required>
                  </div>
                </div>

                <div class="row align-items-center mb-3">
                  <div class="col-12 col-md-3">
                    <label class="form-label m-0" for="modelo">Modelo</label>
                  </div>
                  <div class="col-12 col-md-6">
                    <input class="form-control" type="text" name="modelo" id="modelo"
                      value="<?php echo $valorModelo; ?>" required>
                  </div>
                </div>

                <div class="row align-items-center mb-3">
                  <div class="col-12 col-md-3">
                    <label class="form-label m-0" for="subtipo">Subtipo</label>
                  </div>
                  <div class="col-12 col-md-6">
                    <input class="form-control" type="text" name="subtipo" id="subtipo"
                      value="<?php echo $valorSubtipo; ?>" required>
                    <div class="form-text">Ej: portatil, sobremesa, tickets, multifuncion, teclado, raton, etc.</div>
                  </div>
                </div>

                <div class="row align-items-center mb-3">
                  <div class="col-12 col-md-3">
                    <label class="form-label m-0" for="estado">Condición</label>
                  </div>
                  <div class="col-12 col-md-6">
                    <select class="form-select" name="estado" id="estado" required>
                      <?php if (isset($condiciones)) { ?>
                        <?php foreach ($condiciones as $valor => $etiqueta) { ?>
                          <?php if ($valor == 'nuevo') { ?>
                            <option value="<?php echo $valor; ?>" selected><?php echo $etiqueta; ?></option>
                          <?php } else { ?>
                            <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                          <?php } ?>
                        <?php } ?>
                      <?php } ?>
                    </select>
                    <div class="form-text">Nuevo, usado, reacondicionado, averiado, para piezas o desechado.</div>
                  </div>
                </div>

              </div>
            </div>

            <?php
            $idCatElegida = 0;

            if ($tieneUna) {
              $idCatElegida = $categoriasAlta[0]->id;
            }

            if ($tieneMulti) {
              if (isset($_POST['id_categoria'])) {
                $idCatElegida = $_POST['id_categoria'];
              }
            }
            ?>

            <?php if (isset($atributosCategoria) && $atributosCategoria != '') { ?>
              <div class="border rounded mb-4">
                <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Ficha técnica</div>
                <div class="p-3">

                  <?php foreach ($atributosCategoria as $a) { ?>

                    <?php
                    $val = '';
                    if (isset($_POST['attrs'][$a->id])) {
                      $val = $_POST['attrs'][$a->id];
                    }

                    $req = false;
                    if (isset($a->requerido) && $a->requerido == 1) {
                      $req = true;
                    }
                    ?>

                    <div class="row align-items-center mb-3">
                      <div class="col-12 col-md-3">
                        <label class="form-label m-0" for="attr_<?php echo $a->id; ?>">
                          <?php echo $a->nombre; ?>
                          <?php if (isset($a->unidad) && $a->unidad != '') { ?>
                            <span class="text-muted">(<?php echo $a->unidad; ?>)</span>
                          <?php } ?>
                          <?php if ($req) { ?>
                            <span class="text-danger">*</span>
                          <?php } ?>
                        </label>
                      </div>

                      <div class="col-12 col-md-6">
                        <?php if ($req) { ?>
                          <input class="form-control" type="text"
                            id="attr_<?php echo $a->id; ?>"
                            name="attrs[<?php echo $a->id; ?>]"
                            value="<?php echo $val; ?>"
                            required>
                        <?php } else { ?>
                          <input class="form-control" type="text"
                            id="attr_<?php echo $a->id; ?>"
                            name="attrs[<?php echo $a->id; ?>]"
                            value="<?php echo $val; ?>">
                        <?php } ?>
                      </div>
                    </div>

                  <?php } ?>

                  <div class="form-text">
                    Los campos de esta ficha dependen del tipo seleccionado.
                  </div>
                </div>
              </div>
            <?php } else { ?>

              <?php if ($tieneMulti && $idCatElegida <= 0) { ?>
                <div class="alert alert-warning">
                  Selecciona un <strong>Tipo</strong> para mostrar la ficha técnica.
                </div>
              <?php } ?>

            <?php } ?>

            <div class="d-flex justify-content-end gap-2">
              <button class="btn btn-primary" type="submit" name="guardarProducto">Guardar</button>
              <a class="btn btn-danger" href="stock.php">Cancelar</a>
            </div>
          </form>

        <?php } else { ?>

          <div class="alert alert-danger">
            Categoría no válida.
          </div>
          <a href="stock.php" class="btn btn-secondary">Volver</a>

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