<?php
require_once "../src/Database.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$videojuego_id = $_POST['videojuego_id'] ?? null;

if (!$videojuego_id) {
    header("Location: perfil.php");
    exit;
}

$pdo = Database::connect();

// Eliminar de la colección del usuario
$stmt = $pdo->prepare("
    DELETE FROM coleccion_usuario 
    WHERE usuario_id = :uid AND videojuego_id = :vid
");
$stmt->execute([
    ':uid' => $_SESSION['user_id'],
    ':vid' => $videojuego_id
]);

header("Location: perfil.php");
exit;