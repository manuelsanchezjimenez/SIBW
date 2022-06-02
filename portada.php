<?php
   require_once '/usr/local/lib/php/vendor/autoload.php';
   include("bd.php");

   session_start();
   $usuario=[];
   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);

   $mysqli = conectarBD();
   $usuario = array('Usuario' => 'defecto', 'Tipo' => 'anonimo');
   if( isset($_SESSION['Usuario']) ){

      $user = getUsuarios($mysqli,$_SESSION['Usuario']); 
  
      $usuario['Usuario']= $user['Usuario'];
      $usuario['Tipo']= $user['Tipo'];
   }

   $productos = getProductos($mysqli);
   echo $twig->render('portada.html',['productos'=>$productos,'Usuario'=>$usuario]);
?>