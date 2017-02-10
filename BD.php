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
    private $pw = "";

    private $mysqli;

    //cadena select all usuarios
    private $usuarios_all="SELECT *  FROM usuario";
    private $usuarios_insert="INSERT INTO usuario ( nombre, apellidos, usuario, password, poblacion, cp, puntuacion, horario, foto) VALUES (?,?,?,?,?,?,?,?,?)";
    private $usuarios_delete = "DELETE FROM usuario WHERE nombre = ";


   
  
  function __construct()
  {

    // esto lo hacemos para realizar querys
    $this->mysqli = new mysqli($this->host, $this->user, $this->pw, $this->bd);

      if (mysqli_connect_errno()) {
          printf("Error de conexión: %s\n", mysqli_connect_error());
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


  function getUsuarios($whereUsuario=null){

    if($whereUsuario != null){
      $this->usuarios_all = "SELECT * FROM usuario WHERE usuario like '".$whereUsuario ."'";
      
    }

    $usuarios=[];

    /* consultas de seleccion que devuelven un conjunto de resultados */

    if($result = mysqli_query($this->mysqli,$this->usuarios_all)){
      while($usuario = mysqli_fetch_assoc($result)){
        // gracias a esta linea conseguimos parsear a json las ñ y los acentos
        $usuarios[]=array_map('utf8_encode',$usuario);
        
      }
      /*liberar el conjunto de resultados */


      $result->close();

      return $usuarios;

    }else{
      echo "ERROR en la consulta " .$result;
    }
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
    $stmt = $this->mysqli->prepare($this->usuarios_insert);
    // aqui le pasamos los valores a la query, el primer parametro es una letra que hace referencia al tipo de archivo que le estamos pasando cada letra corresponde a uno de los siguientes parametros por orden
                    
    $stmt->bind_param('ssssssiss',$nombre,$apellidos,$usuario,$password,$poblacion,$cp,$puntuacion,$horario,$data);
    

    $stmt->execute();
    $stmt->close();

    if($this->mysqli->affected_rows == -1){
      printf("Usuario insertado");
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




  function modificarUsuario($oldUser,$idusuario,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$cp,$email,$puntuacion,$horario, $data){
       //echo $oldUser . " " . $idusuario . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $cp . " " . $email . " " . $puntuacion . " " . $horario;

      if(isset($puntuacion) && !empty($puntuacion))

         mysqli_query($this->mysqli," UPDATE usuario SET puntuacion= '" . $puntuacion . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($nombre) && !empty($nombre))

         mysqli_query($this->mysqli," UPDATE usuario SET nombre= '" . $nombre . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($apellidos) && !empty($apellidos))

         mysqli_query($this->mysqli," UPDATE usuario SET apellidos= '" . $apellidos . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($usuario) && !empty($usuario)){

         mysqli_query($this->mysqli," UPDATE usuario SET usuario= '" . $usuario . "' WHERE usuario = '" .$oldUser ."'");
         $oldUser = $usuario;
      }

      if(isset($password) && !empty($password))

         mysqli_query($this->mysqli," UPDATE usuario SET password= '" . $password . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($nif) && !empty($nif))

         mysqli_query($this->mysqli," UPDATE usuario SET nif= '" . $nif . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($direccion) && !empty($direccion))

         mysqli_query($this->mysqli," UPDATE usuario SET direccion= '" . $direccion . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($poblacion) && !empty($poblacion))

         mysqli_query($this->mysqli," UPDATE usuario SET poblacion= '" . $poblacion . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($cp) && !empty($cp))

         mysqli_query($this->mysqli," UPDATE usuario SET cp= '" . $cp . "' WHERE usuario = '" .$oldUser ."'");

      if(isset($email) && !empty($email))

         mysqli_query($this->mysqli," UPDATE usuario SET email= '" . $email . "' WHERE usuario = '" .$oldUser ."'");

       if(isset($horario) && !empty($horario))

         mysqli_query($this->mysqli," UPDATE usuario SET horario= '" . $horario . "' WHERE usuario = '" .$oldUser ."'");

       if(isset($data)){

         mysqli_query($this->mysqli," UPDATE usuario SET foto= '" . $data . "' WHERE usuario = '" .$oldUser ."'");
         echo "entro";
        }

       printf("Filas afectadas por el Update: %d\n", $this->mysqli->affected_rows);
  }


}





 ?>