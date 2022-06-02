<?php
   function conectarBD()
   {
      $mysqli = new mysqli("mysql","manusj0712","Conejo11","SIBW");
      if($mysqli->connect_errno)
      {
         echo ("Fallo al conectar: " . $mysqli->connect_error);
      }
      return $mysqli;
   }
   function getMaxIDProductos($mysqli)
   {
      $evento = [];
      $res =  $mysqli->prepare("SELECT MAX(ID) FROM Productos");
      $res->execute();
      $resultado = $res->get_result();

      if($resultado->num_rows > 0)
      {
         $row = $resultado->fetch_assoc();
         $IDfinal = $row['MAX(ID)'];
         return $IDfinal;
      }
   }
   function getEvento($idEv,$mysqli)
   {
      $evento = array('Nombre'=>'Nombre por defecto','Descripcion'=>'Descripcion por defecto','Imagen1'=>'Imagen1 por defecto','Imagen2'=>'Imagen2 por defecto','Publicado'=>1);
      $res =  $mysqli->prepare("SELECT ID,Nombre, Descripcion,Imagen1,Imagen2,Publicado FROM Productos WHERE ID= ?");
      $res->bind_param('i',$idEv);
      $res->execute();
      $resultado = $res->get_result();

      if($resultado->num_rows > 0)
      {
         $row = $resultado->fetch_assoc();
         $nombreProducto = $row['Nombre'];
         $descripcionProducto = $row['Descripcion'];
         $imagen1 = $row['Imagen1'];
         $imagen2 = $row['Imagen2'];
         $publicad = $row['Publicado'];
         $evento = array('ID'=>$row['ID'],'Nombre'=>$nombreProducto,'Descripcion'=>$descripcionProducto,'Imagen1'=>$imagen1,'Imagen2'=>$imagen2,'Publicado'=>$publicad);
         
         return $evento;
      }
   }

   function obtenerComentario($idEv,$mysqli,$comentario) {
      $sentencia = $mysqli->prepare("SELECT * FROM COMENTARIOS WHERE IDproductos=? ORDER BY Fecha DESC LIMIT ".$comentario. ",2");
      $sentencia->bind_param('i',$idEv);
      $sentencia->execute();
      $resultado = $sentencia->get_result();
		$listaComentarios = array();
      if($resultado->num_rows > 0)
      {
         $row = $resultado->fetch_assoc();
         $date = date_create($row['Fecha']);
         $fecha = date_format($date,"%W %M %e %Y");
         $hora= $row['Hora'];
         $listaComentarios=array('Nombre'=>$row['Nombre'],'Correo'=>$row['Correo'],'Fecha'=>$row['Fecha'],'Descripcion'=>$row['Descripcion'],'Hora'=>$hora);
      }
      $sentencia->close();
		return $listaComentarios;	
  }

  function palabrasProhibidas($mysqli)
  {
     $sentencia = $mysqli->prepare("SELECT Palabra FROM PalabrasProhibidas");
     $sentencia->execute();
     $resultado=$sentencia->get_result();
     $i=0;
     if($resultado->num_rows > 0)
     {
        while($row = $resultado->fetch_assoc())
        {
           $conjuntoPalabras[$i]=$row['Palabra'];
           $i++;
        }
     }
     $sentencia->close();
     return $conjuntoPalabras;
  }

  function insertarUsuario($usuario,$contraseña, $nombre, $apellidos, $email, $tipo)
  {
   $mysqli = conectarBD();
      $sentencia = $mysqli->prepare("INSERT INTO `Usuarios`(`Nombre`, `Apellidos`, `Mail`, `Usuario`, `Contraseña`, `Tipo`) VALUES (?,?,?,?,?,?)");
      $sentencia->bind_param("ssssss",$nombre,$apellidos,$email,$usuario,password_hash($contraseña,PASSWORD_DEFAULT),$tipo);
      $sentencia->execute();
      $res = $sentencia->get_result();
      return $res;
  }

  function checkLogin($mysqli,$usuario, $contraseñaa) {
   $resultado = false;
   $consulta = $mysqli->prepare("SELECT Contraseña FROM Usuarios WHERE Usuario=?");
   $consulta->bind_param("s",$usuario);

   $consulta->execute();
   $res = $consulta->get_result();
   
   if( $res->num_rows > 0){
       $row = $res->fetch_assoc();
       $contraseña2 = $row['Contraseña'];
       if (password_verify($contraseñaa, $contraseña2)) {
           $resultado = true;
       }
   }
   else{
       $resultado = false;
   }

   $consulta->close();
   
   return $resultado;

}
function getUsuario($usuarios,$mysqli) {
   $usuario = [];
   $consulta = $mysqli->prepare("SELECT * FROM `Usuarios` WHERE `Usuario`=?");
   $consulta->bind_param('s',$usuarios);
   $consulta->execute();
   $res = $consulta->get_result();
   if( $res->num_rows > 0){
       $row = $res->fetch_assoc();  
       $usuario = array('Usuario' => $row['Usuario'], 'Contraseña' => $row['Contraseña'], 'Tipo' => $row['Tipo'],'Nombre' => $row['Nombre'],'Apellidos' => $row['Apellidos'],'Mail' => $row['Mail']);
   }
   $consulta->close();

   return $usuario;
}

function obtenerTipoUsuario($usuario) {

   $mysqli = conectarBD();
   $sentencia = "SELECT Tipo FROM `Usuarios` WHERE Usuario='$usuario';";
   $peticion = $mysqli->query($sentencia);

   if ($peticion) {
       $var = $peticion->fetch_assoc();

       return $var['Tipo'];
   }

   return false;
}
function editarUsuario($mysqli,$usuarioNuevo,$usuarioAntiguo)
{
   $consulta = "UPDATE `Usuarios` SET `Usuario`='$usuarioNuevo' WHERE `Usuario`='$usuarioAntiguo'";
   $resultado = $mysqli->query($consulta);
   return $resultado;
}
function editarNombre($mysqli,$nombreNuevo,$usuario)
{
   $consulta = "UPDATE `Usuarios` SET `Nombre`='$nombreNuevo' WHERE `Usuario`='$usuario'";
   $resultado = $mysqli->query($consulta);
   return $resultado;
}
function editarApellidos($mysqli,$apellidosNuevos,$usuario)
{
   echo("los nuevos apellidos son: $apellidosNuevos");
   $consulta = "UPDATE `Usuarios` SET `Apellidos`='$apellidosNuevos' WHERE `Usuario`='$usuario'";
   $resultado = $mysqli->query($consulta);
   return $resultado;
}
function editarMail($mysqli,$mailNuevo,$usuario)
{
   $consulta = "UPDATE `Usuarios` SET `Mail`='$mailNuevo' WHERE `Usuario`='$usuario'";
   $resultado = $mysqli->query($consulta);
   return $resultado;
}
function editarContraseña($mysqli,$contraseñaNueva,$usuario)
{
   $consulta = "UPDATE `Usuarios` SET `Contraseña`='$contraseñaNueva' WHERE `Usuario`='$usuario'";
   $resultado = $mysqli->query($consulta);
   return $resultado;
}
function getTodosComents($mysqli,$idEv)
{
   $comentario = [];
   $comentarios = [];
   
   $consulta = $mysqli->prepare("SELECT * FROM `COMENTARIOS` WHERE `IDproductos`=? ");
   $consulta->bind_param('i',$idEv);
   $consulta->execute();
   $resultado = $consulta->get_result();
   $i = 0;

   if($resultado->num_rows > 0)
   {
      
      while($row = $resultado->fetch_assoc()){
         $comentario = array('IDcomentarios'=>$row['IDcomentarios'],'Nombre' => $row['Nombre'],'IDproductos'=>$row['IDproductos'],'Correo'=>$row['Correo'],'Fecha'=>$row['Fecha'],'Hora'=>$row['Hora'],'Descripcion'=>$row['Descripcion'],'Modificado'=>$row['Modificado']);
         $comentarios[$i]=$comentario;
         $i++;
      
   }
   }
   $consulta->close();
   return $comentarios;
}
function getComentariosTotal($mysqli)
{
   $comentario = [];
   $comentarios = [];
   
   $consulta = $mysqli->prepare("SELECT * FROM `COMENTARIOS` ");
   $consulta->execute();
   $resultado = $consulta->get_result();
   $i = 0;

   if($resultado->num_rows > 0)
   {
      
      while($row = $resultado->fetch_assoc()){
         $comentario = array('IDcomentarios'=>$row['IDcomentarios'],'Nombre' => $row['Nombre'],'IDproductos'=>$row['IDproductos'],'Correo'=>$row['Correo'],'Fecha'=>$row['Fecha'],'Hora'=>$row['Hora'],'Descripcion'=>$row['Descripcion'],'Modificado'=>$row['Modificado']);
         $comentarios[$i]=$comentario;
         $i++;
      
   }
   }
   $consulta->close();
   return $comentarios;   
}
function mostrarComentario($comentario)
{
   $id = $comentario['IDcomentarios'];
   $texto = $comentario['Descripcion'];
   echo("El id del comentario es $id y la descripcion es $texto");
}
function getComentario($mysqli,$IDComentarios)
{
   $comentario = [];
   $consulta = $mysqli->prepare("SELECT * FROM COMENTARIOS WHERE IDcomentarios=? ");
   $consulta->bind_param("i",$IDComentarios);
   $consulta->execute();
   $resultado = $consulta->get_result();

   if($resultado->num_rows > 0)
   {
      $row = $resultado->fetch_assoc(); 
      $comentario = array('IDcomentarios'=>$row['IDcomentarios'],'Nombre' => $row['Nombre'],'IDproductos'=>$row['IDproductos'],'Correo'=>$row['Correo'],'Fecha'=>$row['Fecha'],'Hora'=>$row['Hora'],'Descripcion'=>$row['Descripcion'],'Modificado'=>$row['Modificado']);
   }
   $consulta->close();
   return $comentario;
}

function eliminarComentario($mysqli, $IDComentarios)
{
   $consulta = $mysqli->prepare("DELETE FROM `COMENTARIOS` WHERE `IDcomentarios`=?"); 
   $consulta->bind_param("i", $IDComentarios);
   $consulta->execute();
   $res = $consulta->get_result();
   return $res;
}
function editarNombreComentario($mysqli,$idComentario,$nombre){

   $consulta = $mysqli->prepare("UPDATE `COMENTARIOS` SET Nombre='$nombre',Modificado=1 WHERE IDcomentarios=?"); 
   $consulta->bind_param("i", $idComentario);

   $consulta->execute();
   $res = $consulta->get_result();
   return $res;
}
function editarMailComentario($mysqli,$idComentario,$mail){

   $consulta = $mysqli->prepare("UPDATE `COMENTARIOS` SET Correo='$mail',Modificado=1 WHERE IDcomentarios=?"); 
   $consulta->bind_param("i", $idComentario);

   $consulta->execute();
   $res = $consulta->get_result();
   return $res;
}
function editarTextoComentario($mysqli,$idComentario,$texto){

   $consulta = $mysqli->prepare("UPDATE `COMENTARIOS` SET Descripcion='$texto',Modificado=1 WHERE IDcomentarios=?"); 
   $consulta->bind_param("i", $idComentario);

   $consulta->execute();
   $res = $consulta->get_result();
   return $res;
}
function getTodosUsuarios($mysqli)
{
   $usuario = [];
   $usuarios = [];
   
   $consulta = "SELECT * FROM `Usuarios` WHERE 1";
   $resultado = $mysqli->query($consulta);
   $i = 0;
   if($resultado->num_rows > 0)
   {
      while($row = $resultado->fetch_assoc()){
         $usuario = array('ID'=>$row['ID'],'Nombre' => $row['Nombre'],'Apellidos'=>$row['Apellidos'],'Mail'=>$row['Mail'],'Usuario'=>$row['Usuario'],'Tipo'=>$row['Tipo']);
         $usuarios[$i]=$usuario;
         $i++;
      }
   }
   return $usuarios;
}
function getUsuarios($mysqli,$nickUsuario)
{
   $usuarios = [];
   
   $consulta = $mysqli->prepare("SELECT * FROM `Usuarios` WHERE `Usuario`=?");
   $consulta->bind_param("s",$nickUsuario);
   $consulta->execute();

   $resultado = $consulta->get_result();
   if($resultado->num_rows > 0)
   {
      $row = $resultado->fetch_assoc();
      $usuarios = array('ID'=>$row['ID'],'Nombre' => $row['Nombre'],'Apellidos'=>$row['Apellidos'],'Mail'=>$row['Mail'],'Usuario'=>$row['Usuario'],'Tipo'=>$row['Tipo']);
   }
   return $usuarios;
}

function cambiarARegistrado($mysqli,$nickUsuario)
{
   $usuarios = [];
   
   $consulta = $mysqli->prepare("UPDATE `Usuarios` SET `Tipo`='Registrado' WHERE `Usuario`=?");
   $consulta->bind_param("s",$nickUsuario);
   $consulta->execute();

   $resultado = $consulta->get_result();
   return $usuarios;
}
function cambiarAModerador($mysqli,$nickUsuario)
{
   $usuarios = [];
   
   $consulta = $mysqli->prepare("UPDATE `Usuarios` SET `Tipo`='Moderador' WHERE `Usuario`=?");
   $consulta->bind_param("s",$nickUsuario);
   $consulta->execute();

   $resultado = $consulta->get_result();
   return $usuarios;
}
function cambiarAGestor($mysqli,$nickUsuario)
{
   $usuarios = [];
   
   $consulta = $mysqli->prepare("UPDATE `Usuarios` SET `Tipo`='Gestor' WHERE `Usuario`=?");
   $consulta->bind_param("s",$nickUsuario);
   $consulta->execute();

   $resultado = $consulta->get_result();
   return $usuarios;
}
function cambiarASuper($mysqli,$nickUsuario)
{
   $usuarios = [];
   
   $consulta = $mysqli->prepare("UPDATE `Usuarios` SET `Tipo`='Superusuario' WHERE `Usuario`=?");
   $consulta->bind_param("s",$nickUsuario);
   $consulta->execute();

   $resultado = $consulta->get_result();
   return $usuarios;
}

function getProductos($mysqli)
{
   $producto = [];
   $productos = [];
   
   $consulta = "SELECT * FROM `Productos` WHERE 1";
   $resultado = $mysqli->query($consulta);
   $i = 0;
   if($resultado->num_rows > 0)
   {
      while($row = $resultado->fetch_assoc()){
         $producto = array('ID'=>$row['ID'],'Nombre' => $row['Nombre'],'Descripcion'=>$row['Descripcion'],'Imagen1'=>$row['Imagen1'],'Imagen2'=>$row['Imagen2'],'Publicado'=>$row['Publicado']);
         $productos[$i]=$producto;
         $i++;
      }
   }
   return $productos;
}
function eliminarComentariosEvento($mysqli,$idEvento){
   $consulta = $mysqli->prepare("DELETE FROM `COMENTARIOS` WHERE `IDproductos`=?"); 
   $consulta->bind_param("i", $idEvento);

   $consulta->execute();
   $res = $consulta->get_result();
   return $res;
  
}
function eliminarProducto($mysqli,$idProducto){

   eliminarComentariosEvento($mysqli,$idProducto);
   $consulta = $mysqli->prepare("DELETE FROM `Productos` WHERE `ID`=?");
   $consulta->bind_param('i',$idProducto);
   $consulta->execute();
   $res = $consulta->get_result();

   return
    $res;
}

function editNombreEvento($mysqli,$idEvento,$nombre) {
   $consulta = $mysqli->prepare("UPDATE `Productos` SET Nombre='$nombre' WHERE ID=?");
   $consulta->bind_param('i',$idEvento);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}


function editDescripcionEvento($mysqli,$idEvento,$descripcion) {
   $consulta = $mysqli->prepare("UPDATE `Productos` SET Descripcion='$descripcion' WHERE ID=?");
   $consulta->bind_param('i',$idEvento);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}

function editImagen1Evento($mysqli,$idEvento,$descripcion) {
   $consulta = $mysqli->prepare("UPDATE `Productos` SET Imagen1='$descripcion' WHERE ID=?");
   $consulta->bind_param('i',$idEvento);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}
function editImagen2Evento($mysqli,$idEvento,$descripcion) {
   $consulta = $mysqli->prepare("UPDATE `Productos` SET Imagen1='$descripcion' WHERE ID=?");
   $consulta->bind_param('i',$idEvento);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}

function publicarEvento($mysqli,$idEvento){
   $consulta = $mysqli->prepare("UPDATE `Productos` SET `Publicado`=1 WHERE id=?");
   $consulta->bind_param('i',$idEvento);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}
function ocultarEvento($mysqli,$idEvento){
   $consulta = $mysqli->prepare("UPDATE `Productos` SET `Publicado`=0 WHERE id=?");
   $consulta->bind_param('i',$idEvento);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}
function aniadirProducto($mysqli,$IDnuevo,$nombre,$descripcion,$Imagen1,$Imagen2,$publicado){
   $consulta = $mysqli->prepare("INSERT INTO `Productos`(`ID`, `Nombre`, `Descripcion`, `Imagen1`, `Imagen2`, `Publicado`) VALUES (?,?,?,?,?,?)");
   $consulta->bind_param('issssi',$IDnuevo,$nombre,$descripcion,$Imagen1,$Imagen2,$publicado);

   $consulta->execute();
   $res = $consulta->get_result();      

   return $res;
}
function nuevoComentario($mysqli,$idEv,$nombre,$mail,$texto){
   
   $fecha = date("Y-m-d");
   $hora = date("H:i:s");
   $modif = 0;
   $consulta=$mysqli->prepare("INSERT INTO `COMENTARIOS`(`IDproductos`, `Nombre`, `Correo`, `Fecha`, `Descripcion`, `Hora`, `Modificado`) VALUES (?,?,?,?,?,?,?)");
   $consulta->bind_param("isssssi",$idEv,$nombre,$mail,$fecha,$texto,$hora,$modif);
   $consulta->execute();
   $res = $consulta->get_result();
   $consulta->close();
   
   return $res;

}
function addEtiqueta($mysqli,$texto,$idEvento){

   $consulta = $mysqli->prepare("INSERT INTO `Etiquetas`(`Producto`, `texto`) VALUES (?,?)");
   $consulta->bind_param('is',$idEvento,$texto);

   $consulta->execute();
   $res = $consulta->get_result();

   return $res;
}

?>