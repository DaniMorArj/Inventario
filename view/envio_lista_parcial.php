<?php
// Lista de envios con sus acciones por estado. Reutilizado por el panel global
// (view/envio_view.php) y por la ficha de la tienda (view/tienda_detalle_view.php).
// Espera: $envios (array de objetos), $slotsTienda (array valor=>etiqueta).
// Opcionales: $enviosMostrarTienda (bool, columna tienda), $envioVolver (id tienda para ?volver).
if (!isset($enviosMostrarTienda)) { $enviosMostrarTienda = false; }
if (!isset($envioVolver)) { $envioVolver = 0; }
$sufijoVolver = '';
if ($envioVolver > 0) { $sufijoVolver = '&volver=' . $envioVolver; }
?>

<?php if (isset($envios) && count($envios) > 0) { ?>
    <div class="border rounded table-responsive">
        <table class="table table-sm mb-0 align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Modelo</th>
                    <th>Categoría</th>
                    <?php if ($enviosMostrarTienda) { ?><th>Tienda destino</th><?php } ?>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($envios as $e) { ?>
                    <tr>
                        <td><?php echo $e->codigo; ?></td>
                        <td><?php echo $e->modelo; ?></td>
                        <td><?php if (isset($e->categoria)) { echo $e->categoria; } ?></td>
                        <?php if ($enviosMostrarTienda) { ?>
                            <td>
                                <?php if (isset($e->tienda_numero)) { echo $e->tienda_numero; } ?>
                                <?php if (isset($e->tienda_nombre) && $e->tienda_nombre != '') { ?>
                                    — <?php echo $e->tienda_nombre; ?>
                                <?php } ?>
                            </td>
                        <?php } ?>
                        <td>
                            <?php if (isset($e->motivo) && $e->motivo != '') { ?>
                                <?php echo $e->motivo; ?>
                            <?php } else { ?>
                                <span class="text-muted">—</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($e->estado == 'preparando') { ?>
                                <span class="badge bg-secondary">Preparando</span>
                            <?php } ?>
                            <?php if ($e->estado == 'enviado') { ?>
                                <span class="badge bg-primary">Enviado</span>
                                <?php if (isset($e->fecha_enviado) && $e->fecha_enviado != '') { ?>
                                    <div class="text-muted small"><?php echo $e->fecha_enviado; ?></div>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($e->estado == 'llegado') { ?>
                                <span class="badge bg-success">Llegado</span>
                                <?php if (isset($e->slot_destino) && $e->slot_destino != '') { ?>
                                    <span class="badge bg-light text-dark"><?php echo $e->slot_destino; ?></span>
                                <?php } ?>
                                <?php if (isset($e->fecha_llegado) && $e->fecha_llegado != '') { ?>
                                    <div class="text-muted small"><?php echo $e->fecha_llegado; ?></div>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($e->estado == 'cancelado') { ?>
                                <span class="badge bg-dark">Cancelado</span>
                            <?php } ?>
                        </td>
                        <td class="text-end">
                            <?php if ($e->estado == 'preparando') { ?>
                                <a href="envio.php?action=enviado&id=<?php echo $e->id; ?><?php echo $sufijoVolver; ?>"
                                   class="btn btn-primary btn-sm">Marcar enviado</a>
                                <a href="envio.php?action=cancelar&id=<?php echo $e->id; ?><?php echo $sufijoVolver; ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('¿Cancelar este envío? El material vuelve a stock disponible.');">Cancelar</a>
                            <?php } ?>

                            <?php if ($e->estado == 'enviado') { ?>
                                <button type="button" class="btn btn-success btn-sm"
                                        data-bs-toggle="collapse" data-bs-target="#lleg<?php echo $e->id; ?>">
                                    Marcar llegado
                                </button>
                                <a href="envio.php?action=cancelar&id=<?php echo $e->id; ?><?php echo $sufijoVolver; ?>"
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('¿Cancelar este envío? El material vuelve a stock disponible.');">Cancelar</a>
                                <div class="collapse mt-2 text-start" id="lleg<?php echo $e->id; ?>">
                                    <form method="post" action="envio.php?action=llegado">
                                        <input type="hidden" name="id" value="<?php echo $e->id; ?>">
                                        <?php if ($envioVolver > 0) { ?>
                                            <input type="hidden" name="volver" value="<?php echo $envioVolver; ?>">
                                        <?php } ?>
                                        <label class="form-label m-0 small">¿A qué hueco de la tienda va?</label>
                                        <select name="slot_destino" class="form-select form-select-sm mb-2">
                                            <?php foreach ($slotsTienda as $valor => $etiqueta) { ?>
                                                <option value="<?php echo $valor; ?>"><?php echo $etiqueta; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                   name="devolver" id="dev<?php echo $e->id; ?>">
                                            <label class="form-check-label small" for="dev<?php echo $e->id; ?>">
                                                Si el hueco ya tiene un equipo, devolverlo a oficina
                                            </label>
                                        </div>
                                        <textarea name="motivo_devolucion" class="form-control form-control-sm my-2"
                                                  rows="2" placeholder="Motivo de la devolución del antiguo (si procede)"></textarea>
                                        <button type="submit" class="btn btn-success btn-sm">Confirmar llegada</button>
                                    </form>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } else { ?>
    <div class="alert alert-secondary mb-0">No hay envíos <?php if (!isset($verTodos) || !$verTodos) { echo 'en curso'; } ?>.</div>
<?php } ?>
