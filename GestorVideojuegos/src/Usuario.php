<?php
require_once "Database.php";

class Usuario {

    public static function login($email, $password) {
        $pdo = Database::connect();

        $stmt = $pdo->prepare(
            "SELECT * FROM usuarios WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public static function register($nombre, $email, $password) {
        $pdo = Database::connect();

        // Comprobar si existe el email
        $stmt = $pdo->prepare(
            "SELECT id FROM usuarios WHERE email = :email LIMIT 1"
        );
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            return false;
        }

        // Cifrar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar usuario
        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nombre, email, password)
            VALUES (:nombre, :email, :password)"
        );
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $passwordHash);

        return $stmt->execute();
    }
}
