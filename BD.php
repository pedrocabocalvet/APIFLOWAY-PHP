<?php

/**
 *  Base de datos para manejo de la información
 */
class BD
{
    // atributos
    private $host = "localhost";
    private $bd="flowaydatabase";
    private $user = "root";
    private $pw = "456uni9.L";

    private $mysqli;

    //cadena select all usuarios
    private $usuarios_all="SELECT * FROM usuario";
    private $usuarios_insert="INSERT INTO usuario ( nombre, apellidos, usuario, password, poblacion, cp, puntuacion, horario, foto, conductor, conectado, longitud, latitud) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    private $usuarios_delete = "DELETE FROM usuario WHERE nombre = ";


   
  
  function __construct()
  {

    // esto lo hacemos para realizar querys
    $this->mysqli = new mysqli($this->host, $this->user, $this->pw, $this->bd);
    mysqli_query($this->mysqli,"SET NAMES utf8");
    mysqli_set_charset("utf8");
      if (mysqli_connect_errno()) {
         printf("Error de conexion: %s\n", mysqli_connect_error());
          exit();
      }
  }

  function comprobarUsuario($usuarioComprobar){

      
      $respuesta;

      
      $usuarios = $this->getUsuarios($usuarioComprobar);

      $numero = count($usuarios);

      if($numero==0)
        $respuesta = "false";
      else
        $respuesta = "true";
    
      return "{existe:".$respuesta."}";
  }
  
 
  function getUsuarioId($whereId=null){

       $usuarios=[];
       $consulta = "SELECT * FROM usuario WHERE id_usuario = ".$whereId;
       
       mysqli_query('SET CHARACTER SET utf8');

       if($result = mysqli_query($this->mysqli,$consulta)){

            while ($usuario = mysqli_fetch_assoc($result)){

                $usuarios[] = $usuario;

          }

            $result->close();
            return $usuarios;

       }

  }


  function getUsuarios($whereUsuario=null){

    if($whereUsuario != null){
      $this->usuarios_all = "SELECT * FROM usuario WHERE usuario like '".$whereUsuario ."'";
      
    }

    $usuarios=[];


    if($result = mysqli_query($this->mysqli,$this->usuarios_all)){

	
	    while ($usuario = mysqli_fetch_assoc($result)){

		    $usuarios[] = $usuario;	
        }	


        $result->close();

        return $usuarios;

    }else{
        echo "ERROR en la consulta " .$result;
    }
  }












  function filtrarUsuarios($radio, $conductor, $conectado, $longitud, $latitud){

      //echo $radio . " " . $conductor . " " . $conectado . " " . $longitud . " " . $latitud;

      $query = "SELECT * FROM usuario WHERE conectado = " . $conectado ." and conductor = " . $conductor;

      $usuarios=[];
      

      if($result = mysqli_query($this->mysqli,$query)){

          while($usuario = mysqli_fetch_assoc($result)){

               if(distanceCalculation($latitud, $longitud, $usuario["latitud"], $usuario["longitud"]) <= $radio){
                          $usuarios[] = $usuario; 
               }

          }

      }
        
    // var_dump($usuarios);

      $result->close();

      return $usuarios;

  }

  function incrementarPuntuacion($usuario,$puntuacion){
       //echo $usuario . " " . $puntuacion;
       $valor=[];
       $consulta = "SELECT puntuacion FROM usuario WHERE id_usuario= ".$usuario;
       
       mysqli_query('SET CHARACTER SET utf8');
       
       if($result = mysqli_query($this->mysqli,$consulta)){

           while ($p = mysqli_fetch_assoc($result)){
                $valor = $p;
           }
           $result->close();
       }
       $puntuacionanterior = $valor["puntuacion"];
       //echo $puntuacionanterior. " puntuacion anterior puntuacion usuario ".puntuacion." suma ".($puntuacionAnterior + $puntuacion);
       $puntuacionNueva = ($puntuacionanterior + $puntuacion);

       mysqli_query($this->mysqli,"UPDATE usuario SET puntuacion=".$puntuacionNueva." WHERE id_usuario = '".$usuario."'");
       printf("Filas afectadas por el Update: %d\n",$this->mysqli->affected_rows);

  }


  function deleteUsuario($usuario){

      mysqli_query($this->mysqli,$this->usuarios_delete . "'" . $usuario . "'");
      printf("Filas afectadas por el delete: %d\n", $this->mysqli->affected_rows);

      $this->mysqli->close();

  }

                       // $nombre,$apellidos,$usuario,$password,$poblacion,$codigoPostal,$puntuacion,$chooseone, $data
  function insertUsuario($nombre,$apellidos,$usuario,$password,$poblacion,$cp,$puntuacion,$horario,$data){
    //Utilizamos sentecias preparadas

  //echo  $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $poblacion . " " . $cp . " ". $puntuacion . " " . $horario ;    


    // aqui cargamos la query hecha antes

	mysql_query("SET CHARACTER SET utf8");
   
    $stmt = $this->mysqli->prepare($this->usuarios_insert);
   
   $conductor = 1;
   $conectado = 0;
   $longitud = 0.0;
   $latitud = 0.0;


    // aqui le pasamos los valores a la query, el primer parametro es una letra que hace referencia al tipo de archivo que le estamos pasando cada letra corresponde a uno de los siguientes parametros por orden
                    // true = 1      false = 0
    $stmt->bind_param('ssssssissiidd',$nombre,$apellidos,$usuario,$password,$poblacion,$cp,$puntuacion,$horario,$data,$conductor,$conectado,$longitud,$latitud);
    
    
    
 
    $stmt->execute();
    $last_id = $stmt->insert_id;
    $stmt->close();

    if($this->mysqli->affected_rows == -1){
     // printf("Usuario insertado");
      
      echo $last_id;
    }
    else{
      printf("No ha sido posible insertar el usuario");
    }
    $this->mysqli->close();

  }

  function prueba($palabra,$palabra2){
    echo "llego2 ".$palabra;
     $prueba_insert="INSERT INTO prueba (nombre,apellidos) VALUES (?,?)";

    $stmt = $this->mysqli->prepare($prueba_insert);
     $stmt->bind_param('ss',$palabra,$palabra2);
      $stmt->execute();
    $stmt->close();

    //echo "INSERT INTO prueba values ('".$palabra."');";
    // mysqli_query($this->mysqli,"INSERT INTO prueba values ('".$palabra."'');");
  }

  function modificarConexionUsuario($conectado, $usuario){

     mysqli_query($this->mysqli," UPDATE usuario SET conectado= '" .$conectado . "' WHERE id_usuario = '" . $usuario ."'");
    
    printf("Filas afectadas por el Update: %d\n",$this->mysqli->affected_rows);
  
  }
  

  function modificarConductorUsuario($conductor, $usuario){
    
     mysqli_query($this->mysqli," UPDATE usuario SET conductor= '" . $conductor . "' WHERE id_usuario = '" .$usuario ."'");
     printf("Filas afectadas por el Update: %d\n",$this->mysqli->affected_rows);

  }


  function modificarLongitudLatitudUsuario($longitud,$latitud,$usuario){
  
    mysqli_query($this->mysqli," UPDATE usuario SET longitud= '" .$longitud . "', latitud= '". $latitud . "' WHERE id_usuario = '" . $usuario ."'");
    printf("Filas afectadas por el Update: %d\n",$this->mysqli->affected_rows);

  }



  function modificarUsuario($oldUser,$nombre,$apellidos,$usuario,$password,$poblacion,$cp,$horario, $data){
      // echo $oldUser . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $poblacion . " " . $cp . " " . $horario . " " .$data ." fin";

      //echo $oldUser . "id usuario ";

      if(isset($nombre) && !empty($nombre))

         mysqli_query($this->mysqli," UPDATE usuario SET nombre= '" . $nombre . "' WHERE id_usuario = '" .$oldUser ."'");

      if(isset($apellidos) && !empty($apellidos))

         mysqli_query($this->mysqli," UPDATE usuario SET apellidos= '" . $apellidos . "' WHERE id_usuario = '" .$oldUser ."'");

      if(isset($usuario) && !empty($usuario)){

         mysqli_query($this->mysqli," UPDATE usuario SET usuario= '" . $usuario . "' WHERE id_usuario = '" .$oldUser ."'");
         
      }

      if(isset($password) && !empty($password)){
        
        $consulta = "UPDATE usuario SET password ='" .$password ."' WHERE id_usuario = '" .$oldUser ."'";
        //echo $consulta ." ";
         mysqli_query($this->mysqli,$consulta);
        //echo "entro".$password;
      }
      if(isset($poblacion) && !empty($poblacion))

         mysqli_query($this->mysqli," UPDATE usuario SET poblacion= '" . $poblacion . "' WHERE id_usuario = '" .$oldUser ."'");

      if(isset($cp) && !empty($cp))

         mysqli_query($this->mysqli," UPDATE usuario SET cp= '" . $cp . "' WHERE id_usuario = '" .$oldUser ."'");

       if(isset($horario) && !empty($horario))

         mysqli_query($this->mysqli," UPDATE usuario SET horario= '" . $horario . "' WHERE id_usuario = '" .$oldUser ."'");

       if(isset($data)){

         mysqli_query($this->mysqli," UPDATE usuario SET foto= '" . $data . "' WHERE id_usuario = '" .$oldUser ."'");
         //echo "entro";
        }

       printf("Filas afectadas por el Update: %d\n", $this->mysqli->affected_rows);
  }


}



function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km' ,$decimals = 2){

    //echo " 1 lat: " . $point1_lat . " 1 lang: " . $point1_lang . " 2 lat: " . $point2_lat . " 2 lang: " . $point2_lang . "\n" ;

    $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

    switch($unit){
     
     case 'km':
          $distance = $degrees * 111.13384;
     break;

     case 'mi':
          $distance = $degrees * 69.05482;
     break;

     case 'nmi':
          $distance = $degrees * 59.97662;

    }

    //echo "la distancia entre lat: ".$point1_lat." long: ".$point1_long. " y lat2: ".$point2_lat." long: ".$point2_long."es de ". round($distance,$decimals) . "\n";

    return round($distance, $decimals);


}












 ?>
