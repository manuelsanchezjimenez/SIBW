<?php
require_once 'vendor/autoload.php';
include 'bd.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Usuario = $_POST['Usuario'];
    $Contraseña = $_POST['Contraseña'];
    $nombre = $_POST['Nombre'];
    $apellidos = $_POST['Apellidos'];
    $email = $_POST['Mail'];
    $tipo = $_POST['Tipo'];

    if (insertarUsuario($Usuario, $Contraseña,$nombre,$apellidos,$email,$tipo)) {
        session_start();
        $_SESSION['Usuario'] = $Usuario;
    }

    header("Location: index.php");
    exit();
}

echo $twig->render('registro.html', []);
?>