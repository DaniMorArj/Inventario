<?php
/**
 * Muro de acceso a toda la demo pública, independiente del login propio de la
 * aplicación (tabla `usuario`). Se activa definiendo la variable de entorno
 * APP_LOGIN_PASSWORD; si está vacía, no hay muro (cómodo en desarrollo local).
 *
 * Uso: incluir justo después de session_start() en cualquier punto de entrada
 * (controller/*.php). Ver CLAUDE.md para el motivo (inyección SQL sin arreglar
 * en varios modelos: el muro evita que la demo pública quede abierta a cualquiera).
 */

function inventarioAccessGate()
{
    $clave = getenv('APP_LOGIN_PASSWORD');
    if ($clave === false || $clave === '') {
        return; // sin muro configurado
    }

    if (!empty($_SESSION['muro_ok'])) {
        return; // ya superado en esta sesión
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['muro_password'])) {
        if (hash_equals($clave, (string) $_POST['muro_password'])) {
            $_SESSION['muro_ok'] = true;
            // Redirige a la misma URL sin el POST (permite recargar sin reenviar el formulario).
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit;
        }
        $error = 'Contraseña incorrecta.';
    }

    http_response_code(401);
    ?>
    <!doctype html>
    <html lang="es">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Acceso · InventarioApp</title>
      <style>
        body { font-family: system-ui, -apple-system, sans-serif; background:#0f172a; color:#e2e8f0; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
        .caja { background:#1e293b; padding:32px; border-radius:12px; width:320px; max-width:90vw; box-shadow:0 10px 30px rgba(0,0,0,.3); }
        h1 { font-size:1.15rem; margin:0 0 4px; }
        p.sub { color:#94a3b8; font-size:.85rem; margin:0 0 18px; }
        input { width:100%; padding:10px; border-radius:6px; border:1px solid #334155; background:#0f172a; color:#e2e8f0; margin-bottom:12px; box-sizing:border-box; font-size:1rem; }
        button { width:100%; padding:10px; border-radius:6px; border:none; background:#2563eb; color:#fff; font-weight:600; cursor:pointer; font-size:1rem; }
        button:hover { background:#1d4ed8; }
        .error { color:#f87171; font-size:.85rem; margin:0 0 12px; }
      </style>
    </head>
    <body>
      <div class="caja">
        <h1>InventarioApp</h1>
        <p class="sub">Demo privada — pide la contraseña de acceso.</p>
        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form method="post">
          <input type="password" name="muro_password" placeholder="Contraseña" autofocus required>
          <button type="submit">Entrar</button>
        </form>
      </div>
    </body>
    </html>
    <?php
    exit;
}

inventarioAccessGate();
