<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<h1>FORMULARIO</h1>

<form id="formulario" enctype="multipart/form-data" action="api.php" method="post">

<label>id</label>
<input id="campo4" name="id" type="text" />
<br>
<label>Nombre</label>
<input id="campo1" name="nombre" type="text" />
<br>
<label>Apellidos</label>
<input id="campo2" name="apellidos" type="text" />
<br>
<label>Usuario</label>
<input id="campo3" name="usuario" type="text" />
<br>
<label>password</label>
<input id="campo5" name="password" type="text" />
<br>
<label>nif</label>
<input id="campo6" name="nif" type="text" />
<br>
<label>direccion</label>
<input id="campo7" name="direccion" type="text" />
<br>
<label>poblacion</label>
<input id="campo8" name="poblacion" type="text" />
<br>
<label>cp</label>
<input id="campo9" name="codigoPostal" type="text" />
<br>
<label>email</label>
<input id="campo10" name="email" type="text" />
<br>
<label>puntuacion</label>
<input id="campo11" name="puntuacion" type="text" />
<br>

<label>nombre a modificar con los datos de arriba</label>
<input id="campo12" name="olduser" type="text" />
<br>
<p>Elige Opción</p>
 <input type="radio" name="chooseone" value="manana"><label> mañana</label><br>
 <input type="radio" name="chooseone" value="tarde"><label> tarde</label><br>
<br>
<input name="foto" type="file" />
<br>

<input type="submit" name="enviar" id="enviar" value="Enviar" />
<input type="submit" name="borrar" id="borrar" value="Borrar" />
<input type="submit" name="modificar" id="modificar" value="Modificar" />


</form>


</body>
</html>