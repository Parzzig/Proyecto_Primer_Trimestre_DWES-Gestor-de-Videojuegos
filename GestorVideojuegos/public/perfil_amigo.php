<?php
require_once "../vendor/autoload.php";
require_once "../src/Database.php";
require_once "../src/Videojuego.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['user_id'];
$amigo_id = $_GET['id'] ?? null;

if (!$amigo_id) {
    header("Location: amigos.php");
    exit();
}

$pdo = Database::connect();

/*
Comprobamos que realmente sean amigos
*/
$stmt = $pdo->prepare("
    SELECT 1 FROM amigos
    WHERE usuario_id = :uid
        AND amigo_id = :aid
        AND estado = 'aceptado'
    LIMIT 1
");
$stmt->bindParam(":uid", $usuario_id);
$stmt->bindParam(":aid", $amigo_id);
$stmt->execute();

if (!$stmt->fetch()) {
    header("Location: amigos.php");
    exit();
}

/*
Datos del amigo
*/
$stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = :id");
$stmt->bindParam(":id", $amigo_id);
$stmt->execute();
$amigo = $stmt->fetch(PDO::FETCH_ASSOC);

/*
Colección del amigo
*/
$stmt = $pdo->prepare("
    SELECT 
        v.titulo,
        v.plataforma,
        v.genero,
        cu.estado,
        val.puntuacion AS mi_puntuacion,
        (
            SELECT ROUND(AVG(puntuacion),1)
            FROM valoraciones
            WHERE videojuego_id = v.id
        ) AS media_puntuacion
    FROM coleccion_usuario cu
    JOIN videojuegos v ON v.id = cu.videojuego_id
    LEFT JOIN valoraciones val 
        ON val.videojuego_id = v.id AND val.usuario_id = cu.usuario_id
    WHERE cu.usuario_id = :uid
");
$stmt->bindParam(":uid", $amigo_id);
$stmt->execute();
$juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$loader = new FilesystemLoader('../templates');
$twig = new Environment($loader);

echo $twig->render('perfil_amigo.twig', [
    'amigo' => $amigo,
    'juegos' => $juegos,
    'session' => $_SESSION
]);