# InventarioApp

Aplicación web de **gestión de inventario informático** (PHP MVC + MariaDB) para el control
del material informático de una cadena de tiendas: alta de stock con ficha técnica, asignación
de material a tiendas y trabajadores, cierre de tiendas con recepción de material, packs de
apertura, etiquetas de código de barras y exportaciones PDF/Excel.

> Los datos incluidos en este repositorio son **ficticios (demo)**. Usuario de demo:
> `admin@demo.local` / `Admin1234`.

## Stack

- **PHP 8.2** + **Apache** · **MariaDB**
- PHPMailer (correo SMTP), FPDF (PDF), JsBarcode (Code128), Bootstrap 5
- Arquitectura **MVC** sin framework (`controller/`, `model/`, `view/`)

## Puesta en marcha (Docker)

```bash
docker compose up --build
```

- App: http://localhost:8080
- La base de datos carga `inventario.sql` (esquema + datos de demo) automáticamente.
- Acceso: `admin@demo.local` / `Admin1234`.

## Estructura

```
login.php / index.php      Punto de entrada
controller/                Controladores
model/                     Modelos (acceso a datos con PDO)
view/                      Vistas + css/js/img
lib/fpdf/                  FPDF
inventario.sql          Esquema + datos de demo
Dockerfile · docker-compose.yml
Docs/                      Documentación (arquitectura, operaciones, despliegue...)
```

## Funcionalidades

- **Login** con bcrypt y control de sesiones y roles (admin/user).
- **Dashboard** con tarjetas de resumen (stock, tiendas, centros, packs, recepciones).
- **Tiendas**: CRUD, asignación de material por slots, cierre/reapertura.
- **Trabajadores** (Oficina/Almacén/Serigrafía): CRUD, asignación de equipo, baja lógica.
- **Stock**: alta con ficha técnica dinámica, asignación/liberación, etiquetas Code128.
- **Recepciones**: material pendiente de recibir tras cerrar una tienda.
- **Packs de apertura**: preparación del material para tiendas que abren, con albarán PDF.
- **Usuarios / Configuración** (admin), **buscador global** y **exportaciones** PDF/Excel.

## Configuración

Toda la configuración va por **variables de entorno** (ver `.env.example`): conexión a la BD
y SMTP. Sin secretos en el repositorio.

## Documentación

Más detalle en la carpeta [`Docs/`](Docs/): arquitectura, operaciones, despliegue y bitácora.
