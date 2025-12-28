<?php
require_once "Database.php";

class Videojuego {
    public static function listar() {
        $pdo = Database::connect();
        $stmt = $pdo->query("
            SELECT v.id, v.titulo, v.plataforma, v.genero, v.descripcion,
            ROUND(AVG(val.puntuacion),1) AS puntuacion_media
            FROM videojuegos v
            LEFT JOIN valoraciones val ON v.id = val.videojuego_id
            GROUP BY v.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function coleccionUsuario($usuario_id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT v.id, v.titulo, v.plataforma, v.genero, cu.estado
            FROM coleccion_usuario cu
            JOIN videojuegos v ON cu.videojuego_id = v.id
            WHERE cu.usuario_id = :uid
        ");
        $stmt->bindParam(":uid", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
