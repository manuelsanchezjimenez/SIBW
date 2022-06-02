<?php
require_once 'vendor/autoload.php';
include 'bd.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);
$mysqli = conectarBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['Usuario'];
    $Contraseña = $_POST['Contraseña'];
    if (checkLogin($mysqli,$usuario, $Contraseña)) {
        session_start();
        $_SESSION['Usuario'] = $usuario;
        header("Location: /portada.php");
    }else{
        header("Location: /login.php");
    }

    exit();
}

echo $twig->render('login.html', []);
?>