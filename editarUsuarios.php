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

   if($usuario['Tipo']==="Registrado")
   {
      header("Location: /portada.php");
   }

   if(isset ($_GET['IDComentarios'])){ //Si está definida la variable ev
      
      $idComentario = $_GET['IDComentarios']; //Se toma el id
      $comentario = getComentario($mysqli,$idComentario);
      //mostrarComentario($comentario);

  }
  else{
      $idComentario = -1;
  }

   $usuarios = getTodosUsuarios($mysqli);
   
  //Gestion de los comentarios
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
   
   if(isset($_POST["editarpermisosusuario"])){
      $nickUsuario = $_POST['editarpermisosusuario'];
      header("Location:/editarRolUsuarios.php?Usuario=".$nickUsuario);
  }
   

   exit();
}

//Renderizar la pagina
echo $twig->render('listaUsuarios.html', ['usuarios' => $usuarios]);

?>