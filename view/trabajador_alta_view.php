<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">   
  <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
  <title>Alta Trabajador</title>
  <link rel="stylesheet" href="../view/css/bootstrap.min.css">
  <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Alta Trabajador'; include_once 'menu.php'; ?>

  <!-- CUERPO -->
  <main class="container-fluid flex-grow-1 d-flex p-0">
    <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>

            <section class="col-12 col-lg-10 p-4">

        <!-- MIGAS DE PAN -->
        <div class="mb-2">
          <small class="text-muted"><a href="../controller/index.php">Inicio</a> &gt; <a href="../controller/<?php echo $tipo; ?>.php"><?php echo $tipo; ?></a> &gt; <strong>Alta trabajador</strong></small>
        </div>

        <h2 class="mb-3">Alta Trabajador</h2>

        <?php if (isset($ok) && $ok) { ?>
          <div class="alert alert-success">Trabajador creado correctamente.</div>
        <?php } ?>

        <?php if (isset($errores) && count($errores) > 0) { ?>
          <div class="alert alert-danger">
            <?php foreach ($errores as $e) { ?>
              <div><?php echo $e; ?></div>
            <?php } ?>
          </div>
        <?php } ?>

        <form method="post" action="trabajador.php?action=alta&tipo=<?php echo $tipo; ?>">

          <!-- CENTRO -->
          <div class="border rounded mb-4">
            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Centro</div>
            <div class="p-3">
              <div class="row align-items-center">
                <div class="col-12 col-md-3">
                  <label class="form-label m-0" for="id_centro">Centro</label>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="id_centro" id="id_centro" required>
                    <option value="0">Selecciona</option>
                    <?php foreach ($centros as $cc) { ?>
                      <option value="<?php echo (int)$cc->id; ?>" <?php echo ((int)($idCentro ?? 0) === (int)$cc->id) ? 'selected' : ''; ?>>
                        <?php echo $cc->nombre; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- DATOS TRABAJADOR -->
          <div class="border rounded mb-4">
            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos del trabajador</div>
            <div class="p-3">

              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">Nombre</label>
                  <input class="form-control" type="text" name="nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Email</label>
                  <input class="form-control" type="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Departamento</label>
                  <select class="form-select" name="id_departamento">
                    <option value="0">Selecciona</option>
                    <?php foreach ($departamentos as $d) { ?>
                      <option value="<?php echo (int)$d->id; ?>" <?php echo ((int)($_POST['id_departamento'] ?? 0) === (int)$d->id) ? 'selected' : ''; ?>>
                        <?php echo $d->nombre; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Cargo</label>
                  <input class="form-control" type="text" name="cargo" value="<?php echo $_POST['cargo'] ?? ''; ?>">
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Número Móvil (SIM) (opcional)</label>
                  <select class="form-select" name="id_producto_numero_movil">
                    <option value="0">No tiene</option>
                    <?php foreach ($numerosMovilDisponibles as $p) { ?>
                      <?php
                      $labelSim = $p->codigo;
                      if (isset($p->numero) && $p->numero != '') {
                          $labelSim = $p->numero;
                      }
                      if (isset($p->tarifa) && $p->tarifa != '') {
                          $labelSim .= ' - ' . $p->tarifa;
                      }
                      ?>
                      <?php if (isset($_POST['id_producto_numero_movil']) && $_POST['id_producto_numero_movil'] == $p->id) { ?>
                        <option value="<?php echo $p->id; ?>" selected><?php echo $labelSim; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $p->id; ?>"><?php echo $labelSim; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>

              </div>

            </div>
          </div>

          <!-- EQUIPO -->
          <div class="border rounded mb-4">
            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Equipo</div>
            <div class="p-3">

              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">Equipo 1</label>
                  <select class="form-select" name="equipo1">
                    <option value="0">No tiene</option>
                    <?php foreach ($equiposDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['equipo1'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>

                  <label class="form-label mt-2">AnyDesk Equipo 1</label>
                  <input class="form-control" type="text" name="anydesk1" value="<?php echo $_POST['anydesk1'] ?? ''; ?>">
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Equipo 2</label>
                  <select class="form-select" name="equipo2">
                    <option value="0">No tiene</option>
                    <?php foreach ($equiposDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['equipo2'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>

                  <label class="form-label mt-2">AnyDesk Equipo 2</label>
                  <input class="form-control" type="text" name="anydesk2" value="<?php echo $_POST['anydesk2'] ?? ''; ?>">
                </div>
              </div>

            </div>
          </div>

          <!-- PERIFÉRICOS -->
          <div class="border rounded mb-4">
            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Periféricos</div>
            <div class="p-3">

              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <label class="form-label">Monitor 1</label>
                  <select class="form-select" name="monitor1">
                    <option value="0">No tiene</option>
                    <?php foreach ($monitoresDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['monitor1'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Monitor 2 (opcional)</label>
                  <select class="form-select" name="monitor2">
                    <option value="0">No tiene</option>
                    <?php foreach ($monitoresDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['monitor2'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Teclado</label>
                  <select class="form-select" name="teclado">
                    <option value="0">No tiene</option>
                    <?php foreach ($tecladosDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['teclado'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Ratón</label>
                  <select class="form-select" name="raton">
                    <option value="0">No tiene</option>
                    <?php foreach ($ratonesDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['raton'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Teléfono móvil (terminal) (opcional)</label>
                  <select class="form-select" name="telefono_movil">
                    <option value="0">No tiene</option>
                    <?php foreach ($movilesDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['telefono_movil'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Maletín portátil (opcional)</label>
                  <select class="form-select" name="maletin">
                    <option value="0">No tiene</option>
                    <?php foreach ($maletinesDisponibles as $p) { ?>
                      <option value="<?php echo (int)$p->id; ?>" <?php echo ((int)($_POST['maletin'] ?? 0) === (int)$p->id) ? 'selected' : ''; ?>>
                        <?php echo $p->codigo . " - " . $p->modelo; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>

              </div>

            </div>
          </div>

          <!-- OBSERVACIONES -->
          <div class="border rounded mb-4">
            <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Observaciones</div>
            <div class="p-3">
              <textarea class="form-control" name="observaciones" rows="4"><?php echo $_POST['observaciones'] ?? ''; ?></textarea>
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <a class="btn btn-secondary" href="<?php echo ($tipo === 'oficina') ? 'oficina.php' : (($tipo === 'almacen') ? 'almacen.php' : 'serigrafia.php'); ?>">Atrás</a>
            <button class="btn btn-primary" type="submit" name="guardarTrabajador">Guardar</button>
          </div>

        </form>

      </section>
    </div>
  </main>

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