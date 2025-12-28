<?php
require_once "../vendor/autoload.php";
require_once "../src/Videojuego.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["user_id"];
$coleccion = Videojuego::coleccionUsuario($usuario_id);

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

echo $twig->render('coleccion.twig', [
    'coleccion' => $coleccion,
    'email' => $_SESSION["email"]
]);