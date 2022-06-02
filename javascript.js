contador = 2;
function incluirComent(){
   var d = new Date();
   var fecha = d.getUTCDate() + "/" + d.getMonth() + "/" + d.getFullYear();
   var hora = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();

   var fechaTotal = fecha + " - " + hora;

   var comentario=[];
   var apellidos = [];
   var nombre=[];
   var correo=[];
   
   nombre = document.getElementById("name").value;
   correo = document.getElementById("mail").value;
   comentario = document.getElementById("msg").value;
   if(pruebaemail(correo) ==false)
   {
      alert("Email no valido");
      return false;
   }
   if (nombre == "" || correo == "" || comentario == "") {
       alert("Faltan datos");
       return false;
   } else {
       var secComentarios = document.getElementById("seccionComentarios");
       var divAniadir = document.createElement("div");
       var cabeceraComment = document.createElement("h3");
       var nombreCabecera = document.createElement("h3");
       var textoComentario = document.createElement("p");

      cabeceraComment.innerHTML= fechaTotal;
      nombreCabecera.innerHTML = nombre;
      textoComentario.innerHTML=comentario;
      divAniadir.id="comentario"+(contador+1);
      divAniadir.className="comentario";

      var ultimoComent= document.getElementById("comentario"+contador);
      divAniadir.appendChild(cabeceraComment);
      divAniadir.appendChild(nombreCabecera);
      divAniadir.appendChild(textoComentario);
      secComentarios.insertBefore(divAniadir,ultimoComent);
      contador++;

      nombre = document.getElementById("name").value="";
      correo = document.getElementById("mail").value="";
      comentario = document.getElementById("msg").value="";

   }
}
function sustituirAsteriscos(palabra)
{
   console.log("entra");
   var palabraNueva = [];
   for(i= 0; i < palabra.length; i++)
   {
      palabraNueva+='*';
   }
   return palabraNueva;
}
function sustituirPalabras(palabra) {  
         
   var palabrasProhibidas = document.getElementsByClassName("palabrasProhibidas");
   var palabrasMalSonantes = [];
   var i=0;
   var j=0;

   for(i=0; i<palabrasProhibidas.length; i++)
   {
      palabrasMalSonantes.push(palabrasProhibidas[i].id);
   }
   var mensaje = document.getElementById("msg").value;
   for(i=0; i<palabrasMalSonantes.length;i++)
   {
      if(mensaje.match(palabrasMalSonantes[i]))
      {
         var palabraAsteriscos =""
         var palabraAux = palabrasMalSonantes[i].length;
         for(j=0; j < palabraAux; j++)
         {
            palabraAsteriscos+='*';
         }
         mensaje = mensaje.replace(palabrasMalSonantes[i],palabraAsteriscos);
         document.getElementById("msg").value = mensaje;
      }
   }
}
function myFunction() {
   var x = document.getElementById("seccionComentarios");
   if (x.style.display == "") {
      x.style.display = "block";
   } else {
      x.style.display = "";
   }

}
function pruebaemail(valor) {
   re = /^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/
   if (!re.test(valor)) {
      return false;
   }
   return true;
}