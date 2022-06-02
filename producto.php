<?php
   require_once '/usr/local/lib/php/vendor/autoload.php';
   include("bd.php");
   $loader = new \Twig\Loader\FilesystemLoader('templates');
   $twig = new \Twig\Environment($loader);
   $mysqli = conectarBD();
   if(isset($_GET['ev']))
   {
      $idEv=$_GET['ev'];
      
   } else
   {
      $idEv = -1;
   }

   session_start();

   $usuario = [];

   if( isset($_SESSION['Usuario']) ){

      $usuarioActual = $_SESSION['Usuario'];
      $user = getUsuario($usuarioActual,$mysqli);

      $usuario['Usuario']= $user['Usuario'];
      $usuario['Contraseña']= $user['Contraseña'];
      $usuario['Tipo']= $user['Tipo'];
      $usuario['Nombre']= $user['Nombre'];
      $usuario['Apellidos']= $user['Apellidos'];
      $usuario['Mail']= $user['Mail'];

   } else{
      header("Location: /login.php");
   }
   if(isset($_POST["editarcomentario"])) {
      $idComentario = $_POST['editarcomentario'];
      header("Location: /editarComentario.php?IDComentarios=".$idComentario);
   }
   if(isset($_POST["eliminarcomentario"]))
   {
      $idComentario = $_POST['eliminarcomentario'];
      $produc = getComentario($mysqli,$idComentario);
      $pagActual = $produc['IDproductos'];
      eliminarComentario($mysqli,$idComentario);

      header("Location: /producto.php?ev=".$pagActual);
   }
   if($_SERVER['REQUEST_METHOD'] === 'POST'){
      if(isset($_POST["nuevocomentario"])){
        $texto = $_POST['msg'];
        $idEv=$_POST['idEvento'];
        //Comprobar que ningún campo está vacio
        if($texto!=""){
            nuevoComentario($mysqli,$idEv,$usuario['Nombre'],$usuario['Mail'],$texto);
        }
        header("Location: /producto.php/?ev=".$idEv);
      } 
      if(isset($_POST["eliminarevento"])) {
         $idEvento = $_POST['eliminarevento'];
         eliminarProducto($mysqli,$idEvento);
         header("Location: /portada.php");
      }
      if(isset($_POST["editarevento"])) {
         $idEvento = $_POST['editarevento'];
         header("Location: /editarProducto.php?ev=".$idEvento);
       } 
      exit();
    }
   
    
   $evento = getEvento($idEv,$mysqli);
   $comentarios = getTodosComents($mysqli,$idEv);
   $numeroComents = sizeof($comentarios);
   $conjuntoPalabrasProhibidas = palabrasProhibidas($mysqli);
   echo $twig->render('producto.html',['evento'=> $evento,'usuario'=>$usuario,'comentarios'=>$comentarios,'palabrasOcultas'=>$conjuntoPalabrasProhibidas]);

?>