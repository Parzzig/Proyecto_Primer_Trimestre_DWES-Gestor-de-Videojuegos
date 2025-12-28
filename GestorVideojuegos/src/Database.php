<?php
class Database {
    public static function connect() {
        $dsn = "mysql:host=db;dbname=lista_videojuegos;charset=utf8";
        $pdo = new PDO($dsn, "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
