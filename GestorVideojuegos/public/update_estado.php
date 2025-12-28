<?php
require_once "../src/Database.php";

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$videojuego_id = $_POST['videojuego_id'] ?? null;
$estado = $_POST['estado'] ?? null;

if (!$videojuego_id || !in_array($estado, ['jugando','completado'])) {
    header("Location: perfil.php");
    exit;
}

$pdo = Database::connect();

$stmt = $pdo->prepare("
    UPDATE coleccion_usuario
    SET estado = :estado
    WHERE usuario_id = :uid AND videojuego_id = :vid
");
$stmt->execute([
    ':estado' => $estado,
    ':uid' => $_SESSION['user_id'],
    ':vid' => $videojuego_id
]);

header("Location: perfil.php");
exit;