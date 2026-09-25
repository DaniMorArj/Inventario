# Bitácora

Histórico cronológico de decisiones y avances. Lo más reciente arriba.

## 2026-09-15 — Envíos oficina→tienda (Fase 2, emparejados con Devoluciones)

Segundo tramo del ciclo de reposición. Motivación del usuario: al cambiar un equipo en un
hueco de la tienda, el antiguo se perdía a "disponible" sin control; quiere **mandar primero
el nuevo con seguimiento de estado** y, cuando llega, **devolver el antiguo**.

- Nueva tabla `envio` (item, tienda destino, `slot_destino`, motivo, estado
  **preparando/enviado/llegado/cancelado**, fechas, `id_devolucion` para el emparejamiento).
- **El hueco se decide al llegar**, no al crear: `Envio::marcarLlegado($slot, $devolverAntiguo,
  $motivo)`. Si el hueco ya está ocupado y NO se marca devolver → devuelve `'ocupado'` y no
  coloca (el controlador avisa). Si se marca devolver → el antiguo pasa a **Devoluciones**
  (reutiliza `Devolucion::solicitar`, que ahora devuelve el id) y el nuevo entra en el hueco.
- Mientras está en curso, el producto queda ocupado con `destino_tipo='envio'` (ni disponible
  ni reasignable). Cancelar lo libera.
- Se gestiona desde la **ficha de la tienda** (secciones "Envíos a esta tienda" + "Devolver
  material") y desde el **panel Envíos** (sidebar + contador). Lista de envíos en un parcial
  reutilizado por ambos: `view/envio_lista_parcial.php`.
- Ficheros: `model/Envio.php`, `controller/envio.php`, `view/envio_view.php`,
  `view/envio_lista_parcial.php`, sección en `view/tienda_detalle_view.php`, carga de datos en
  `controller/tienda.php`, entrada + contador en `view/sidebar.php`, icono
  `view/img/iconos/envio.svg`, tabla en `inventario.sql`.
- Validado en local (TIENDA DEMO): crear envío (OR0000000002 → preparando, producto ocupado) →
  enviado → llegar a equipo1 **sin** devolver con el hueco ocupado por DEMO-ORD-A → abortó
  ('ocupado', nada cambió) → llegar **con** devolver → el nuevo entra en equipo1, DEMO-ORD-A
  pasa a Devoluciones pendiente, `envio.id_devolucion` emparejado. Datos demo restaurados.
- Nota (gotcha detectado en pruebas): `AUTO_INCREMENT` no se reinicia al borrar filas; al
  probar hay que usar el id real del envío, no asumir que empieza en 1.

**Stock refleja el tránsito.** Antes, un producto en un envío salía en Stock como "Asignado"
sin más. Ahora el listado/búsqueda de Stock muestra el estado real: **Preparando envío** /
**Enviado** (con el estado del `envio`), **En devolución**, **En recepción** y **En pack**,
además de Asignado/Disponible. `StockAlta::listarProductos` hace `LEFT JOIN envio` (estado
preparando/enviado) y `view/stock_view.php` mapea el badge y la ubicación por `destino_tipo`.
Validado: OR0000000002 en envío se ve "Preparando envío" y, al marcar enviado, "Enviado".

**Ficha del producto coherente con el listado.** La ficha (`stock.php?action=ver`) mostraba
"Asignado" para todo y el botón "Ir al destino" no hacía nada para material en tránsito (el
`$linkDestino` sólo cubría tienda/oficina/almacen/serigrafia). Ahora `obtenerProductoConAsignacion`
también trae el estado del envío, la ficha usa el mismo mapeo de badges (Preparando envío /
Enviado / En devolución / En recepción / En pack) y muestra la **Condición**. El botón lleva al
panel correcto según el destino (**Ver en Envíos/Devoluciones/Recepciones/Packs**) y se oculta
**Liberar** en estados de tránsito (se gestionan desde su panel, no liberando a mano). Validado
con DEMO-CAM3-B (envío en preparación): ficha muestra "Preparando envío" + botón "Ver en Envíos".

**No asignar material no operativo desde la ficha.** En la ficha del producto, si la condición
no es asignable (averiado / en garantía / en reparación / para piezas / desechado) ya no se
muestra el botón **Asignar**: en su lugar un aviso ("No asignable por su condición (X). Cambia
la condición desde Editar…"). Refuerzo en el controlador: entrar directo a
`stock.php?action=asignar` con una condición no asignable corta antes de pintar el formulario
(el backend ya lo bloqueaba en el POST; ahora también en el GET). De paso, `stock_asignar_view.php`
solo pinta el formulario si llegó `$tiendas` (arregla el form vacío que se colaba en los cortes
"ya asignado"/"no encontrado"). Criterio único: `StockAlta::condicionAsignable`.

**Código de pack: `OR…` → `PACK…`.** El código de control de los packs empezaba por `OR`, igual
que los códigos de los ordenadores (`OR0000000001`), y se confundían. Cambiado a **PACK + 8
dígitos** (`PACK00000001`): `Pack::generarCodigo` (prefijo + `SUBSTRING(codigo,5)` + `LIKE 'PACK%'`),
texto de ayuda en `view/pack_view.php` y documentación. El pack existente se migró
(`OR000000001` → `PACK00000001`); las asignaciones referencian el pack por id, no por código, así
que no se rompe nada. Elegido `PACK` (frente a ORD/APE) por ser inconfundible.

**Menú: grupo "Transporte" con landing tipo dashboard + submenús animados.** Envíos,
Recepciones y Devoluciones se agrupan bajo **Transporte** (icono camión). El padre **navega** a
una nueva página `controller/transporte.php` / `view/transporte_view.php`: un panel estilo
dashboard con 3 tarjetas (Envíos en curso / Recepciones pendientes / Devoluciones pendientes) y
paneles con los ítems abiertos de cada flujo (Envíos solo admin). El padre muestra un **badge con
el total de pendientes**; cada hijo conserva su contador. Envíos sigue siendo solo admin.
Packs apertura se queda suelto (decisión del usuario).

Además, **todos los submenús del menú** (Stock, Tiendas, Oficina, Almacén, Serigrafía, Usuarios y
Transporte) se abren ahora con la **animación de deslizar** de Bootstrap al entrar en su sección:
el submenú de la sección activa se renderiza como `.collapse .submenu-anim` (cerrado) y un script
en `view/sidebar.php` lo abre. Los padres siguen navegando a su listado (decisión del usuario:
"navega + anima al entrar"); no hay toggle manual ni flechas/caret. Icono nuevo
`view/img/iconos/transporte.svg`.

La animación se dispara **solo al ENTRAR en la sección desde fuera**, no en cada carga: se guarda
la sección activa en `sessionStorage` y, si navegas DENTRO de la misma sección (otra categoría de
Stock, o saltar entre Envíos/Recepciones/Devoluciones), el submenú se muestra al instante
(`classList.add('show')` síncrono, antes del paint) sin volver a animar. Esto corrige el
parpadeo/"recarga" del submenú que se veía al pulsar cualquier hijo.

## 2026-09-16 — Fase 3: compra directa a tienda

Cierra el ciclo de reposición. Caso de uso: material comprado que llega **directo a la tienda**
sin pasar por oficina; se quiere registrar igual, aunque no lleve código pegado.

- En la **ficha de la tienda**, nueva sección "Registrar material comprado (directo)": categoría,
  modelo, subtipo, condición (solo asignables: nuevo/usado/reacondicionado), código **opcional** y
  hueco destino. Crea el producto y lo **asigna al hueco en un solo paso** (sin pasar por stock
  disponible).
- **Código opcional**: si se deja vacío se autogenera **según la categoría**
  (`StockAlta::generarCodigoPorCategoria`): prefijo propio por categoría + correlativo
  (Ordenador `OR`+10 díg. continuando los existentes; el resto `MONI`/`IMPT`/`LECC`… + 6 díg.;
  respaldo `CD` si la categoría no tiene prefijo). Si se escribe un código, debe ser único.
  Esquema de prefijos provisional (pruebas), se ajustará más adelante.
- **Hueco**: los huecos con nombre no deben estar ocupados (se rechaza con aviso); "Material
  adicional" usa un slot único `cd_<idProducto>`.
- Ficheros: acción `compra_directa` en `controller/tienda.php` (+ carga de categorías y
  condiciones asignables en la vista), sección en `view/tienda_detalle_view.php`,
  `StockAlta::generarCodigoCompraDirecta`.
- Validado en local (TIENDA DEMO): alta con código vacío → `CD00000001` creado y asignado
  (hueco adicional); rechazos correctos por hueco ocupado, código duplicado y datos faltantes,
  sin crear productos basura. Datos de prueba limpiados.

Con esto, el ciclo completo de material está cubierto: **Compra directa / Envíos (oficina→tienda)
/ Recepciones (cierre) / Devoluciones (avería)**, todo agrupado en el menú "Transporte".

**Retoques posteriores (ficha de tienda).** Las tres secciones de gestión de material de la ficha
—Envíos a esta tienda, Devolver material a oficina, Registrar material comprado— son ahora
**contraíbles** (cabecera clicable con Bootstrap collapse + caret que gira; abiertas por defecto).
Y el código autogenerado de la compra directa pasó de `CD…` genérico a **prefijo por categoría**
(ver arriba).

## 2026-09-15 — Devoluciones (avería) + fix de asignación + paginación

**Paginación de Stock.** El listado pintaba las 112 páginas enteras y se salía del
margen. Reescrita con **ventana deslizante** (primera · actual ±2 · última con puntos
suspensivos) y `flex-wrap`. Además ahora arrastra el filtro de condición al paginar.
Solo `view/stock_view.php`.

**Fix crítico de asignación (regresión del refactor de condición).** `model/TiendaAlta.php`
seguía con la semántica antigua: las consultas de "disponibles" filtraban
`estado='disponible'` (que ya no existe: `estado` es la condición) → los desplegables del
detalle de tienda salían **vacíos**; y `asignarProducto`/`liberarAsignacion` hacían
`SET estado='asignado'/'disponible'`, **corrompiendo la condición** en cada asignación.
Corregido: disponibilidad = sin fila en `asignacion` **y** condición asignable
(nuevo/usado/reacondicionado); eliminadas las escrituras a `estado`. Verificado: 631
ordenadores vuelven a aparecer como disponibles; un ítem "en garantía" no aparece.

**Condiciones nuevas.** Añadidas `en_garantia` ("En garantía") y `en_reparacion`
("En reparación") a `StockAlta::condiciones()` (8 en total). No asignables. Badges de
color en el listado de Stock. Cubren el desenlace de una avería (garantía → reparación →
piezas → desechado).

**Devoluciones (Fase 1 del ciclo de reposición).** Nuevo flujo hermano de Recepciones,
pero **por ítem suelto**: cuando una tienda devuelve material (normalmente averiado) a
oficina.
- Nueva tabla `devolucion` (item, tienda origen, slot, motivo, estado
  pendiente/recibido/incidencia, `condicion_final`, `id_envio` para la fase 2).
- Desde el **detalle de tienda** (sección "Devolver material a oficina"), se marca un
  material: sale del slot y su asignación se repunta a `destino_tipo='devolucion'`
  (en tránsito, no cuenta como disponible).
- Panel **Devoluciones** (sidebar + contador): **Recibir** (libera a stock y fija la
  condición de desenlace elegida) o **Incidencia** (sigue en tránsito).
- Ficheros: `model/Devolucion.php`, `controller/devolucion.php`, `view/devolucion_view.php`,
  acción `devolver` en `controller/tienda.php`, sección en `view/tienda_detalle_view.php`,
  entrada + contador en `view/sidebar.php`, icono `view/img/iconos/devolucion.svg`,
  tabla en `inventario.sql`.
- Validado en local: devuelto DEMO-ORD-A (equipo1, "pantalla rota") → sale de la tienda y
  aparece pendiente; recibido con "En garantía" → producto queda `en_garantia`, asignación
  liberada, contador a 0. (Datos demo restaurados tras la prueba.)

Es la primera de tres fases del ciclo de reposición acordado con el usuario:
**Devoluciones (hecho) → Envíos oficina→tienda emparejados → Compra directa a tienda.**

## 2026-09-08 — Recepciones: tiendas plegables

En el panel de Recepciones, cada tienda (lote) es ahora **plegable** (Bootstrap collapse):
por defecto se muestra solo la cabecera (tienda + contadores) y al pulsar se despliega su
material pendiente. Útil cuando hay varias tiendas por recepcionar. El botón "Marcar todo
recibido" lleva `event.stopPropagation()` para no plegar al pulsarlo. Caret indicador que
rota. Cambios solo en `view/recepcion_view.php`.

## 2026-09-08 — Dashboard: cards de Packs y Recepciones

Añadidas dos tarjetas al dashboard: **Packs de apertura** (packs en curso:
preparacion/completo/enviado; solo admin) y **Recepciones** (material pendiente de recibir;
todos). Nuevos métodos `Dashboard::getPacksPendientes()` y `getRecepcionesPendientes()`,
datos en `controller/index.php`, tarjetas en `view/dashboard_view.php`. Verificado: packs=0,
recepciones=6, sin errores.

## 2026-09-14 — Condición / ciclo de vida del material

Se separa la **condición** del material de su **disponibilidad** (esta última ya se deriva
de las asignaciones). El campo `producto.estado` — que estaba libre (se ponía "Disponible"
y solo se usaba para ordenar) — pasa a guardar la **condición**: `nuevo`, `usado`,
`reacondicionado`, `averiado`, `para_piezas`, `desechado`.

- `StockAlta::condiciones()` (catálogo clave→etiqueta) y `condicionAsignable()` (solo
  nuevo/usado/reacondicionado son asignables).
- Alta y edición de stock con **selector de condición**; listado con **badge** y **filtro**
  por condición (`?condicion=`).
- La **asignación bloquea** material averiado/para piezas/desechado.
- Migración: los productos existentes (`estado='disponible'`) → `usado`.
- Verificado 5/5 (alta, edición, filtro, badge, bloqueo de asignación).
- Pendiente/idea: integrar con Recepciones (marcar condición al recibir de una tienda
  cerrada) y baja lógica de "desechado". Nuevo formato de código de producto (en diseño).

## 2026-09-08 — Beta-test integral de la aplicación

Batería completa por HTTP (positivo + negativo), todo PASS salvo un hallazgo:
- **Permisos**: sin sesión, los 12 controladores redirigen a login; con rol `user`, las
  zonas admin (usuarios, configuración, packs, stock alta/asignar/liberar) redirigen a
  index; las permitidas (dashboard, tiendas, recepciones, buscador) devuelven 200.
- **Validaciones negativas**: alta con campos vacíos bloqueada; código de producto
  duplicado bloqueado; sociedad con tiendas y departamento con trabajadores no se pueden
  eliminar.
- **CRUD positivo end-to-end**: sociedades, departamentos, plantilla de packs, usuarios
  (alta/edición), productos (alta/edición), tiendas (alta/edición/cierre→recepción→
  recibir→reapertura), trabajadores (alta/edición/baja), packs (crear/añadir/cancelar).
- **Buscador y exportaciones**: buscador OK; export stock/trabajadores/tienda en PDF y
  Excel con content-type correcto; albarán PDF y etiqueta OK.

**Vista de bajas + reactivar:** en Oficina/Almacén/Serigrafía hay un conmutador
**Activos / Dados de baja** (`?ver=bajas`). La vista de bajas muestra la Fecha de baja y un
botón **Reactivar** (admin, `trabajador.php?action=reactivar`, `TrabajadorCrud::reactivar`
pone estado=ACTIVO y fecha_baja=NULL). Al abrir la ficha de un trabajador (activo o de baja)
se ve su histórico de equipo (`asignacion_historial`). `TrabajadorListado::listar/contar`
aceptan un parámetro `$estado` (por defecto ACTIVO). Verificado 6/6.

**Hallazgo → RESUELTO (baja lógica, opción A):** la baja de trabajador borraba el registro
(DELETE). Ahora `TrabajadorCrud::darDeBajaTotal` hace **baja lógica**: libera el equipo,
pone `estado='BAJA'` + `fecha_baja=NOW()` y conserva el registro (histórico). Los listados
`TrabajadorListado::contarTrabajadores/listarTrabajadores` filtran `estado='ACTIVO'`
(el buscador y el dashboard ya lo hacían). Verificado 7/7: registro conservado, BAJA con
fecha, equipo liberado, oculto de listados de activos.

## 2026-09-08 — Control total de productos y asignaciones

Auditoría automatizada completa (44/44 comprobaciones + verificación HTTP):
- **Mapeo de slots** correcto para las 20 categorías (cada una a su slot con nombre o,
  si no tiene sección, a material adicional).
- **Consistencia de datos**: sin asignaciones huérfanas, sin material 'tienda' en tiendas
  no activas, todos los slots visibles en el detalle, y disponibilidad cuadra
  (total = asignados + disponibles).
- **Ciclo asignar por categoría**: se creó una tienda temporal, se asignó un producto de
  cada categoría desde Stock y se verificó por HTTP que **los 19 aparecen** en el detalle
  (cada uno en su sección o en Material adicional). Limpieza posterior.
- Se detectó y corrigió que la categoría **Periférico** no tenía slot (no se podía asignar
  desde Stock): ahora mapea a `periferico` (material adicional).

## 2026-09-08 — Material asignado desde Stock invisible en el detalle de tienda

El material asignado desde Stock no aparecía en el detalle de la tienda (salía vacío):
la asignación de Stock y el detalle usan esquemas de slots distintos (`camara` vs
`camara_fija_N`, `lector_billete` vs `lector_billete1`...) y algunas categorías (Monitor,
Ratón, Teclado, HUB, Nº Móvil) no tienen hueco en el detalle. La sección "Material
adicional" de `view/tienda_detalle_view.php` ahora muestra **cualquier asignación cuyo
slot no sea uno de los huecos con nombre** (antes solo capturaba los `pack_*`), así todo
el material asignado a una tienda es visible.

**Refinamiento (mismo día):** `StockAlta::slotsPermitidosPorCategoria()` reescrito para
ofrecer los **slots con nombre** del detalle (equipo1/2, tickets1/2, multifuncion,
lector_codigo1/2, lector_billete1/2, cajon_portamonedas, telefono_fijo/movil, router_sos,
datafono, pinpad, camara360_1, camara_fija_1) con etiquetas legibles. Así el material
asignado desde Stock cae en su sección correspondiente. Solo las categorías sin hueco
propio (Monitor, Ratón, Teclado, HUB, Nº Móvil, Licencia) siguen en "Material adicional".
Datos existentes re-mapeados (p. ej. CAMF00000004 -> camara_fija_1, LB-001 -> lector_billete2).
También se corrigió que la categoría de teléfono se normaliza como `telefono fijo`/`telefono movil`
(antes el mapeo buscaba `telefono` y nunca casaba).

## 2026-09-08 — Asignar stock: solo a tiendas activas

Al asignar un producto desde Stock aparecían tiendas cerradas y se podía asignar material
a ellas. Ahora `StockAlta::listarTiendas()` solo devuelve tiendas `activa` (el desplegable
excluye cerradas y en apertura), y el guardado valida con `StockAlta::tiendaActiva()`:
si el destino no está activo, error "No se puede asignar material a una tienda cerrada o
en apertura" y no se asigna. Las tiendas en apertura se equipan vía packs.

## 2026-09-08 — Fix iconos del listado de Stock

Varios artículos mostraban el icono de portátil por defecto: los iconos se elegían por
`subtipo` con cadenas que no cuadraban con los datos (`Camaras 360` vs `Camara 360`,
`Pinpad` vs `PINPAD`) y sin respaldo por categoría (Lector Billete/Código con subtipo
vacío). `view/stock_view.php` ahora selecciona el icono **por categoría** (granular y
fiable) cubriendo todas, con `soporte.svg` como defecto neutro. Se cambiaron también los
iconos del sidebar de Packs (`pack.svg`) y Recepciones (`recepcionar.svg`).

## 2026-09-07 — Fix contadores del sidebar

Los badges de Recepciones/Packs desaparecían en Dashboard, Configuración y Buscador
(esos controladores usan modelos con conexión propia y no dejaban `$conexion` en scope,
así que el contador salía 0). `view/sidebar.php` ahora abre su propia conexión si no
existe, y los contadores son consistentes en toda la app.

## 2026-09-07 — Función nueva: Packs de apertura

Flujo inverso a Recepciones: al **abrir** una tienda, IT prepara un "pack de apertura"
con el material informático estándar. Da trazabilidad de qué recibió cada tienda al abrir.

**Diseño (decidido con el usuario):**
- Plantilla estándar (checklist) configurable; el pack valida completitud contra ella.
- La tienda se crea "en apertura" (nuevo estado `tienda.estado='en_apertura'`) y se le asocia el pack.
- Código de control **OR + 9 dígitos** generado automáticamente.
- Al abrir la tienda, el material del pack **se materializa** como inventario de la tienda.

**Implementación:**
- Tablas nuevas: `pack`, `pack_linea`, `pack_plantilla` (+ seed de la plantilla estándar).
  Enum de `tienda.estado` ampliado con `en_apertura`.
- `model/Pack.php`: código OR auto, reserva de stock (asignación `destino_tipo='pack'`,
  mismo enfoque que Recepción → no cuenta como disponible ni se reasigna), checklist de
  completitud, `abrirTienda()` (materializa), `cancelar()` (libera).
- `controller/pack.php` + `view/pack_view.php` (listado + detalle con checklist y selectores).
- Entrada "Packs apertura" en el sidebar (solo admin) con contador de packs en curso.
- Badge "En apertura" en el listado de Tiendas.

**Validado en local:** 16/16 comprobaciones (crear, código OR, checklist, reserva, no
doble-asignación, materializar al abrir, cancelar libera) + controlador/vista por HTTP + visual.

**Fix 2 (mismo día) — cancelar y duplicados:** cancelar un pack dejaba la tienda
huérfana en `en_apertura`, y crear otro pack con el mismo número duplicaba la tienda.
Ahora `Pack::cancelar()` hace **deshacer completo**: libera material y, si la tienda es
un placeholder (`en_apertura` sin otro pack vigente), elimina tienda y pack. Y el alta de
pack (`controller/pack.php`) **deduplica por número**: reutiliza una tienda en apertura
libre o da error si el número ya existe (tienda normal o con pack en curso).

**Fix 1 (mismo día):** al abrir la tienda, el material entraba con `slot='pack'` (todos
iguales) y el detalle de tienda, que indexa por slot y pinta huecos con nombre, salía
vacío. `Pack::materializarEnTienda()` ahora mapea cada producto a su slot real de la
tienda (equipo1/2, tickets1/2, lector_codigo1/2, etc.); el material sin slot propio
(Ratón, HUB USB) va a `pack_<id>` y se muestra en una sección **"Material adicional"**
del detalle de tienda. Datos ya materializados mal reparados con un remapeo puntual.

**Fase 2 (hecha, mismo día):**
- **Albarán PDF** del pack: `controller/albaran.php` (FPDF) — código OR, tienda destino,
  fecha, usuario, tabla de material y línea de firma. Botón en el detalle del pack.
- **Etiqueta Code128** del código OR: se reutiliza `controller/etiqueta.php`
  (`etiqueta.php?codigo=OR…`). Botón en el detalle del pack.
- **Editor de plantilla** en Configuración: pestaña "Plantilla packs" con CRUD de
  `pack_plantilla` (métodos nuevos en `model/Configuracion.php`, prepared statements).
  Editar la plantilla afecta directamente al checklist de los packs.

Nota: "Cable USB" no existe como categoría (excluido del checklist); la plantilla es
editable desde Configuración → Plantilla packs.

## 2026-09-02 — Containerización (Docker) + documentación

### Auditoría inicial (auditoría de seguridad y limpieza para publicación)

Se auditó el proyecto original (`Docs/InventarioApp(Local)/`, PHP MVC + MariaDB 10.4).
Bloqueantes para contenedor/Linux detectados:

- **Case-sensitivity de rutas**: carpetas en minúsculas pero referencias en mayúscula
  (`Controller/`, `../View/...`) que rompen en Linux. → normalizado todo a minúsculas.
- **Credenciales de BD hardcodeadas y duplicadas** en `InventarioDB.php` y en 8
  controladores (PDO inline con `root`/localhost). → centralizado y por variables de entorno.
- **Correo con `mail()` nativo** (no funciona en contenedor) y enlace de recuperación
  con ruta hardcodeada `/InventarioApp/`. → migrado a PHPMailer + SMTP M365 por env;
  enlace construido con `APP_BASE_URL`.
- **Inyección SQL** en `model/Usuario.php` (concatenación en el flujo de recuperación).
  → señalada; **aplazada** por decisión del usuario para una tanda posterior.

### Decisión de sistemas (Néstor)

Mantener el stack **PHP + MariaDB** (no reescribir a Postgres): impacto en la infra
despreciable. Condiciones para el despliegue en [despliegue.md](despliegue.md).

### Cambios aplicados

- App promovida de `Docs/InventarioApp(Local)/` a la **raíz del repositorio**.
- Casing de rutas normalizado a minúsculas (0 referencias en mayúscula).
- Conexión BD centralizada en `model/InventarioDB.php` leyendo `DB_*` de entorno (con
  fallback local); eliminadas las 8 conexiones PDO hardcodeadas de los controladores.
- Recuperación de contraseña con PHPMailer + SMTP M365 por variables de entorno.
- Nuevos ficheros: `Dockerfile` (php:8.2-apache + pdo_mysql + Composer/PHPMailer),
  `docker-compose.yml` (app + MariaDB 11, carga del dump, bind-mount de código),
  `.dockerignore`, `.gitignore`, `.env.example`, `index.php` (raíz), `CLAUDE.md`,
  `composer.json`.

### Validación en local (Docker)

- Build correcto tras añadir `unzip` (lo requiere Composer).
- BD: 15 tablas importadas del dump.
- `/` → login; login completo → dashboard con datos reales.
- Los 9 controladores principales responden **HTTP 200 sin errores PHP**.

### Documentación

Creada la suite de documentación en `Docs/`: README, arquitectura, operaciones,
despliegue y esta bitácora.

### Función nueva: recepción de material al cerrar tienda

**Problema:** al cerrar una tienda, el `DELETE` de asignaciones dejaba el material como
stock disponible al instante, aunque físicamente aún no había llegado a la oficina.

**Solución (flujo de recepción en 3 estados: pendiente / recibido / incidencia):**

- Nueva tabla `recepcion` (una línea por producto, agrupadas por `lote` de cierre) con
  estado, fechas, usuario y observaciones.
- Al cerrar (`controller/tienda.php`): `Recepcion::crearDesdeCierre()` crea las líneas
  pendientes y **repunta las asignaciones a `destino_tipo='recepcion'`** (el material
  sigue "ocupado"). `TiendaAlta::cerrarTienda()` ya no borra asignaciones.
- Panel nuevo `controller/recepcion.php` + `view/recepcion_view.php`, con entrada propia
  en el sidebar y contador de pendientes. La oficina marca cada ítem **Recibir**
  (libera la asignación → vuelve a stock disponible) o **Incidencia** (con observaciones;
  el material NO vuelve a disponible). Atajo "Marcar todo recibido" por cierre.
- El material en tránsito se muestra como "En recepción" en el listado de Stock.

Ventaja del enfoque (repuntar la asignación): el material en tránsito no cuenta como
disponible ni se puede reasignar **sin tocar ninguna consulta de stock existente**.

Validado en local: cerrada la Tienda 2 (9 items → pendientes), recibido 1 (pasa a
disponible), 1 incidencia (sigue ocupado); los disponibles solo suben con los recibidos.

### Pendiente

- Desplegar en un hosting con Dockerfile (Render u otro).
- Validar correo real por SMTP M365.
- Parametrizar el SQL de `model/Usuario.php` (nota: `model/Recepcion.php` ya usa
  prepared statements).
- Limpiar el duplicado `Docs/InventarioApp(Local)/`.
- Reapertura de tienda: no restaura asignaciones (comportamiento previo); si hay
  material en recepción, se gestiona desde el panel de Recepciones.
