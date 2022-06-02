<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";

    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    session_start();
    $mysqli = conectarBD();
    $usuario = [];

    if( isset($_SESSION['Usuario']) ){
      $usua = $_SESSION['Usuario'];
      $user = getUsuario($usua,$mysqli);

      $usuario['Usuario']= $user['Usuario'];
      $usuario['Contraseña']= $user['Contraseña'];
      $usuario['Tipo']= $user['Tipo'];
      $usuario['Nombre']= $user['Nombre'];
      $usuario['Apellidos']= $user['Apellidos'];
      $usuario['Mail']= $user['Mail'];

   } else{
      header("Location: /login.php");
   }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        
        if(isset($_POST["nuevoProducto"])){
            $IDnuevo = getMaxIDProductos($mysqli);
            $IDnuevo++;
            $nombre= $_POST['nuevonombre'];
            $descripcion= $_POST['nuevadescripcion'];
            $Imagen1= $_POST['nuevoImagen1'];
            $Imagen2= $_POST['nuevoImagen2'];
            $publicado= 0;
            if(aniadirProducto($mysqli,$IDnuevo,$nombre,$descripcion,$Imagen1,$Imagen2,$publicado)){
                
                header("Location: /portada.php");
            }
            else{
                header("Location: /aniadirProducto.php");
            }
        }
    
        exit();
    }

   //Renderizar la pagina
   echo $twig->render('nuevoProducto.html', ['usuario' => $usuario]);

?>