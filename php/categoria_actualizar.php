<?php
require_once("../inc/session_star.php");
require_once("main.php");
$id=limpiar_cadena($_POST['categoria_id']);
$check_categoria=conexion();

$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id ='$id'");
    if ($check_categoria->rowCount()>0) {
    $datos=$check_categoria->fetch();
     }else {
        echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
           La categoria no existe
        </div>
        ';
        exit();  
     }
$nombre=limpiar_cadena($_POST['categoria_nombre']);
$ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);

if ($nombre== "" ||$ubicacion=="") {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        No has llenado todo los datos que son obligatorios
    </div>
    ';
    exit();
}
#Verificar campos del formulario#
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El nombre no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El nombre no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
#Verificar nombre de la categoria#
if ($nombre!=$datos['categoria_nombre'] || $ubicacion!=$datos['categoria_ubicacion'] ) {
    $actulizarCategoria=conexion();
    $actulizarCategoria=$actulizarCategoria->prepare("UPDATE categoria SET categoria_nombre=:nombre,categoria_ubicacion=:ubicacion WHERE categoria_id=:ID");
    $array=[":nombre"=>$nombre,
         ":ubicacion"=>$ubicacion,
        ":ID"=>$id];
    $actulizarCategoria->execute($array);
    if ($actulizarCategoria->rowCount()==1) {
        echo '
        <div class="notification is-light">
            <strong>¡Categoria Actualizada!</strong>
           La categoria '.$datos['categoria_nombre'].' fue actualizada Exitosamente
        </div>
        ';
       
        
    } else {
        echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       No podemos procesar su peticion
    </div>
    ';
    exit();
    }
                       
    
}else{
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El nombre y ubicacion son los mismo que tienen en el sistema modificalos para poder ejecutar el cambio
    </div>
    ';
    exit();
}
