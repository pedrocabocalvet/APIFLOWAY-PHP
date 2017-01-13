<?php

include "BD.php";
include "jsonResponse.php";

// la super global de dentro del switch permite saber de que manera esta enviado la url, si con get, post, delete o put
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
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
	
	case 'PUT':
	// este metodo lo que hace es guardar en $dadesRebudes el los parametros q vienen por url ya sea delete put post o get
		parse_str(file_get_contents("php://input"),$dadesRebudes);
		
		if(isset($dadesRebudes["olduser"]) && !empty($dadesRebudes["olduser"])){

			$oldUser = $dadesRebudes["olduser"];

			$id = "";

			$nombre = $dadesRebudes["nombre"];
			$apellidos = $dadesRebudes["apellidos"];
			$usuario = $dadesRebudes["usuario"];
			$password = $dadesRebudes["password"];	
			$nif = $dadesRebudes["nif"];
			$direccion = $dadesRebudes["direccion"];
			$poblacion = $dadesRebudes["poblacion"];
			$codigoPostal = $dadesRebudes["codigoPostal"];	
			$email = $dadesRebudes["email"];
			$puntuacion = $dadesRebudes["puntuacion"];

			if(isset($dadesRebudes['chooseone']) && !empty($dadesRebudes['chooseone'])){
				$chooseone = $dadesRebudes["chooseone"];
			}else{
				$chooseone = "";
			}

			//$data = cogerFoto($_FILES["foto"]);
			$data = "";
			

			//echo $oldUser . " " . $id . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $codigoPostal . " " . $email . " " . $puntuacion . " " . $chooseone ; 



			$bd = new BD();
			$bd->modificarUsuario($oldUser, $id,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$codigoPostal,$email,$puntuacion,$chooseone, $data);

		}
		else{
			
			echo "tienes que escribir el usuario ha modificar" ;
		}
	break;

	case 'DELETE':
	// este metodo lo que hace es guardar en $dadesRebudes el los parametros q vienen por url ya sea delete put post o get
		parse_str(file_get_contents("php://input"),$dadesRebudes);

		if(isset($dadesRebudes["nombre"])){

			$usuario = $dadesRebudes["nombre"];

			$bd = new BD();
			$bd->deleteUsuario($usuario);

		}else{
			echo "tienes que poner un usuario para que lo borre";
		}


	break;

	case 'POST':
		//$id = $_POST["id"];
		$id = 3;

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

		//$data = cogerFoto($_FILES["foto"]);
		$data = "";
		
	
		/*echo $id . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $codigoPostal . " " . $email . " " . $puntuacion . " " . $chooseone ;    */

		

		$bd = new BD();
		$bd->insertUsuario($id,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$codigoPostal,$email,$puntuacion,$chooseone, $data);
	break;
}

// funcion que le pasas un archivo con la foto y retorna una variable en binario para pasarsela a la bbdd, despues de comprobar 
// formatos validos y tamaño de la foto permitido

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