<?php
require_once "../vendor/autoload.php";
require_once "../src/Usuario.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: perfil.php');
    exit;
}
$mensaje = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST["nombre"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $password2 = trim($_POST["password2"] ?? '');

    if (!$nombre || !$email || !$password || !$password2) {
        $mensaje = "Todos los campos son obligatorios.";
    } elseif ($password !== $password2) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        if (Usuario::register($nombre, $email, $password)) {
            $mensaje = "Usuario registrado correctamente. <a href='login.php'>Inicia sesión</a>";
        } else {
            $mensaje = "El email ya está registrado.";
        }
    }
}

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

echo $twig->render('register.twig', [
    'mensaje' => $mensaje,
    'session' => $_SESSION
]);
