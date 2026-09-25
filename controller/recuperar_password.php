<?php
session_start();// Comprobamos si la sesión existe, si no existe lo redirigimos al login
require_once __DIR__ . '/../lib/AccessGate.php';

include_once '../model/Usuario.php';

// Cargamos PHPMailer (instalado por Composer en el contenedor). Guardado por si no existe en local.
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

// Variables para controlar el flujo
$paso = 1;
$error = '';
$ok = '';
$token = '';

//El usuario llegó desde el enlace del email (tiene token en la URL) ---
if (isset($_GET['token']) && $_GET['token'] != '') {

    $token = $_GET['token'];
    $emailToken = Usuario::validarToken($token);

    if ($emailToken == false) {
        $error = 'El enlace no es válido o ha expirado. Solicita uno nuevo.';
        $paso = 1;
    } else {
        $paso = 2;
    }
}

//Procesamos el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // POST paso 1: recibimos el email
    if (isset($_POST['email'])) {

        $email = $_POST['email'];

        if ($email == '') {
            $error = 'Introduce tu email.';
            $paso = 1;

        } else {

            // Siempre mostramos el mismo mensaje por seguridad
            $ok = 'Si el email está registrado recibirás un correo en breve.';

            if (Usuario::emailExiste($email)) {

                // Generamos token único
                $token = bin2hex(random_bytes(32));

                // Guardamos en la BD
                Usuario::guardarToken($email, $token);

                // Construimos el enlace. La app va en la raíz del dominio.
                // Usamos APP_BASE_URL si está definida (correcto detrás de un proxy);
                // si no, la reconstruimos a partir del host de la petición (dev local).
                $baseUrl = getenv('APP_BASE_URL');
                if ($baseUrl === false || $baseUrl == '') {
                    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
                }
                $baseUrl = rtrim($baseUrl, '/');
                $enlace = $baseUrl . '/recuperar_password.php?token=' . $token;

                // Preparamos el correo
                $asunto = 'Recuperar contraseña - InventarioApp';

                $mensaje  = 'Hola,' . "\n\n";
                $mensaje .= 'Hemos recibido una solicitud para restablecer tu contraseña.' . "\n\n";
                $mensaje .= 'Pulsa en el siguiente enlace para crear una nueva contraseña:' . "\n";
                $mensaje .= $enlace . "\n\n";
                $mensaje .= 'Este enlace caducará en 1 hora.' . "\n\n";
                $mensaje .= 'Si no solicitaste este cambio, ignora este mensaje.' . "\n\n";
                $mensaje .= 'InventarioApp';

                // Enviamos con PHPMailer + SMTP (Microsoft 365). Todo por variables de entorno.
                // Si no hay SMTP configurado (p.ej. desarrollo local), no se envía y no se rompe.
                $smtpHost = getenv('SMTP_HOST');
                if ($smtpHost != '' && class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {

                    $smtpPort = getenv('SMTP_PORT');
                    if ($smtpPort == '') {
                        $smtpPort = 587;
                    }
                    $smtpSecure = getenv('SMTP_SECURE');
                    if ($smtpSecure == '') {
                        $smtpSecure = 'tls';
                    }
                    $smtpUser = getenv('SMTP_USER');
                    $smtpPass = getenv('SMTP_PASS');
                    $smtpFrom = getenv('SMTP_FROM');
                    if ($smtpFrom == '') {
                        $smtpFrom = $smtpUser;
                    }
                    $smtpFromName = getenv('SMTP_FROM_NAME');
                    if ($smtpFromName == '') {
                        $smtpFromName = 'InventarioApp - Grupo Demo';
                    }

                    try {
                        $correo = new \PHPMailer\PHPMailer\PHPMailer(true);
                        $correo->isSMTP();
                        $correo->Host = $smtpHost;
                        $correo->SMTPAuth = true;
                        $correo->Username = $smtpUser;
                        $correo->Password = $smtpPass;
                        $correo->SMTPSecure = $smtpSecure;
                        $correo->Port = (int)$smtpPort;
                        $correo->CharSet = 'UTF-8';

                        $correo->setFrom($smtpFrom, $smtpFromName);
                        $correo->addAddress($email);
                        $correo->Subject = $asunto;
                        $correo->Body = $mensaje;

                        $correo->send();
                    } catch (Exception $e) {
                        // No revelamos el fallo al usuario (mismo mensaje de seguridad).
                        error_log('Error enviando correo de recuperación: ' . $e->getMessage());
                    }
                }
            }

            $paso = 1;
        }
    }

    // POST paso 2: recibimos la nueva contraseña
    if (isset($_POST['token']) && isset($_POST['nueva_pass']) && isset($_POST['confirmar_pass'])) {

        $token = $_POST['token'];
        $nuevaPass = $_POST['nueva_pass'];
        $confirmarPass = $_POST['confirmar_pass'];

        $emailToken = Usuario::validarToken($token);

        if ($emailToken == false) {
            $error = 'El enlace no es válido o ha expirado. Solicita uno nuevo.';
            $paso = 1;

        } elseif ($nuevaPass == '') {
            $error = 'Introduce la nueva contraseña.';
            $paso = 2;

        } elseif (strlen($nuevaPass) < 6) {
            $error = 'La contraseña debe tener al menos 6 caracteres.';
            $paso = 2;

        } elseif ($nuevaPass != $confirmarPass) {
            $error = 'Las contraseñas no coinciden.';
            $paso = 2;

        } else {
            Usuario::cambiarPassword($emailToken, $nuevaPass, $token);
            $ok = 'Contraseña cambiada correctamente. Ya puedes iniciar sesión.';
            $paso = 1;
        }
    }
}

//Cargamos la vista correspondiente
if ($paso == 2) {
    include_once '../view/recuperar_nueva_pass_view.php';
} else {
    include_once '../view/recuperar_email_view.php';
}