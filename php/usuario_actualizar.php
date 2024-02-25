<?php
require_once("../inc/session_star.php");
require_once("main.php");
$id=limpiar_cadena($_POST['usuario_id']);
//verficar usuario
$check_usuario=conexion();
$check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_id ='$id'");
if ($check_usuario->rowCount()<=0) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El usuario selecionado no existe
    </div>
    ';
    exit();
} else {
$datos=$check_usuario->fetch();
}
$check_usuario=null;
$admin_usuario=limpiar_cadena($_POST['administrador_usuario']);
$admin_clave=limpiar_cadena($_POST['administrador_clave']);
#Verificar campos obligatorios#

if ($admin_usuario=="" || $admin_clave=="") {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        No has llenado todo los datos que son obligatorios
    </div>
    ';
    exit();
}
#Verificar campos del formulario#
if (verificar_datos("[a-zA-Z0-9]{4,20}",$admin_usuario)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El Usuario no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$admin_clave)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        La clave no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
//Verificando admin
$check_admin=conexion();
$check_admin=$check_admin->query("SELECT usuario_usuario,usuario_clave FROM usuario WHERE usuario_usuario='$admin_usuario' AND usuario_id='".$_SESSION['id']."'");
if($check_admin->rowCount()==1){
    $check_admin=$check_admin->fetch();
    if ($check_admin['usuario_usuario']!=$admin_usuario || !password_verify($admin_clave,$check_admin['usuario_clave'])) {
        echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
            Usuario o clave de admin incorrectos
        </div>
        ';
        exit();
    }

}else {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        Usuario o clave de admin incorrectos
    </div>
    ';
    exit();
}
$check_admin=null;
#Almacenado de datos#
$nombre= limpiar_cadena($_POST['usuario_nombre']);
$apellido= limpiar_cadena($_POST['usuario_apellido']);
$usuario= limpiar_cadena($_POST['usuario_usuario']);
$email= limpiar_cadena($_POST['usuario_email']);
$clave_1= limpiar_cadena($_POST['usuario_clave_1']);
$clave_2= limpiar_cadena($_POST['usuario_clave_2']);

#Verificar campos obligatorios#

if ($nombre== "" ||$apellido=="" ||$usuario=="" ) {
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
#Verificar email#
if($email!="" && $email!=$datos['usuario_email']){
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
if ($usuario != $datos['usuario_usuario']) {
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
        
}
#Verificar contraseña#
if ($clave_1 !="" || $clave_2 !="") {
    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1)||verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)) {
        echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
            La clave no coincide con los caracteres permitidos
        </div>
        ';
        exit();
    }else{
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
    }
    
    
} else {
    $clave=$datos['usuario_clave'];
    
}
#actualizar datos#
$actualizar_usuario=conexion();
$actualizar_usuario=$actualizar_usuario->prepare("UPDATE usuario SET usuario_nombre=:nombre,usuario_apellido=:apellido,usuario_usuario=:usuario,usuario_clave=:clave,usuario_email=:email WHERE usuario_id=:id");

$marcadores = [
    ":nombre"=>$nombre,
    ":apellido"=>$apellido,
    ":usuario"=>$usuario,
    ":clave"=>$clave,
    ":email"=>$email,
    ":id"=>$id
  ];
if($actualizar_usuario->execute($marcadores)){
    echo'<div class="notification is-info is-light">
         
    <strong>!El usuario se actualizo exitosamente¡</strong>
 </div>';
}else{
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       Lo sentimos No Se logro actualizar ningun dato
    </div>
    ';
}
$actualizar_usuario=null;