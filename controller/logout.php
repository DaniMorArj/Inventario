<?php
session_start();//Comprobamos si la sesión existe, si no existe lo redirigimos al login
require_once __DIR__ . '/../lib/AccessGate.php';

session_destroy();// Destruimos la sesión
header('location:login.php');
