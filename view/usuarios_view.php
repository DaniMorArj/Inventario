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

                <div class="mb-2">
                    <small class="text-muted">
                        <a href="index.php">Inicio</a> &gt; <strong>Usuarios</strong>
                    </small>
                </div>

                <h2 class="mb-3">Usuarios</h2>

                <?php
                $qBuscar = '';
                if (isset($_GET['buscar'])) {
                    $qBuscar = $_GET['buscar'];
                }

                $qRol = '';
                if (isset($_GET['rol'])) {
                    $qRol = $_GET['rol'];
                }
                ?>

                <form method="get" action="usuarios.php" class="mb-3">
                    <div class="d-flex flex-column flex-lg-row gap-2 align-items-lg-center justify-content-between">

                        <div class="d-flex flex-column flex-md-row gap-2">
                            <input type="text" class="form-control" name="buscar" placeholder="Buscar..."
                                value="<?php echo $qBuscar; ?>">

                            <select name="rol" class="form-select">
                                <option value="">Todos</option>
                                <?php if ($qRol == 'admin') { ?>
                                    <option value="admin" selected>Admin</option>
                                <?php } else { ?>
                                    <option value="admin">Admin</option>
                                <?php } ?>
                                <?php if ($qRol == 'responsable') { ?>
                                    <option value="responsable" selected>Responsable</option>
                                <?php } else { ?>
                                    <option value="responsable">Responsable</option>
                                <?php } ?>
                                <?php if ($qRol == 'user') { ?>
                                    <option value="user" selected>User</option>
                                <?php } else { ?>
                                    <option value="user">User</option>
                                <?php } ?>
                            </select>

                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            <a href="usuarios.php" class="btn btn-secondary">Limpiar</a>
                        </div>

                        <a class="btn btn-primary" href="usuarios.php?action=alta">Alta Usuario</a>
                    </div>
                </form>

                <div class="border rounded overflow-hidden bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Departamento</th>
                                    <th>Rol</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!isset($usuarios) || $usuarios == '') { ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No hay usuarios para mostrar.</td>
                                    </tr>
                                <?php } else { ?>
                                    <?php foreach ($usuarios as $u) { ?>
                                        <tr>
                                            <td><?php echo $u->nombre; ?></td>
                                            <td><?php echo $u->departamento; ?></td>
                                            <td><?php echo $u->rol; ?></td>
                                            <td class="text-end">
                                                <a class="btn btn-sm btn-primary" href="usuarios.php?action=editar&id=<?php echo $u->id; ?>">Editar</a>
                                                <a class="btn btn-sm btn-danger" href="usuarios.php?action=eliminar&id=<?php echo $u->id; ?>">Borrar</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($totalPaginas > 1) { ?>
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center mb-0">

                            <?php if ($pagina <= 1) { ?>
                                <li class="page-item disabled">
                            <?php } else { ?>
                                <li class="page-item">
                            <?php } ?>
                                <a class="page-link" href="usuarios.php?pagina=<?php echo $pagina - 1; ?>&buscar=<?php echo $qBuscar; ?>&rol=<?php echo $qRol; ?>">Anterior</a>
                            </li>

                            <?php for ($p = 1; $p <= $totalPaginas; $p++) { ?>
                                <?php if ($p == $pagina) { ?>
                                    <li class="page-item active">
                                <?php } else { ?>
                                    <li class="page-item">
                                <?php } ?>
                                    <a class="page-link" href="usuarios.php?pagina=<?php echo $p; ?>&buscar=<?php echo $qBuscar; ?>&rol=<?php echo $qRol; ?>">
                                        <?php echo $p; ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($pagina >= $totalPaginas) { ?>
                                <li class="page-item disabled">
                            <?php } else { ?>
                                <li class="page-item">
                            <?php } ?>
                                <a class="page-link" href="usuarios.php?pagina=<?php echo $pagina + 1; ?>&buscar=<?php echo $qBuscar; ?>&rol=<?php echo $qRol; ?>">Siguiente</a>
                            </li>

                        </ul>
                    </nav>
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