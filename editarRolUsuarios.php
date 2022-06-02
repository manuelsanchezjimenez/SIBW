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

   if($usuario['Tipo']!="Superusuario")
   {
      header("Location: /portada.php");
   }

  if(isset($_GET['Usuario']))
   {
      $usuario = $_GET['Usuario'];
      $usuario2 = getUsuarios($mysqli,$usuario);
   }
  //Gestion de los comentarios
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
   
   if(isset($_POST["changeRegistrado"])){
      
      $nickUsuario = $_POST['editarpermisosusuario'];
      cambiarARegistrado($mysqli,$nickUsuario);
      header("Location:/editarRolUsuarios.php?Usuario=".$nickUsuario);
  }
   if(isset($_POST["changeModerador"])){
      
      $nickUsuario = $_POST['editarpermisosusuario'];
      cambiarAModerador($mysqli,$nickUsuario);
      header("Location:/editarRolUsuarios.php?Usuario=".$nickUsuario);
  }
  if(isset($_POST["changeGestor"])){
      
      $nickUsuario = $_POST['editarpermisosusuario'];
      cambiarAGestor($mysqli,$nickUsuario);
      header("Location:/editarRolUsuarios.php?Usuario=".$nickUsuario);
   }
  if(isset($_POST["changeSuperusuario"])){
   $nickUsuario = $_POST['editarpermisosusuario'];
   cambiarASuper($mysqli,$nickUsuario);
   header("Location:/editarRolUsuarios.php?Usuario=".$nickUsuario);
}
   exit();
}

//Renderizar la pagina
echo $twig->render('editarRolUsuario.html', ['usuario' => $usuario2]);

?>