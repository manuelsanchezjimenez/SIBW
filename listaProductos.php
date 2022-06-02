<?php
   require_once '/usr/local/lib/php/vendor/autoload.php';
   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);
   include("bd.php");
   $mysqli = conectarBD();
   session_start();
   $usuario = [];

   if( isset($_SESSION['Usuario']) ){

      $user = getUsuario($_SESSION['Usuario'],$mysqli);

      $usuario['Usuario']= $user['Usuario'];
      $usuario['Contraseña']= $user['Contraseña'];
      $usuario['Tipo']= $user['Tipo'];
      $usuario['Nombre']= $user['Nombre'];
      $usuario['Apellidos']= $user['Apellidos'];
      $usuario['Mail']= $user['Mail'];

   } else{
      header("Location: /login.php");
   }

   if($usuario['Tipo']=="Superusuario" or $usuario['Tipo']=="Gestor")
   {
      
   } else{
      header("Location: /portada.php");
   }

  if(isset($_GET['Usuario']))
   {
      $usuario = $_GET['Usuario'];
      $usuario2 = getUsuarios($mysqli,$usuario);
   }
   $productos = getProductos($mysqli);

//Renderizar la pagina
echo $twig->render('listaProductos.html', ['usuario' => $usuario2, 'productos'=>$productos]);

?>