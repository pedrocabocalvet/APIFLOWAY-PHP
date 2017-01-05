<?php

include "BD.php";
include "jsonResponse.php";

if(isset($_GET["accion"])){
	
	switch ($_GET["accion"]) {
		case 'all_users':

			if(isset($_GET["where"])){
				$bd = new BD();
				$js = new jsonResponse();
				$users = $bd->getUsuarios($_GET["where"]);
				$js->setData($users);
				echo $js->json_response();
				
			}else{
				$bd = new BD();
				$js = new jsonResponse();
				$users = $bd->getUsuarios();
				$js->setData($users);
				echo $js->json_response();
				
			}
		break;



	}	 


}

// si apreta el boton enviar significa insertar
if ($_POST && isset($_POST["enviar"])){

	$id = $_POST["id"];

	$nombre = $_POST["nombre"];
	$apellidos = $_POST["apellidos"];
	$usuario = $_POST["usuario"];
	$password = $_POST["password"];	
	$nif = $_POST["nif"];
	$direccion = $_POST["direccion"];
	$poblacion = $_POST["poblacion"];
	$codigoPostal = $_POST["codigoPostal"];	
	$email = $_POST["email"];
	$puntuacion = $_POST["puntuacion"];
	//$data = null;
	

	if(isset($_POST['chooseone']) && !empty($_POST['chooseone'])){
		$chooseone = $_POST["chooseone"];
	}else{
		$chooseone = "manana";
	}

	$data = cogerFoto($_FILES["foto"]);
	
	
/*
	echo $id . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $codigoPostal . " " . $email . " " . $puntuacion . " " . $chooseone ;    */ 

	

	$bd = new BD();
	$bd->insertUsuario($id,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$codigoPostal,$email,$puntuacion,$chooseone, $data);

// si apreta el boton borrar significa delete
}else if($_POST && isset($_POST["borrar"])){
	
	if(isset($_POST["nombre"])){

		$usuario = $_POST["nombre"];

		$bd = new BD();
		$bd->deleteUsuario($usuario);

	}else{
		echo "tienes que poner un usuario para que lo borre";
	}
}
// esto es modificar
else{
	
	if(isset($_POST["olduser"]) && !empty($_POST["olduser"])){

		$oldUser = $_POST["olduser"];

		$id = $_POST["id"];

		$nombre = $_POST["nombre"];
		$apellidos = $_POST["apellidos"];
		$usuario = $_POST["usuario"];
		$password = $_POST["password"];	
		$nif = $_POST["nif"];
		$direccion = $_POST["direccion"];
		$poblacion = $_POST["poblacion"];
		$codigoPostal = $_POST["codigoPostal"];	
		$email = $_POST["email"];
		$puntuacion = $_POST["puntuacion"];

		if(isset($_POST['chooseone']) && !empty($_POST['chooseone'])){
			$chooseone = $_POST["chooseone"];
		}else{
			$chooseone = "";
		}

		$data = cogerFoto($_FILES["foto"]);
		
		

		//echo $oldUser . " " . $id . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $codigoPostal . " " . $email . " " . $puntuacion . " " . $chooseone ; 



		$bd = new BD();
		$bd->modificarUsuario($oldUser, $id,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$codigoPostal,$email,$puntuacion,$chooseone, $data);

	}
	else{
		
		echo "tienes que escribir el usuario ha modificar" ;
	}
}


function cogerFoto($foto){
	$data = "";


	if(!isset($foto) || $foto["error"] > 0){
		echo "no hay ninguna foto seleccionada";
	}else{
		$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
		$limite_kb = 65;

		if(in_array($foto['type'],$permitidos) && $foto['size'] <= $limite_kb * 1024){
			//este es el archivo temporal
			$imagen_temporal  = $foto['tmp_name'];
			//este es el tipo de archivo
			$tipo = $foto['type'];	// en este ej no hace falta pero lo he copiado para saberlo
			//leer el archivo temporal en binario
	        $fp = fopen($imagen_temporal, 'r+b');
	        $data = fread($fp, filesize($imagen_temporal));
	        fclose($fp);

	        //escapar los caracteres
            $data = mysql_escape_string($data);
		}else{
			echo "Archivo no permitido, formato no permitido o sobrepasa los 65kb";
		}

		
	}

	return $data;
} 


?>