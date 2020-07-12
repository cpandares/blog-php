<?php



if(isset($_POST)){
    require 'includes/conexion.php';

    if(!isset($_SESSION)){
    session_start();
}   
                    //mysqli_real_escape_string me permite escapar datos pra proteger la BD  
    $nombre = isset($_POST['nombre']) ? mysqli_real_escape_string($db, $_POST['nombre']): false;
    $apellidos = isset($_POST['apellidos']) ? mysqli_real_escape_string($db,  $_POST['apellidos'] ) : false;
    $email = isset($_POST['email']) ? mysqli_real_escape_string($db,  $_POST['email']): false;
    $password =isset($_POST['password']) ? mysqli_real_escape_string($db,  $_POST['password']):false;


$errores=array();
//VALIDADOS EL NOMBRE
if(!empty($nombre) && !is_numeric($nombre) && !preg_match("/[0-9]/", $nombre)){
   $nombre_validado=true;
  }else{
      $nombre_validado=false;
      $errores['nombre'] = "El nombre no es Valido";
  }
//VALIDANDO APELLIDOS
  if(!empty($apellidos) && !is_numeric($apellidos) && !preg_match("/[0-9]/", $apellidos)){
     $apellidos_validados = true;
  }else{
      $apellidos_validados = false;
      $errores['apellidos'] = 'Apellidos incorrectos';
  }
//VALIDADO EMAIL
  if(!empty($email) && !is_numeric($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
      $email_validado =true;
  }else{
      $email_validado = false;
      $errores['email'] = 'Email incorrecto';
  }

  //VALIDANDO PASSWORD

  if(!empty($password)){
      $password_validado = true;
  }else{
      $password_validado=false;
      $errores['password'] = 'Password no valida';
  }


  $guardar_usuario = false;
 if(count($errores) == 0){
     $guardar_usuario= true;

     //CIFRAR LA CONTRASEÑA

     $password_segura = password_hash($password, PASSWORD_BCRYPT, ['cost'=>4]);

     //Insertamos usuarios cuando no exitan errores en el formulario

     $in = "INSERT INTO usuarios VALUES (null, '$nombre', '$apellidos', '$email', '$password_segura')";

     $sql = mysqli_query($db, $in);

     

     if($sql){
        $_SESSION['completado'] = 'Registro guardado';
     }else{

          $_SESSION['errores']['general'] = 'Error al Registrar usuario';
     }

 }else{
     $_SESSION['errores'] = $errores;
     
 }

}
header('Location: index.php');