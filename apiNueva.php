<?php

require_once("./BD.php");
require_once("./jsonResponse.php");

// la super global de dentro del switch permite saber de que manera esta enviado la url, si con get, post, delete o put
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':

		if (isset($_GET["api_key"]) && $_GET["api_key"] == "HDRYsemQRQRPRT") {
			
			if(isset($_GET["where"])){

                                        $bd = new BD();
					$js = new jsonResponse();
					$users = $bd->getUsuarios($_GET["where"]); 

					$js->setData($users);
					echo $js->json_response();
			
                        }elseif(isset($_GET["whereid"])){

                                        $bd = new BD();
                                        $js = new jsonResponse();
                                        $user = $bd->getUsuarioId($_GET["whereid"]);
                                       
                                        $js->setData($user);
                                        echo $js->json_response();

		
			}elseif(isset($_GET["usuario"])){

					$bd = new BD();
					$js = new jsonResponse();
					$hayUsuarios = $bd->comprobarUsuario($_GET["usuario"]);

					

					echo $hayUsuarios;

			}elseif(isset($_GET["radio"])){
                                        
                                        $conectado = $_GET["conectado"];
                                        $conductor = $_GET["conductor"];
                                        $longitud = $_GET["longitud"];
                                        $latitud = $_GET["latitud"];
                                        $radio = $_GET["radio"];

                                        //echo $conectado . $conductor . $longitud . $latitud . $radio;
                                        $bd = new BD();
                                        $js = new jsonResponse();

                                        $usuariosfiltrados = $bd->filtrarUsuarios($radio,$conductor,$conectado,$longitud,$latitud);
                                        
                                        //echo "vuelvo";

                                        $js->setData($usuariosfiltrados);
                                        echo $js->json_response();

                        }else{
					$bd = new BD();
					$js = new jsonResponse();
					$users = $bd->getUsuarios();
					
					
					$js->setData($users);
				
					echo $js->json_response();
					exit();
					
				}
		}else{
			echo "fallo api key";
		}

		break;
	
	case 'PUT':

           if(isset($_GET["api_key"]) && $_GET["api_key"] == "HDRYsemQRQRPRT"){
	// este metodo lo que hace es guardar en $dadesRebudes el los parametros q vienen por url ya sea delete put post o get
		parse_str(file_get_contents("php://input"),$dadesRebudes);
		
		if(isset($dadesRebudes["olduser"]) && !empty($dadesRebudes["olduser"])){
                 
                     if(isset($_GET["conectado"])){
                            
                        $conectado = $dadesRebudes["conectado"];
                        $usuario = $dadesRebudes["olduser"];

                        $bd = new BD();
                        $bd->modificarConexionUsuario($conectado,$usuario);

                     }else if(isset($_GET["conductor"])){
                        
                        $conductor = $dadesRebudes["conductor"];
                        $usuario = $dadesRebudes["olduser"];

                        $bd = new BD();
                        $bd->modificarConductorUsuario($conductor,$usuario);

                     }else if(isset($_GET["cambiarlocalizacion"])){

                        $longitud = $dadesRebudes["longitud"];
                        $latitud = $dadesRebudes["latitud"];
                        $usuario = $dadesRebudes["olduser"];

                        $bd = new BD();
                        $bd->modificarLongitudLatitudUsuario($longitud, $latitud, $usuario);
 
                     }else if(isset($_GET["puntuacion"])){

                              $puntuacion = $dadesRebudes["puntuacion"];
                              $usuario = $dadesRebudes["olduser"];
                              
                              $bd = new BD();
                              $bd->incrementarPuntuacion($usuario,$puntuacion);

                     }else{                 

			$oldUser = $dadesRebudes["olduser"];
			$nombre = $dadesRebudes["nombre"];
			$apellidos = $dadesRebudes["apellidos"];
			$usuario = $dadesRebudes["usuario"];
			$password = $dadesRebudes["password"];	
			$poblacion = $dadesRebudes["poblacion"];
			$codigoPostal = $dadesRebudes["codigoPostal"];	
                        $horario = $dadesRebudes["horario"];

			//$data = cogerFoto($_FILES["foto"]);
			$data = $dadesRebudes["foto"];
			
                        $bd = new BD();
                        $bd->modificarUsuario($oldUser,$nombre,$apellidos,$usuario,$password,$poblacion,$codigoPostal, $horario, $data);
			

                   }
		}
		else{
			
			echo "tienes que escribir el usuario ha modificar" ;
		}

           }else{
                echo "fallo api key";
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
		//$id = 3;

            if(isset($_GET["api_key"]) && $_GET["api_key"]== "HDRYsemQRQRPRT"){

		$nombre = $_POST["nombre"];
		$apellidos = $_POST["apellidos"];

		$usuario = $_POST["usuario"];
		$password = $_POST["password"];	

		$poblacion = $_POST["poblacion"];
		$codigoPostal = $_POST["codigoPostal"];	

		$puntuacion = $_POST["puntuacion"];

		$chooseone = $_POST["horario"];

		

		//$data = cogerFoto($_FILES["foto"]);
		$data = $_POST["data"];
		
	
		//echo  $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $poblacion . " " . $codigoPostal . " "  . $puntuacion . " " . $chooseone ;    

		

		$bd = new BD();
		$bd->insertUsuario($nombre,$apellidos,$usuario,$password,$poblacion,$codigoPostal,$puntuacion,$chooseone,$data);
	   }else{
		echo "fallo api key";
           }

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
