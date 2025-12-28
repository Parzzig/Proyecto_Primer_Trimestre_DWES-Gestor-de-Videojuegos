<?php
require_once "../vendor/autoload.php";
require_once "../src/Amigo.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];

// Aceptar solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitud_id'])) {
    Amigo::aceptarSolicitud($_POST['solicitud_id']);
}

$solicitudes = Amigo::listarSolicitudes($usuario_id);

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);
echo $twig->render('solicitudes.twig', [
    'solicitudes' => $solicitudes,
    'session' => $_SESSION
]);