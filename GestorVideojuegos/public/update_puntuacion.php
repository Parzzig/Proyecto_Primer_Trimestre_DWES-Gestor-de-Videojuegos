<?php
require_once "../src/Database.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$videojuego_id = $_POST['videojuego_id'] ?? null;
$puntuacion = intval($_POST['puntuacion'] ?? 0);

if (!$videojuego_id || $puntuacion < 1 || $puntuacion > 10) {
    header("Location: perfil.php");
    exit;
}

$pdo = Database::connect();

// Insertar o actualizar la puntuación
$stmt = $pdo->prepare("
    INSERT INTO valoraciones (usuario_id, videojuego_id, puntuacion)
    VALUES (:uid, :vid, :puntuacion)
    ON DUPLICATE KEY UPDATE puntuacion = :puntuacion
");
$stmt->execute([
    ':uid' => $_SESSION['user_id'],
    ':vid' => $videojuego_id,
    ':puntuacion' => $puntuacion
]);

header("Location: perfil.php");
exit;