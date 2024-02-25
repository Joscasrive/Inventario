<?php
require_once("main.php");
#Almacenado de datos#
$nombre= limpiar_cadena($_POST['usuario_nombre']);
$apellido= limpiar_cadena($_POST['usuario_apellido']);
$usuario= limpiar_cadena($_POST['usuario_usuario']);
$email= limpiar_cadena($_POST['usuario_email']);
$clave_1= limpiar_cadena($_POST['usuario_clave_1']);
$clave_2= limpiar_cadena($_POST['usuario_clave_2']);

#Verificar campos obligatorios#

if ($nombre== "" ||$apellido=="" ||$usuario=="" || $clave_1==""||$clave_2=="") {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        No has llenado todo los datos que son obligatorios
    </div>
    ';
    exit();
}
#Verificar campos del formulario#
if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El nombre no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El apellido no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El usuario no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        La clave no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       La clave no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}

#Verificar email#
if($email!=""){
    if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $check_email=conexion();
        $check_email=$check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
        if ($check_email->rowCount()>0) {  
            echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El email ya esta registrado en la base de datos
    </div>
    ';
    exit();
        }
        $check_email=null;
    } else {
        echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
          EL email no es valido
        </div>
        ';
        exit();
 
    }
    
}
#Verificar Usuario#
$check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
        if ($check_usuario->rowCount()>0) {  
            echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El Usuario ya esta registrado en la base de datos 
    </div>
    ';
    exit();
        }
        $check_usuario=null;
        
        #Verificar contraseña#
        if ($clave_1 != $clave_2) {
             echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       Las contraseñas no coinciden 
    </div>
    ';
    exit();
        } else{
           $clave= password_hash($clave_1,PASSWORD_BCRYPT,["cost"=>10]);
        }
        #Guardar datos#
        $guardar_usuario=conexion();
        $guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre,usuario_apellido,usuario_usuario,usuario_clave,usuario_email) VALUES(:nombre,:apellido,:usuario,:clave,:email)");
        $marcadores = [
           ":nombre"=>$nombre,
           ":apellido"=>$apellido,
           ":usuario"=>$usuario,
           ":clave"=>$clave,
           ":email"=>$email
         ];
        $guardar_usuario->execute($marcadores);
        if ($guardar_usuario->rowCount()==1) {

         echo'<div class="notification is-success">
         
          <strong>!El usuario se registro exitosamente¡</strong>
       </div>';
            
        } else {
            echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       Lo sentimos No Se logro regsitrar ningun dato
    </div>
    ';
    exit();
            
        }
        $guardar_usuario=null;
        

        
   
