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

  <?php $tituloPagina = 'Stock';
  include_once 'menu.php'; ?>

  <!-- CUERPO -->
  <main class="container-fluid flex-grow-1 d-flex p-0">
    <div class="d-flex flex-grow-1 w-100">
      <?php include_once 'sidebar.php'; ?>


      <!-- CONTENIDO -->
      <section class="flex-grow-1 p-4" style="min-width:0;">

        <div class="mb-2">
          <small class="text-muted">
            <a href="index.php">Inicio</a> &gt; <strong>Stock</strong>
          </small>
        </div>

        <h2 class="mb-3">Stock</h2>

        <?php
        if (!isset($buscar)) {
          $buscar = '';
        }
        if (!isset($filtroTipo)) {
          $filtroTipo = '';
        }
        if (!isset($orden)) {
          $orden = '';
        }
        if (!isset($productos)) {
          $productos = array();
        }
        if (!isset($pagina)) {
          $pagina = 1;
        }
        if (!isset($totalPaginas)) {
          $totalPaginas = 1;
        }
        if (!isset($resumenTop)) {
          $resumenTop = array();
        }
        ?>

        <!-- Barra filtros -->
        <form class="d-flex flex-wrap gap-3 align-items-center mb-4" method="get" action="stock.php">
          <input class="form-control" style="width:240px" type="text" name="buscar" placeholder="Buscar"
            value="<?php echo $buscar; ?>">

          <select class="form-select" style="width:180px" name="tipo">
            <option value="">Tipo</option>
            <?php
            $tipos = array(
              'Ordenador',
              'Monitor',
              'Impresora Tickets',
              'Impresoras Multifuncion',
              'Teclado',
              'Raton',
              'HUB USB',
              'Cajon Portamonedas',
              'Lector Codigo',
              'Lector Billete',
              'Telefono Fijo',
              'Telefono Movil',
              'Numero Movil',
              'Router',
              'Camaras 360',
              'Camaras Fija',
              'Licencia',
              'Datafono',
              'Pinpad'
            );

            foreach ($tipos as $t) {
              if ($filtroTipo == $t) {
                echo '<option value="' . $t . '" selected>' . $t . '</option>';
              } else {
                echo '<option value="' . $t . '">' . $t . '</option>';
              }
            }
            ?>
          </select>

          <select class="form-select" style="width:180px" name="orden">
            <option value="">Ordenar por...</option>
            <?php if ($orden == 'codigo_asc') { ?>
              <option value="codigo_asc" selected>Código (A-Z)</option>
            <?php } else { ?>
              <option value="codigo_asc">Código (A-Z)</option>
            <?php } ?>
            <?php if ($orden == 'codigo_desc') { ?>
              <option value="codigo_desc" selected>Código (Z-A)</option>
            <?php } else { ?>
              <option value="codigo_desc">Código (Z-A)</option>
            <?php } ?>
            <?php if ($orden == 'estado_asc') { ?>
              <option value="estado_asc" selected>Estado (A-Z)</option>
            <?php } else { ?>
              <option value="estado_asc">Estado (A-Z)</option>
            <?php } ?>
            <?php if ($orden == 'estado_desc') { ?>
              <option value="estado_desc" selected>Estado (Z-A)</option>
            <?php } else { ?>
              <option value="estado_desc">Estado (Z-A)</option>
            <?php } ?>
          </select>

          <select class="form-select" style="width:180px" name="condicion">
            <option value="">Condición...</option>
            <?php if (isset($condiciones)) { ?>
              <?php foreach ($condiciones as $valor => $etiqueta) { ?>
                <?php if (isset($filtroCondicion) && $filtroCondicion == $valor) { ?>
                  <option value="<?php echo $valor; ?>" selected><?php echo $etiqueta; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                <?php } ?>
              <?php } ?>
            <?php } ?>
          </select>

          <button class="btn btn-primary" type="submit">Filtrar</button>
          <a class="btn btn-secondary" href="stock.php">Limpiar</a>
          <!-- BOTONES EXPORTAR -->
          <a href="../controller/exportar.php?tipo=stock&formato=pdf" target="_blank" class="btn btn-danger btn-sm">Exportar PDF</a>
          <a href="../controller/exportar.php?tipo=stock&formato=excel" class="btn btn-success btn-sm">Exportar Excel</a>
        </form>

        <!-- Resumen top -->
        <div class="d-flex flex-wrap gap-3 mb-4">

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/portatil.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Portatiles</div>
              <?php
              $totalPortatiles = 0;
              $dispPortatiles = 0;
              if (isset($resumenTop['Portatiles'])) {
                if (isset($resumenTop['Portatiles']['total'])) {
                  $totalPortatiles = $resumenTop['Portatiles']['total'];
                }
                if (isset($resumenTop['Portatiles']['disponibles'])) {
                  $dispPortatiles = $resumenTop['Portatiles']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalPortatiles; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispPortatiles; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/monitor.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Monitor</div>
              <?php
              $totalMonitor = 0;
              $dispMonitor = 0;
              if (isset($resumenTop['Monitor'])) {
                if (isset($resumenTop['Monitor']['total'])) {
                  $totalMonitor = $resumenTop['Monitor']['total'];
                }
                if (isset($resumenTop['Monitor']['disponibles'])) {
                  $dispMonitor = $resumenTop['Monitor']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalMonitor; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispMonitor; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/impresora.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Impresora</div>
              <?php
              $totalImpresora = 0;
              $dispImpresora = 0;
              if (isset($resumenTop['Impresora'])) {
                if (isset($resumenTop['Impresora']['total'])) {
                  $totalImpresora = $resumenTop['Impresora']['total'];
                }
                if (isset($resumenTop['Impresora']['disponibles'])) {
                  $dispImpresora = $resumenTop['Impresora']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalImpresora; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispImpresora; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/cajonportamonedas.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Cajón Portamonedas</div>
              <?php
              $totalCajon = 0;
              $dispCajon = 0;
              if (isset($resumenTop['Cajon Portamonedas'])) {
                if (isset($resumenTop['Cajon Portamonedas']['total'])) {
                  $totalCajon = $resumenTop['Cajon Portamonedas']['total'];
                }
                if (isset($resumenTop['Cajon Portamonedas']['disponibles'])) {
                  $dispCajon = $resumenTop['Cajon Portamonedas']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalCajon; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispCajon; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/ratonTeclado.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Perifericos</div>
              <?php
              $totalPerif = 0;
              $dispPerif = 0;
              if (isset($resumenTop['Perifericos'])) {
                if (isset($resumenTop['Perifericos']['total'])) {
                  $totalPerif = $resumenTop['Perifericos']['total'];
                }
                if (isset($resumenTop['Perifericos']['disponibles'])) {
                  $dispPerif = $resumenTop['Perifericos']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalPerif; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispPerif; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/lector2.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Lectores</div>
              <?php
              $totalLectores = 0;
              $dispLectores = 0;
              if (isset($resumenTop['Lectores'])) {
                if (isset($resumenTop['Lectores']['total'])) {
                  $totalLectores = $resumenTop['Lectores']['total'];
                }
                if (isset($resumenTop['Lectores']['disponibles'])) {
                  $dispLectores = $resumenTop['Lectores']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalLectores; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispLectores; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/telefono.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Telefono Fijo</div>
              <?php
              $totalTelFijo = 0;
              $dispTelFijo = 0;
              if (isset($resumenTop['Telefono Fijo'])) {
                if (isset($resumenTop['Telefono Fijo']['total'])) {
                  $totalTelFijo = $resumenTop['Telefono Fijo']['total'];
                }
                if (isset($resumenTop['Telefono Fijo']['disponibles'])) {
                  $dispTelFijo = $resumenTop['Telefono Fijo']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalTelFijo; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispTelFijo; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/movil.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Telefono Movil</div>
              <?php
              $totalTelMovil = 0;
              $dispTelMovil = 0;
              if (isset($resumenTop['Telefono Movil'])) {
                if (isset($resumenTop['Telefono Movil']['total'])) {
                  $totalTelMovil = $resumenTop['Telefono Movil']['total'];
                }
                if (isset($resumenTop['Telefono Movil']['disponibles'])) {
                  $dispTelMovil = $resumenTop['Telefono Movil']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalTelMovil; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispTelMovil; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/numerom.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Numeros Movil</div>
              <?php
              $totalMovil = 0;
              $dispMovil = 0;
              if (isset($resumenTop['Movil'])) {
                if (isset($resumenTop['Movil']['total'])) {
                  $totalMovil = $resumenTop['Movil']['total'];
                }
                if (isset($resumenTop['Movil']['disponibles'])) {
                  $dispMovil = $resumenTop['Movil']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalMovil; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispMovil; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/router.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Routers</div>
              <?php
              $totalRouter = 0;
              $dispRouter = 0;
              if (isset($resumenTop['Router'])) {
                if (isset($resumenTop['Router']['total'])) {
                  $totalRouter = $resumenTop['Router']['total'];
                }
                if (isset($resumenTop['Router']['disponibles'])) {
                  $dispRouter = $resumenTop['Router']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalRouter; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispRouter; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/camara.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Camaras</div>
              <?php
              $totalCamaras = 0;
              $dispCamaras = 0;
              if (isset($resumenTop['Camaras'])) {
                if (isset($resumenTop['Camaras']['total'])) {
                  $totalCamaras = $resumenTop['Camaras']['total'];
                }
                if (isset($resumenTop['Camaras']['disponibles'])) {
                  $dispCamaras = $resumenTop['Camaras']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalCamaras; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispCamaras; ?></strong></div>
            </div>
          </div>

          <div class="bg-light rounded p-3 d-flex align-items-center gap-3" style="min-width:240px;">
            <img src="../view/img/iconos/licencia.svg" alt="" style="width:42px;height:42px;">
            <div>
              <div class="fw-semibold">Licencias</div>
              <?php
              $totalLic = 0;
              $dispLic = 0;
              if (isset($resumenTop['Licencias'])) {
                if (isset($resumenTop['Licencias']['total'])) {
                  $totalLic = $resumenTop['Licencias']['total'];
                }
                if (isset($resumenTop['Licencias']['disponibles'])) {
                  $dispLic = $resumenTop['Licencias']['disponibles'];
                }
              }
              ?>
              <div class="small">Total <strong><?php echo $totalLic; ?></strong></div>
              <div class="small">Disponible <strong class="text-success"><?php echo $dispLic; ?></strong></div>
            </div>
          </div>

        </div>

        <!-- Cards productos -->
        <div class="row g-3">
          <?php foreach ($productos as $p) {

            $idProducto = 0;
            if (isset($p->id)) {
              $idProducto = $p->id;
            }

            $link = 'stock.php?action=ver&id=' . $idProducto;

            $asignado = false;
            if (isset($p->destino_tipo) && $p->destino_tipo != '' && isset($p->destino_id) && $p->destino_id != '') {
              $asignado = true;
            }

            if ($asignado && $p->destino_tipo == 'envio') {
              // Material en un envio en curso hacia una tienda: mostrar su estado real.
              $envioEstado = '';
              if (isset($p->envio_estado)) { $envioEstado = $p->envio_estado; }
              if ($envioEstado == 'preparando') {
                $estadoTexto = 'Preparando envío';
                $badgeClass = 'bg-secondary';
              } else {
                $estadoTexto = 'Enviado';
                $badgeClass = 'bg-primary';
              }
              $ubicacion = 'En envío a tienda';
            } else if ($asignado && $p->destino_tipo == 'devolucion') {
              $estadoTexto = 'En devolución';
              $badgeClass = 'bg-warning text-dark';
              $ubicacion = 'En devolución a oficina';
            } else if ($asignado && $p->destino_tipo == 'recepcion') {
              $estadoTexto = 'En recepción';
              $badgeClass = 'bg-warning text-dark';
              $ubicacion = 'En recepción';
            } else if ($asignado && $p->destino_tipo == 'pack') {
              $estadoTexto = 'En pack';
              $badgeClass = 'bg-primary';
              $ubicacion = 'En pack de apertura';
            } else if ($asignado) {
              $estadoTexto = 'Asignado';
              $badgeClass = 'bg-success';
              $ubicacion = $p->destino_tipo;
            } else {
              $estadoTexto = 'Disponible';
              $badgeClass = 'bg-info';
              $ubicacion = 'No Ubicado';
            }

            $categoriaNombre = '';
            if (isset($p->categoria_nombre)) {
              $categoriaNombre = $p->categoria_nombre;
            }

            $subtipo = '';
            if (isset($p->subtipo)) {
              $subtipo = $p->subtipo;
            }

            $codigo = '';
            if (isset($p->codigo)) {
              $codigo = $p->codigo;
            }

            $modelo = '';
            if (isset($p->modelo)) {
              $modelo = $p->modelo;
            }

            // Determinar icono según la categoría del producto (fiable y granular).
            $base = "../view/img/iconos/";
            $icono = $base . "soporte.svg"; // por defecto (categoría no reconocida)

            if ($categoriaNombre == 'Ordenador') {
              $icono = $base . "portatil.svg";
            } elseif ($categoriaNombre == 'Monitor') {
              $icono = $base . "monitor.svg";
            } elseif ($categoriaNombre == 'Impresora Tickets' || $categoriaNombre == 'Impresoras Multifuncion') {
              $icono = $base . "impresora.svg";
            } elseif ($categoriaNombre == 'Lector Codigo') {
              $icono = $base . "lector2.svg";
            } elseif ($categoriaNombre == 'Lector Billete') {
              $icono = $base . "lectorBillete.svg";
            } elseif ($categoriaNombre == 'Router') {
              $icono = $base . "router.svg";
            } elseif ($categoriaNombre == 'Camaras 360' || $categoriaNombre == 'Camaras Fija') {
              $icono = $base . "camara.svg";
            } elseif ($categoriaNombre == 'Datafono' || $categoriaNombre == 'Pinpad' || $categoriaNombre == 'TPV') {
              $icono = $base . "tpv.svg";
            } elseif ($categoriaNombre == 'Telefono Fijo') {
              $icono = $base . "telefono.svg";
            } elseif ($categoriaNombre == 'Telefono Movil') {
              $icono = $base . "movil.svg";
            } elseif ($categoriaNombre == 'Numero Movil') {
              $icono = $base . "numerom.svg";
            } elseif ($categoriaNombre == 'Cajon Portamonedas') {
              $icono = $base . "cajonportamonedas.svg";
            } elseif ($categoriaNombre == 'Raton' || $categoriaNombre == 'Teclado' || $categoriaNombre == 'HUB USB') {
              $icono = $base . "ratonTeclado.svg";
            } elseif ($categoriaNombre == 'Licencia') {
              $icono = $base . "licencia.svg";
            }
          ?>
            <div class="col-12 col-md-6 col-lg-4">
              <a href="<?php echo $link; ?>" class="text-decoration-none text-dark">
                <div class="border rounded p-3 h-100">

                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-3">
                      <img src="<?php echo $icono; ?>" alt="" style="width:40px;height:40px;">
                      <div class="fw-semibold"><?php echo $codigo; ?></div>
                    </div>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $estadoTexto; ?></span>
                  </div>

                  <div class="fw-semibold"><?php echo $ubicacion; ?></div>
                  <div>Tipo: <?php echo $subtipo; ?></div>
                  <div>Modelo: <?php echo $modelo; ?></div>

                  <?php
                    $cond = '';
                    if (isset($p->estado)) { $cond = $p->estado; }
                    $condLabel = $cond;
                    if (isset($condiciones[$cond])) { $condLabel = $condiciones[$cond]; }
                    $condClass = 'bg-secondary';
                    if ($cond == 'nuevo') { $condClass = 'bg-success'; }
                    if ($cond == 'reacondicionado') { $condClass = 'bg-info text-dark'; }
                    if ($cond == 'averiado') { $condClass = 'bg-warning text-dark'; }
                    if ($cond == 'en_garantia') { $condClass = 'bg-primary'; }
                    if ($cond == 'en_reparacion') { $condClass = 'bg-info text-dark'; }
                    if ($cond == 'para_piezas') { $condClass = 'bg-dark'; }
                    if ($cond == 'desechado') { $condClass = 'bg-danger'; }
                  ?>
                  <div class="mt-1">Condición: <span class="badge <?php echo $condClass; ?>"><?php echo $condLabel; ?></span></div>

                </div>
              </a>
            </div>
          <?php } ?>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center my-4">
          <nav>
            <ul class="pagination flex-wrap justify-content-center">
              <?php
              $qBase = "buscar=" . $buscar . "&tipo=" . $filtroTipo . "&orden=" . $orden;
              if (isset($filtroCondicion) && $filtroCondicion != '') {
                $qBase = $qBase . "&condicion=" . $filtroCondicion;
              }

              // Ventana deslizante: primera, actual +-2 y ultima
              $ventana = 2;
              $desde = $pagina - $ventana;
              if ($desde < 1) {
                $desde = 1;
              }
              $hasta = $pagina + $ventana;
              if ($hasta > $totalPaginas) {
                $hasta = $totalPaginas;
              }
              ?>

              <?php if ($pagina <= 1) { ?>
                <li class="page-item disabled">
              <?php } else { ?>
                <li class="page-item">
              <?php } ?>
                <a class="page-link" href="stock.php?<?php echo $qBase; ?>&pagina=<?php echo $pagina - 1; ?>">Anterior</a>
              </li>

              <?php if ($desde > 1) { ?>
                <li class="page-item">
                  <a class="page-link" href="stock.php?<?php echo $qBase; ?>&pagina=1">1</a>
                </li>
                <?php if ($desde > 2) { ?>
                  <li class="page-item disabled"><span class="page-link">…</span></li>
                <?php } ?>
              <?php } ?>

              <?php for ($i = $desde; $i <= $hasta; $i++) { ?>
                <?php if ($i == $pagina) { ?>
                  <li class="page-item active">
                <?php } else { ?>
                  <li class="page-item">
                <?php } ?>
                  <a class="page-link" href="stock.php?<?php echo $qBase; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php } ?>

              <?php if ($hasta < $totalPaginas) { ?>
                <?php if ($hasta < $totalPaginas - 1) { ?>
                  <li class="page-item disabled"><span class="page-link">…</span></li>
                <?php } ?>
                <li class="page-item">
                  <a class="page-link" href="stock.php?<?php echo $qBase; ?>&pagina=<?php echo $totalPaginas; ?>"><?php echo $totalPaginas; ?></a>
                </li>
              <?php } ?>

              <?php if ($pagina >= $totalPaginas) { ?>
                <li class="page-item disabled">
              <?php } else { ?>
                <li class="page-item">
              <?php } ?>
                <a class="page-link" href="stock.php?<?php echo $qBase; ?>&pagina=<?php echo $pagina + 1; ?>">Siguiente</a>
              </li>
            </ul>
          </nav>
        </div>

        <?php if ($_SESSION['rol'] == 'admin') { ?>
          <div class="d-flex justify-content-center">
            <a href="stock.php?action=alta&cat=ordenador" class="btn btn-primary">Alta Producto</a>
          </div>
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