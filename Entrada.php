<?php
   require_once '/usr/local/lib/php/vendor/autoload.php';

   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);
   include("bd.php");


   session_start();
   $usuario = array('Usuario' => 'Defecto', 'Tipo' => 'Anonimo');
   if( isset($_SESSION['Usuario']) ){
      $usuario['Usuario']= $_SESSION['Usuario'];
      $usuario['Tipo']= obtenerTipoUsuario($_SESSION['Usuario']);
      $conectado = 1;
      header("Location: /portada.php");
    }
   echo $twig->render('Entrada.html',['Usuario' => $usuario['Usuario'], 'Conectado'=>$conectado, 'Tipo'=>$usuario['Tipo']]);
?>