<?php
require_once "../vendor/autoload.php";
require_once "../src/Database.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];
$pdo = Database::connect();

$stmt = $pdo->prepare("
    SELECT cu.id AS coleccion_id, v.id AS videojuego_id, v.titulo, v.plataforma, v.genero, cu.estado, 
            (SELECT puntuacion FROM valoraciones WHERE usuario_id = :uid AND videojuego_id = v.id) AS mi_puntuacion,
            (SELECT ROUND(AVG(puntuacion), 1) FROM valoraciones WHERE videojuego_id = v.id) AS media_puntuacion
    FROM coleccion_usuario cu
    JOIN videojuegos v ON v.id = cu.videojuego_id
    WHERE cu.usuario_id = :uid
");
$stmt->execute([':uid' => $uid]);
$juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$twig = new Environment(new FilesystemLoader('../templates'));
echo $twig->render('perfil.twig', [
    'juegos' => $juegos,
    'session' => $_SESSION
]);