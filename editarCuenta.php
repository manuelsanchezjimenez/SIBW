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
      header("Location: /Entrada.php");
   }
   if(isset ($_GET['edicion'])){ 
      $edicion = 1;
  }
  else{
      $edicon = 0;
  }
  if(isset ($_GET['error'])){ 
      $error = $_GET['error'];
      if($error == 0)
         header("Location: ../editarCuenta.php");
   }
   else{
      $error = -1;
   }

  if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if(isset($_POST["guardarUsuario"]))
   {
      $nuevoUsuario = $_POST['nuevoUsuario'];
      if(editarUsuario($mysqli,$nuevoUsuario,$usuario['Usuario'])){
         $_SESSION['Usuario']=$nuevoUsuario;
         header("Location: ../editarCuenta.php?edicion&error=0");
      }else{
         header("Location: ../editarCuenta.php?edicion&error=1");
      }
   }

   if(isset($_POST["guardarNombre"]))
   {
      $nuevoNombre = $_POST['nuevoNombre'];
      if(editarNombre($mysqli,$nuevoNombre,$usuario['Usuario'])){
         header("Location: ../editarCuenta.php?edicion&error=0");
      }else{
         header("Location: ../editarCuenta.php?edicion&error=1");
      }
   }
   if(isset($_POST["guardarApellidos"]))
   {
      $nuevosApellidos = $_POST['nuevoApellidos'];
      if(editarApellidos($mysqli,$nuevosApellidos,$usuario['Usuario'])){
         header("Location: ../editarCuenta.php?edicion&error=0");
      }else{
         header("Location: ../editarCuenta.php?edicion&error=1");
      }
   }

   if(isset($_POST["guardarEmail"]))
   {
      $nuevoMail = $_POST['nuevoEmail'];
      if(editarMail($mysqli,$nuevoMail,$usuario['Usuario'])){
         header("Location: ../editarCuenta.php?edicion&error=0");
      }else{
         header("Location: ../editarCuenta.php?edicion&error=1");
      }
   }

   if(isset($_POST["guardarContraseña"]))
   {
      $nuevoContraseña = $_POST['nuevaContraseña'];
      if(editarContraseña($mysqli,$nuevoContraseña,$usuario['Usuario'])){
         header("Location: ../editarCuenta.php?edicion&error=0");
      }else{
         header("Location: ../editarCuenta.php?edicion&error=1");
      }
   }
}
   echo $twig->render('editarCuenta.html',['usuario' => $usuario, 'edicion'=>$edicion, 'error'=>$error]);
?>