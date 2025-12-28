<?php
require_once "Database.php";

class Amigo {

    // Enviar solicitud
    public static function enviarSolicitud($usuario_id, $correo_amigo) {
        $pdo = Database::connect();

        // Buscar usuario por correo
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $correo_amigo);
        $stmt->execute();
        $amigo = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$amigo) return false; // Usuario no existe

        $amigo_id = $amigo['id'];

        // Evitar enviar a si mismo
        if ($usuario_id == $amigo_id) return false;

        // Comprobar si ya existe solicitud o amistad
        $stmt = $pdo->prepare("SELECT * FROM amigos WHERE usuario_id = :uid AND amigo_id = :aid LIMIT 1");
        $stmt->bindParam(":uid", $usuario_id);
        $stmt->bindParam(":aid", $amigo_id);
        $stmt->execute();
        if ($stmt->fetch()) return false;

        // Insertar solicitud
        $stmt = $pdo->prepare("INSERT INTO amigos (usuario_id, amigo_id, estado) VALUES (:uid, :aid, 'pendiente')");
        $stmt->bindParam(":uid", $usuario_id);
        $stmt->bindParam(":aid", $amigo_id);
        $stmt->execute();
        return true;
    }

    // Listar solicitudes recibidas
    public static function listarSolicitudes($usuario_id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT a.id, u.nombre, u.email
            FROM amigos a
            JOIN usuarios u ON u.id = a.usuario_id
            WHERE a.amigo_id = :uid AND a.estado = 'pendiente'
        ");
        $stmt->bindParam(":uid", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Aceptar solicitud
    public static function aceptarSolicitud($solicitud_id) {
        $pdo = Database::connect();

        // Cambiar estado a aceptado
        $stmt = $pdo->prepare("UPDATE amigos SET estado = 'aceptado' WHERE id = :sid");
        $stmt->bindParam(":sid", $solicitud_id);
        $stmt->execute();

        // Obtener usuario y amigo para insertar registro inverso
        $stmt2 = $pdo->prepare("SELECT usuario_id, amigo_id FROM amigos WHERE id = :sid");
        $stmt2->bindParam(":sid", $solicitud_id);
        $stmt2->execute();
        $sol = $stmt2->fetch(PDO::FETCH_ASSOC);

        // Insertar registro inverso si no existe
        $stmt3 = $pdo->prepare("INSERT IGNORE INTO amigos (usuario_id, amigo_id, estado) VALUES (:aid, :uid, 'aceptado')");
        $stmt3->bindParam(":aid", $sol['amigo_id']);
        $stmt3->bindParam(":uid", $sol['usuario_id']);
        $stmt3->execute();
    }

    // Listar amigos
    public static function listarAmigos($usuario_id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("
            SELECT u.id, u.nombre, u.email
            FROM amigos a
            JOIN usuarios u ON u.id = a.amigo_id
            WHERE a.usuario_id = :uid AND a.estado = 'aceptado'
        ");
        $stmt->bindParam(":uid", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}