<?php
require_once("main.php");
require_once("../inc/session_star.php");
$id=limpiar_cadena($_POST['producto_id']);
$codigo=limpiar_cadena($_POST['producto_codigo']);
$nombre=limpiar_cadena($_POST['producto_nombre']);
$precio=limpiar_cadena($_POST['producto_precio']);
$stock=limpiar_cadena($_POST['producto_stock']);
$categoria=limpiar_cadena($_POST['producto_categoria']);
#VAlidaciones roducto #
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$id'");
if ($check_producto->rowCount()==1) {
$datos=$check_producto->fetch();
   #VERIFICA INTEGRIDAD DE LOS DATOS#
if(verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)){
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El codigo tiene caracteres no permitidos por el sistema
    </div>
    ';
    exit();
}
if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El codigo tiene caracteres no permitidos por el sistema
    </div>
    ';
    exit();
}
if(verificar_datos("[0-9.]{1,25}",$precio)){
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El codigo tiene caracteres no permitidos por el sistema
    </div>
    ';
    exit();
}
if(verificar_datos("[0-9]{1,25}",$stock)){
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El codigo tiene caracteres no permitidos por el sistema
    </div>
    ';
    exit();
}
#Verificar el codigo de barra$
if ($codigo != $datos['producto_codigo']) {
    $consulta_codigo=conexion();
$consulta_codigo=$consulta_codigo->query("SELECT * FROM producto WHERE producto_codigo= '$codigo'");
if ($consulta_codigo->rowCount() == 1) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El Codigo ya existe en el sistema 
    </div>
    ';
    exit();
}
$consulta_codigo=null;
}
#Verificar el nombre del producto$
if ($nombre != $datos['producto_nombre']) {
    $consulta_nombre=conexion();
$consulta_nombre=$consulta_nombre->query("SELECT * FROM producto WHERE producto_nombre= '$nombre'");
if ($consulta_nombre->rowCount() != 0) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El Nombre ya existe en el sistema 
    </div>
    ';
    exit();
}
$consulta_nombre=null;
}
#actaulizar los datos#
if ($codigo != $datos['producto_codigo'] || $nombre != $datos['producto_nombre'] || $precio != $datos['producto_precio'] || $stock != $datos['producto_stock'] || $categoria != $datos['categoria_id']) {
    $actualizar=conexion();
    $actualizar=$actualizar->prepare("UPDATE producto SET producto_codigo=:codigo,producto_nombre=:nombre,producto_precio=:precio,producto_stock=:stock,categoria_id=:categoria,usuario_id=:usuario WHERE producto_id=:ID");
    $array=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id'],
        ":ID"=>$id
    ];
    if ($actualizar->execute($array)) {
    
        echo '
        <div class="notification is-info">
            <strong>¡EXITO!</strong>
           El producto  se logro actualizar correctamente
        </div>
        ';
        
    } else {
       echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
           El producto no se logro actualizar intente mas tarde
        </div>
        ';
        exit(); 
    }
}else{
    echo '
        <div class="notification is-info is-light">
            <strong>¡Ocurrio un error inesperado!</strong>
           Cambia algun valor en el formulario para poder actualizar
        </div>
        ';
        exit(); 
}


} else {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El producto no existe en el sistema 
    </div>
    ';
    exit();
}
$check_producto=null;

