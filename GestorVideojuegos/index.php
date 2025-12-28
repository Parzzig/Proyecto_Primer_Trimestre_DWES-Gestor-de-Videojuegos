<?php
session_start();

// Si hay sesión, ir al perfil
if (isset($_SESSION['user_id'])) {
    header("Location: public/perfil.php");
} else {
    header("Location: public/login.php");
}
exit();
