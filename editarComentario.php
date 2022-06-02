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
  //Gestion de los comentarios
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
   if(isset($_POST["guardarNombre"])){
       $idComentario = $_POST['IDcomentarios'];
       $nombre = $_POST['nuevonombre'];
       editarNombreComentario($mysqli,$idComentario,$nombre);
       header("Location: /editarComentario.php/?IDComentarios=". $idComentario);
   }

   if(isset($_POST["guardarEmail"])) {
       $idComentario = $_POST['IDcomentarios'];
       $email = $_POST['nuevoemail'];
       editarMailComentario($mysqli,$idComentario,$email);
       header("Location: /editarComentario.php/?IDComentarios=". $idComentario);
   }

   if(isset($_POST["guardarTexto"])) {
       $idComentario = $_POST['IDcomentarios'];
       $texto = $_POST['nuevotexto'];
       editarTextoComentario($mysqli,$idComentario,$texto);
       header("Location: /editarComentario.php/?IDComentarios=". $idComentario);
   }

   if(isset($_POST["volver"])) {
       $idEv=$_POST['IDproductos'];
       header("Location: /producto.php?ev=".$idEv);
   }

   if(isset($_POST["cancelar"])) {
       $idComentario = $_POST['idcomentario'];
       header("Location: /editarComentario.php/?IDcomentarios=". $idComentario);
   }
   

   exit();
}

//Renderizar la pagina
echo $twig->render('editarComentario.html', ['usuario' => $usuario, 'comentario' => $comentario, 'prohibidas' => $prohibidas]);

?>