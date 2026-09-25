#!/bin/sh
# Render (y la mayoría de hostings tipo PaaS) asignan el puerto en tiempo de
# ejecución mediante la variable PORT, no en el build. Apache por defecto
# escucha siempre en el 80, así que lo reescribimos aquí antes de arrancar.
set -e

PORT="${PORT:-80}"

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
