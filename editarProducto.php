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

   if($usuario['Tipo']==="Registrado" or $usuario['Tipo']==="Moderador" )
   {
      header("Location: /portada.php");
   }
   
   if(isset ($_GET['ev'])){ //Si está definida la variable ev
      
      $idProducto = $_GET['ev']; //Se toma el id
      $evento = getEvento($idProducto,$mysqli); 
  }
  else{
      $idProducto = -1;
  }
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        
   if(isset($_POST["guardarNombre"])){
       $idEvento = $_POST['IDevento'];
       $nombre= $_POST['nuevonombre'];
       editNombreEvento($mysqli,$idEvento,$nombre);
       header("Location: /editarProducto.php/?ev=".$idEvento);
   }

   if(isset($_POST["guardarDescripcion"])){
       $idEvento = $_POST['IDevento'];
       $descripcion= $_POST['nuevaDescripcion'];
       editDescripcionEvento($mysqli,$idEvento,$descripcion);
       header("Location: /editarProducto.php/?ev=".$idEvento);
   }

   if(isset($_POST["guardarImagen1"])){
      $idEvento = $_POST['IDevento'];
      $nombre= $_POST['nuevaImagen1'];
      editImagen1Evento($mysqli,$idEvento,$nombre);
      header("Location: /editarProducto.php/?ev=".$idEvento);
  }
  if(isset($_POST["guardarImagen2"])){
      $idEvento = $_POST['IDevento'];
      $descripcion= $_POST['nuevaImagen2'];
      editImagen2Evento($mysqli,$idEvento,$nombre);
      header("Location: /editarProducto.php/?ev=".$idEvento);
  }


   if(isset($_POST["cancelar"])) {
       $idEvento = $_POST['IDevento'];
       header("Location: /editarProducto.php/?ev=".$idEvento);
   }


   if(isset($_POST["publicarevento"])){
       $idEvento = $_POST['IDevento'];
       publicarEvento($mysqli,$idEvento);
       header("Location: /editarProducto.php/?ev=".$idEvento);
   }

   if(isset($_POST["ocultarevento"])){
       $idEvento = $_POST['IDevento'];
       ocultarEvento($mysqli,$idEvento);
       header("Location: /editarProducto.php/?ev=".$idEvento);
   }
   if(isset($_POST["aniadirEtiqueta"])){
        $etiqueta = $_POST['nuevaEtiqueta'];
        $idEvento = $_POST['IDevento'];
        if($etiqueta != ""){
            addEtiqueta($mysqli,$etiqueta,$idEvento);
        }
        header("Location: /editarProducto.php/?ev=".$idEvento);
    }
   
   exit();
}
//Renderizar la pagina
echo $twig->render('editarProductos.html', ['usuario' => $usuario, 'evento' => $evento]);

?>