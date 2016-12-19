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
	

	if(isset($_POST['chooseone']) && !empty($_POST['chooseone'])){
		$chooseone = $_POST["chooseone"];
	}else{
		$chooseone = "manana";
	}

	//$foto = $_POST["foto"];
/*
	echo $id . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $codigoPostal . " " . $email . " " . $puntuacion . " " . $chooseone ;    */ 

	

	$bd = new BD();
	$bd->insertUsuario($id,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$codigoPostal,$email,$puntuacion,$chooseone);

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
		

		//echo $oldUser . " " . $id . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $codigoPostal . " " . $email . " " . $puntuacion . " " . $chooseone ; 



		$bd = new BD();
		$bd->modificarUsuario($oldUser, $id,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$codigoPostal,$email,$puntuacion,$chooseone);

	}
	else{
		
		echo "tienes que escribir el usuario ha modificar" ;
	}
}



?>