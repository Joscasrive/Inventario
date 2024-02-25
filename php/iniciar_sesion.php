<?php
#Almacenado de datos#
$usuario= limpiar_cadena($_POST['login_usuario']);
$clave= limpiar_cadena($_POST['login_clave']);


#Verificar campos obligatorios#
if ($usuario== "" ||$clave=="" ) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        No has llenado todo los datos que son obligatorios
    </div>
    ';
    exit();
}
#Verificar integridad de los datos#
if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El usuario no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        La clave no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}

#Verificar Usuario#
$check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT * FROM usuario WHERE usuario_usuario='$usuario'");
        if ($check_usuario->rowCount()==1) {  
            $check_usuario=$check_usuario->fetch();
            if ($check_usuario['usuario_usuario']==$usuario && password_verify($clave,$check_usuario['usuario_clave'])) {
           $_SESSION['id']=$check_usuario['usuario_id'];
           $_SESSION['nombre']=$check_usuario['usuario_nombre'];
           $_SESSION['apellido']=$check_usuario['usuario_apellido'];
           $_SESSION['usuario']=$check_usuario['usuario_usuario'];
           if (headers_sent()) {
            echo "<script> window.location.href='index.php?vista=home'</script>";
            
           } else {
            header("location:index.php?vista=home");
           }
           
                
            } else {
                echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       Usuario o Clave incorrectos
    </div>
    ';
            }
            

    exit();
        }else {
            echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       Usuario o Clave incorrectos
    </div>
    ';
        }
        $check_usuario=null;
