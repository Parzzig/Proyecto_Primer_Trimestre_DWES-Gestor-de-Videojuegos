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

// Insertar con estado por defecto si no existe
$stmt = $pdo->prepare("
    INSERT IGNORE INTO coleccion_usuario (usuario_id, videojuego_id, estado)
    VALUES (:uid, :vid, 'jugando')
");
$stmt->execute([
    ':uid' => $_SESSION['user_id'],
    ':vid' => $videojuego_id
]);

header("Location: perfil.php");
exit;