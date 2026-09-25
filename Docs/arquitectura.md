# Arquitectura

## Stack

- **PHP 8.2** sobre **Apache** (imagen `php:8.2-apache`).
- **MariaDB 11** (el dump original venía de MariaDB 10.4, EOL).
- **PHPMailer** (vía Composer) para correo SMTP.
- Librerías embebidas: **FPDF** (`lib/fpdf`), **JsBarcode** y **Bootstrap 5** (`view/`).
- Sin framework ni router: cada controlador es un `.php` invocado directamente por Apache.

## Patrón MVC

```
index.php / login.php        Punto de entrada (raíz) → redirige a controller/index.php
recuperar_password.php       Entrada de recuperación de contraseña (raíz)
controller/                  Controladores (uno por área funcional)
model/                       Modelos: acceso a datos con PDO
  InventarioDB.php           Conexión centralizada (lee variables de entorno)
view/                        Vistas + css / js / img
  menu.php, sidebar.php      Includes compartidos por todas las vistas
lib/fpdf/                    FPDF (generación de PDF y etiquetas)
```

Flujo típico de una petición:

1. Apache sirve `controller/<area>.php`.
2. El controlador arranca sesión, comprueba `$_SESSION['usuario']` (si no, redirige a login).
3. Incluye `model/InventarioDB.php` y obtiene `$conexion = InventarioDB::connectDB()`.
4. Llama a métodos estáticos de los modelos para leer/escribir.
5. Incluye la vista correspondiente `view/<algo>_view.php`, que a su vez incluye
   `menu.php` y `sidebar.php`.

### Convenciones

- Carpetas y rutas **siempre en minúsculas** (`controller/model/view/lib`). Linux
  distingue mayúsculas: no introducir referencias tipo `../View/...`.
- Rutas relativas: desde vistas a controladores `../controller/...`; desde
  controladores a modelos `../model/...` y a vistas `../view/...`.
- Cada vista define `$tituloPagina` antes de incluir el menú.
- Visibilidad de menú admin: `$menuConfiguracion = ($rol == 'admin')`,
  `$menuUsuarios = ($rol == 'admin')`.
- Protección de acciones admin: `if ($_SESSION['rol'] != 'admin') { header('location:index.php'); exit; }`.

## Configuración por variables de entorno

Toda la configuración sensible se lee de entorno (ver `.env.example` en la raíz).
`InventarioDB` cae a valores locales de XAMPP si no hay `DB_*` definidas.

| Variable | Uso |
|---|---|
| `DB_HOST` `DB_PORT` `DB_NAME` `DB_USER` `DB_PASS` | Conexión a la base de datos |
| `APP_BASE_URL` | URL pública (enlaces en correos, correcto tras el proxy) |
| `SMTP_HOST` `SMTP_PORT` `SMTP_SECURE` `SMTP_USER` `SMTP_PASS` `SMTP_FROM` `SMTP_FROM_NAME` | SMTP |

## Modelo de datos

Base de datos `inventario`. Tablas principales:

| Tabla | Descripción |
|---|---|
| `producto` | Ítems de inventario: `codigo`, `modelo`, `id_categoria`, `subtipo`, y `estado` = **condición/ciclo de vida** (nuevo/usado/reacondicionado/averiado/en_garantia/en_reparacion/para_piezas/desechado). La *disponibilidad* NO es este campo: se deriva de la presencia en `asignacion`. Solo son asignables nuevo/usado/reacondicionado. |
| `categoria` | Categorías (Ordenador, Monitor, Impresora, Periférico, TPV, Licencia…) |
| `atributo`, `categoria_atributo`, `producto_atributo` | Ficha técnica dinámica por categoría |
| `asignacion` | Asignación de un producto a un destino: `id_producto`, `destino_tipo` (tienda/trabajador), `destino_id`, `slot` |
| `asignacion_historial` | Auditoría de asignaciones **de trabajadores**: acción (ASIGNAR/LIBERAR/CAMBIAR), producto anterior/nuevo, usuario, fecha |
| `recepcion` | Material pendiente de recepción en oficina tras cerrar una tienda: `lote`, `id_producto`, `id_tienda_origen`, `slot_origen`, `estado` (pendiente/recibido/incidencia), fechas y usuarios de cierre/recepción, observaciones |
| `devolucion` | Material que una tienda devuelve a oficina (normalmente avería), por ítem suelto: `id_producto`, `id_tienda_origen`, `slot_origen`, `motivo`, `estado` (pendiente/recibido/incidencia), fechas/usuarios de solicitud y recepción, `condicion_final` (desenlace al recibir) e `id_envio` (emparejamiento con el envío). Flujo hermano de `recepcion` pero item a item. |
| `envio` | Material que oficina envía a una tienda (reposición/sustitución), por ítem: `id_producto`, `id_tienda_destino`, `slot_destino`, `motivo`, `estado` (preparando/enviado/llegado/cancelado), fechas/usuarios, e `id_devolucion` (devolución emparejada del equipo antiguo). Flujo inverso de `devolucion`. |
| `pack` | Pack de apertura de una tienda: `codigo` (PACK…), `id_tienda`, `estado` (preparacion/completo/enviado/entregado/cancelado), fechas y usuario |
| `pack_linea` | Productos incluidos en un pack (`id_pack`, `id_producto`) |
| `pack_plantilla` | Checklist estándar del pack: `id_categoria`, `subtipo`, `cantidad`, `orden` |

`tienda.estado` es ENUM('activa','cerrada','en_apertura').
| `tienda` | Tiendas; `estado` ENUM('activa','cerrada'), datos de equipo (anydesk/pos/caja), red (ip_fija, vpn), operadora |
| `trabajador` | Trabajadores; centro, departamento, cargo, estado, fechas alta/baja |
| `usuario` | Usuarios de la app: `pass` bcrypt, `rol` ENUM('admin','user') |
| `sociedad` | Sociedades (nombre, cif) |
| `departamento`, `centro` | Catálogos organizativos |
| `password_reset_tokens` | Tokens de recuperación de contraseña |

### Concepto clave: disponibilidad de stock

La disponibilidad de un producto **no** se deriva de `producto.estado`, sino de la
**presencia de una fila en `asignacion`**:

- Producto **asignado** → tiene fila en `asignacion`.
- Producto **disponible** → no tiene fila en `asignacion`.

Así lo calculan el Dashboard (`model/Dashboard.php`) y el Stock
(`model/StockAlta.php`). Cualquier cambio en la lógica de disponibilidad debe tener
esto en cuenta.

## Áreas funcionales

- **Login / sesión**: bcrypt (`password_verify`), control por `$_SESSION`.
- **Dashboard**: tarjetas resumen y últimas asignaciones.
- **Tiendas**: CRUD, asignación de material por *slots*, exportación PDF/Excel, filtros
  y paginación. Al **cerrar** una tienda el material no se libera directamente: pasa a
  *recepción* (ver abajo).
- **Recepciones**: material de tiendas cerradas pendiente de recibir y comprobar en
  oficina. Estados pendiente/recibido/incidencia. Al recibir un ítem se libera y vuelve
  a stock disponible; con incidencia sigue ocupado. Mientras está en tránsito, su
  asignación queda con `destino_tipo='recepcion'`, por lo que no cuenta como disponible
  ni es reasignable. Modelo `model/Recepcion.php`.
- **Packs de apertura** (solo admin): material informático preparado para una tienda que
  va a abrir. La tienda se crea `en_apertura` y se le asocia un pack con código `PACK…`
  automático. Un checklist (`pack_plantilla`) valida que el pack lleve el material
  estándar. El material metido en el pack queda ocupado (`destino_tipo='pack'`); al
  **abrir** la tienda se materializa como su inventario (`→ destino_tipo='tienda'`) y la
  tienda pasa a `activa`. Cancelar un pack libera el material. Modelo `model/Pack.php`.
  Es el flujo inverso de Recepciones (salida al abrir / entrada al cerrar).
  El detalle del pack ofrece **albarán PDF** (`controller/albaran.php`, FPDF) y
  **etiqueta Code128** del código PACK (reutiliza `controller/etiqueta.php`). La plantilla
  (`pack_plantilla`) se edita desde **Configuración → Plantilla packs**.
- **Transporte** (panel): página resumen (estilo dashboard) de los tres flujos de movimiento de
  material —Envíos, Recepciones y Devoluciones— con tarjetas de pendientes y listas de lo abierto.
  `controller/transporte.php` / `view/transporte_view.php`. En el menú es el padre que agrupa esos
  tres apartados.
- **Envíos** (solo admin): material que oficina envía a una tienda (reposición/sustitución).
  Se crea `preparando` (el producto queda ocupado con `destino_tipo='envio'`), pasa a `enviado`
  y luego a `llegado`. **El hueco de la tienda se elige al llegar**: si ese hueco ya tenía un
  equipo antiguo, en el mismo paso se puede **devolver ese antiguo a oficina** (crea la
  devolución emparejada, `envio.id_devolucion`). Cancelar libera el material. Se gestiona desde
  la **ficha de la tienda** y desde el **panel Envíos**. Modelo `model/Envio.php`. Es el flujo
  inverso de Devoluciones.
- **Devoluciones**: material que una tienda devuelve a oficina (normalmente avería). Desde el
  detalle de tienda se marca un material como *devuelto a oficina*: sale del slot y queda en
  tránsito (`destino_tipo='devolucion'`), sin contar como disponible. En el panel Devoluciones se
  **recibe** (se libera a stock y se fija el desenlace: averiado / en garantía / en reparación /
  para piezas / desechado) o se marca **incidencia** (sigue en tránsito). Modelo `model/Devolucion.php`.
- **Compra directa** (en la ficha de tienda, solo admin): alta de material comprado que llega
  directo a la tienda (sin pasar por oficina). Crea el producto y lo asigna al hueco en un paso.
  El código es opcional; si se omite se autogenera (`CD…`, `StockAlta::generarCodigoCompraDirecta`).
- **Trabajadores** (Oficina / Almacén / Serigrafía): CRUD, asignación de equipo, baja.
- **Stock**: alta con ficha técnica dinámica, asignación/liberación, etiquetas
  Code128, exportación PDF/Excel.
- **Usuarios / Configuración** (solo admin): usuarios con roles; sociedades y departamentos.
- **Buscador global**: busca en `codigo`, `modelo` y atributo `numero`.
- **Recuperación de contraseña**: token + PHPMailer/SMTP M365.
