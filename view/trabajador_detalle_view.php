<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">   
  <link rel="icon" type="image/x-icon" href="../view/img/iconos/faviconpmp.ico">
  <title>Detalle Trabajador</title>
  <link rel="stylesheet" href="../view/css/bootstrap.min.css">
  <link rel="stylesheet" href="../view/css/estilos.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php $tituloPagina = 'Detalle Trabajador'; include_once 'menu.php'; ?>

  <!-- CUERPO -->
  <main class="container-fluid flex-grow-1 d-flex p-0">
    <div class="d-flex flex-grow-1 w-100">
            <?php include_once 'sidebar.php'; ?>

            <section class="col-12 col-lg-10 p-4">

        <?php
        $dep = $trabajador->departamento_nombre ?? ($trabajador->departamento ?? '');
        ?>

        <!-- MIGAS DE PAN -->
        <div class="mb-2">
          <small class="text-muted">
            <a href="../controller/index.php">Inicio</a> &gt;<a href="../controller/<?php echo $trabajador->centro_tipo; ?>.php"><?php echo $trabajador->centro_tipo; ?></a> &gt; <strong>Detalle Trabajador</strong>
          </small>
        </div>

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
          <h2 class="mb-3"><?php echo $trabajador->nombre ?? ''; ?></h2>

          <div class="d-flex gap-2">
            <a class="btn btn-outline-primary"
              href="trabajador.php?action=editar&id=<?php echo ($trabajador->id ?? 0); ?>&tipo=<?php echo $trabajador->centro_tipo ?? 'oficina'; ?>">
              Editar
            </a>
            <a class="btn btn-outline-dark"
              href="trabajador.php?action=equipo&id=<?php echo ($trabajador->id ?? 0); ?>&tipo=<?php echo $trabajador->centro_tipo ?? 'oficina'; ?>">
              Equipo
            </a>
            <a class="btn btn-danger"
              href="trabajador.php?action=baja&id=<?php echo ($trabajador->id ?? 0); ?>&tipo=<?php echo $trabajador->centro_tipo ?? 'oficina'; ?>">
              Baja
            </a>
          </div>
        </div>

        <!-- DATOS -->
        <div class="border rounded mb-4">
          <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Datos del trabajador</div>
          <div class="p-3">
            <div><b>Email:</b> <?php echo $trabajador->email ?? ''; ?></div>
            <div><b>Centro:</b> <?php echo $trabajador->centro_nombre ?? ''; ?></div>
            <div><b>Departamento:</b> <?php echo $dep; ?></div>
            <div><b>Cargo:</b> <?php echo $trabajador->cargo ?? ''; ?></div>

            <div class="mt-2">
              <div><b>Número móvil (SIM):</b>
                <?php if (isset($trabajador->id_producto_numero_movil) && $trabajador->id_producto_numero_movil > 0) { ?>
                  <?php echo ($simNumero != '') ? $simNumero : 'Asignado'; ?>
                <?php } else { ?>
                  No tiene
                <?php } ?>
              </div>
            </div>
          </div>
        </div>

        <!-- ASIGNACIONES -->
        <div class="border rounded mb-4">
          <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Equipo y periféricos asignados</div>
          <div class="p-3">

            <?php if (!isset($asignaciones) || count($asignaciones) == 0) { ?>
              <div class="text-muted">Este trabajador no tiene productos asignados.</div>
            <?php } else { ?>
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>Código</th>
                      <th>Modelo</th>
                      <th>Categoría</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($asignaciones as $a) { ?>
                      <?php $cat = $a->categoria_nombre ?? ($a->categoria ?? ''); ?>
                      <tr>
                        <td><?php echo $a->codigo; ?></td>
                        <td><?php echo $a->modelo; ?></td>
                        <td><?php echo $cat; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } ?>

          </div>
        </div>

        <!-- OBSERVACIONES -->
        <div class="border rounded mb-4">
          <div class="bg-secondary bg-opacity-25 p-2 fw-semibold">Observaciones</div>
          <div class="p-3">
            <?php
            $obs = trim((string)($trabajador->observaciones ?? ''));
            echo $obs !== '' ? $obs : '<span class="text-muted">Sin observaciones.</span>';
            ?>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <?php
          $volver = ($trabajador->centro_tipo === 'oficina') ? 'oficina.php'
            : (($trabajador->centro_tipo === 'almacen') ? 'almacen.php' : 'serigrafia.php');
          ?>
          <a class="btn btn-secondary" href="<?php echo $volver; ?>">Atrás</a>

          <a class="btn btn-primary" href="trabajador.php?action=alta&tipo=<?php echo $trabajador->centro_tipo ?? 'oficina'; ?>">
            Alta trabajador
          </a>
        </div>

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