# InventarioApp — Documentación

Aplicación web de gestión de inventario informático. PHP MVC
(sin framework) + MariaDB/MySQL, servida con Apache.

> Versión de portfolio: datos ficticios, demo protegida con contraseña (ver `CLAUDE.md`).

## Índice de documentación

| Documento | Contenido |
|-----------|-----------|
| [arquitectura.md](arquitectura.md) | Estructura MVC, modelo de datos, cómo está montado por dentro |
| [operaciones.md](operaciones.md) | Arrancar, probar y mantener en local (Docker) |
| [despliegue.md](despliegue.md) | Cómo desplegarla |
| [bitacora.md](bitacora.md) | Histórico cronológico de decisiones y avances |

## Estado del proyecto (2026-09-02)

- Aplicación funcional (login, dashboard, tiendas, trabajadores, stock, usuarios,
  configuración, buscador, recuperación de contraseña).
- Containerizada (Docker), lista para desplegar en cualquier hosting con soporte de Dockerfile.
- Ver [bitacora.md](bitacora.md) para el detalle de lo hecho y lo pendiente.

