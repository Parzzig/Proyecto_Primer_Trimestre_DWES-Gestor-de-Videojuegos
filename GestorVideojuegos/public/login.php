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
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (!$email || !$password) {
        $mensaje = "El email y la contraseña son obligatorios.";
    } else {
        $user = Usuario::login($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            header("Location: perfil.php");
            exit;
        } else {
            $mensaje = "Email o contraseña incorrectos.";
        }
    }
}

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

echo $twig->render('login.twig', [
    'mensaje' => $mensaje,
    'session' => $_SESSION
]);