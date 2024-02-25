<?php
require_once("main.php");
$nombre= limpiar_cadena($_POST['categoria_nombre']);
$ubicacion= limpiar_cadena($_POST['categoria_ubicacion']);
#Verificar campos obligatorios#

if ($nombre=="") {
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

if($ubicacion!=""){
    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$ubicacion)) {
        echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
           La ubicacion no coincide con los caracteres permitidos
        </div>
        ';
        exit();
    }
}

#Verificar nombre#
$check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre='$nombre'");
        if ($check_nombre->rowCount()>0) {  
            echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El nombre de la categoria ya esta registrado en la base de datos 
    </div>
    ';
    exit();
        }
        $check_nombre=null;
        
#Guardar datos#
$guardar_categoria=conexion();
$guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre,categoria_ubicacion) VALUES(:nombre,:ubicacion)");
    $marcadores = [
            ":nombre"=>$nombre,
            ":ubicacion"=>$ubicacion
          ];
         $guardar_categoria->execute($marcadores);
         if ($guardar_categoria->rowCount()==1) {
 
          echo'<div class="notification is-success ">
          
           <strong>!La categoria se registro exitosamente¡</strong>
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
$guardar_categoria=null;