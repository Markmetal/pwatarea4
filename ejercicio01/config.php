<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'tareas_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaLogueado() {
    return isset($_SESSION['usuario_id']);
}

function esAdministrador() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';
}

function requiereLogin() {
    if (!estaLogueado()) {
        header('Location: login.php');
        exit();
    }
}

function limpiarInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>