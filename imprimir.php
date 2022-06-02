<?php
   require_once '/usr/local/lib/php/vendor/autoload.php';
   include("bd.php");
   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);
   if(isset($_GET['ev']))
   {
      $idEv=$_GET['ev'];
      
   } else
   {
      $idEv = -1;
   }
   $mysqli = conectarBD();
   $evento = getEvento($idEv,$mysqli);
   echo $twig->render('imprimir.html',['evento'=> $evento]);
?>