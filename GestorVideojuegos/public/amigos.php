<?php
require_once "../vendor/autoload.php";
require_once "../src/Amigo.php";
require_once "../src/Usuario.php";
require_once "../src/Videojuego.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$mensaje = null;

// Enviar solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    $correo = trim($_POST['correo']);
    if (!Amigo::enviarSolicitud($usuario_id, $correo)) {
        $mensaje = "No se pudo enviar la solicitud. El correo no existe o ya es tu amigo.";
    } else {
        $mensaje = "Solicitud enviada correctamente.";
    }
}

// Listar amigos
$amigos = Amigo::listarAmigos($usuario_id);

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);
echo $twig->render('amigos.twig', [
    'amigos' => $amigos,
    'mensaje' => $mensaje,
    'session' => $_SESSION
]);