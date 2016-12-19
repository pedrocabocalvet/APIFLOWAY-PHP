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
    private $usuarios_insert="INSERT INTO usuario ( nombre, apellidos, usuario, password, nif, direccion, poblacion, cp, email, puntuacion, horario) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    private $usuarios_delete = "DELETE FROM usuario WHERE nombre = ";


   
  
  function __construct()
  {

    // este lo uso para el insert
    $this->mysqli = new mysqli($this->host, $this->user, $this->pw, $this->bd);

      if (mysqli_connect_errno()) {
          printf("Error de conexión: %s\n", mysqli_connect_error());
          exit();
      }
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


  function insertUsuario($idusuario,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$cp,$email,$puntuacion,$horario){
    //Utilizamos sentecias preparadas

 // echo $idusuario . " " . $nombre . " " . $apellidos . " " . $usuario . " " . $password . " " . $nif . " " . $direccion . " " . $poblacion . " " . $cp . " " . $email . " " . $puntuacion . " " . $horario ;    

    


    $stmt = $this->mysqli->prepare($this->usuarios_insert);
    $stmt->bind_param('sssssssssis',$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$cp,$email,$puntuacion,$horario);
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




  function modificarUsuario($oldUser,$idusuario,$nombre,$apellidos,$usuario,$password,$nif,$direccion,$poblacion,$cp,$email,$puntuacion,$horario){
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

       printf("Filas afectadas por el Update: %d\n", $this->mysqli->affected_rows);
  }


}





 ?>