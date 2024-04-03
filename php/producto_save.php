<?php
require_once("main.php");
require_once("../inc/session_star.php");
#Almacenado de datos#
$codigo= limpiar_cadena($_POST['producto_codigo']);
$nombre= limpiar_cadena($_POST['producto_nombre']);
$precio= limpiar_cadena($_POST['producto_precio']);
$stock= limpiar_cadena($_POST['producto_stock']);
$categoria= limpiar_cadena($_POST['producto_categoria']);

#Verificar campos obligatorios#

if ($codigo== "" ||$nombre=="" ||$precio=="" || $stock=="" || $categoria=="") {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        No has llenado todo los datos que son obligatorios
    </div>
    ';
    exit();
}

#Verificar campos del formulario#
if (verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El Codigo no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El nombre no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[0-9.]{1,25}",$precio)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El Precio no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
if (verificar_datos("[0-9]{1,25}",$stock)) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
        El Stock no coincide con los caracteres permitidos
    </div>
    ';
    exit();
}
#Verificando codigo#
$check_codigo=conexion();
    $check_codigo=$check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");
        if ($check_codigo->rowCount()>0) {  
            echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El Codigo del Producto ya esta registrado en la base de datos 
    </div>
    ';
    exit();
        }
        $check_codigo=null;
#Verificando Nombre#
$check_nombre=conexion();
$check_nombre=$check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre='$nombre'");
    if ($check_nombre->rowCount()>0) {  
        echo '
<div class="notification is-danger">
    <strong>¡Ocurrio un error inesperado!</strong>
   El nombre del Producto ya esta registrado en la base de datos 
</div>
';
exit();
    }
    $check_nombre=null;

    #Verificando Categoria#
    $check_categoria=conexion();
$check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
    if ($check_categoria->rowCount()<=0) {  
        echo '
<div class="notification is-danger">
    <strong>¡Ocurrio un error inesperado!</strong>
   La categoria del Producto  no  esta registrado en la base de datos 
</div>
';
exit();
}
$check_categoria=null;
#Verificando imagen#
$img_dir="../img/producto/";
if ($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size'] != 0) {

    if(!file_exists($img_dir)){
        if(!mkdir($img_dir,0777)){
            echo '
            <div class="notification is-danger">
                <strong>¡Ocurrio un error inesperado!</strong>
               No podemos procesar su peticion
            </div>
            ';
        }
     }

    #Verificar el peso de imagenes#
if ($_FILES['producto_foto']['size'] /1024 > 5120 ) {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       La imagen supera el peso permitido 
    </div>
    ';
    exit();
   }
   #comprobar formato de la imagen#
   if (mime_content_type($_FILES['producto_foto']['tmp_name'])!= "image/jpeg" &&  
   mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png") {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       La imagen no corresponde al formato solicitado 
    </div>
    ';
    exit();
   }
   #Asignamos nombre y tipo a la imagen#
   $foto=renombrar_fotos($nombre);
   
    #Extencion de la imagen#
    switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
       case  "image/jpeg":
             $foto=$foto.".jpg";
          break;
          case   "image/png":
             $foto=$foto.".png";
          break;
       }
       chmod($img_dir,0777);
        #Mover imagenen al dir#
        if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)) {
            echo '
            <div class="notification is-danger">
                <strong>¡Ocurrio un error inesperado!</strong>
               La imagen no se logro guardar en el sistema 
            </div>
            ';
            exit();
        }
} else {

    $foto="";
   
      }
      #Guardar Datos#
      $guardarProductos=conexion();
      $guardarProductos=$guardarProductos->prepare("INSERT INTO producto(producto_codigo,producto_nombre,producto_precio,producto_stock,producto_foto,categoria_id,usuario_id) 
      VALUES(:codigo,:nombre,:precio,:stock,:foto,:categoria,:usuario)");
    $datos=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock,
        ":foto"=>$foto,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id']
    ];
    $guardarProductos->execute($datos);
              if ($guardarProductos->rowCount()==1) {
                echo '
                <div class="notification is-success">
                    <strong>¡Exito!</strong>
                  Los datos fueron guardados correctamente 
                </div>
                ';
                
                
              } else {
                if (is_file($img_dir.$foto)) {
                   chmod($img_dir.$foto,0777);                    
                   unlink($img_dir.$foto);
                     }
                echo '
            <div class="notification is-danger">
                <strong>¡Ocurrio un error inesperado!</strong>
               No se logro guardar ningun dato en el sistema intente mas tarde 
            </div>
            ';
            exit();
              }
              $guardarProductos=null;
              

