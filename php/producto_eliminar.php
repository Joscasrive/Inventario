<?php
$id=limpiar_cadena($_GET['product_id_del']);

#Verificar que el producto existe#
$check_producto=conexion();
$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id =$id");
if($check_producto->rowCount()==1){
    $datos=$check_producto->fetch();
   #eliminar producto#
   $eliminarProducto=conexion();
   $eliminarProducto=$eliminarProducto->prepare("DELETE FROM producto WHERE producto_id =:ID");
   $eliminarProducto->execute(array('ID'=>$id));
   if ($eliminarProducto->rowCount()==1) {
    
    
    if (is_file("./img/producto/".$datos['producto_foto'])) {
       chmod("./img/producto/".$datos['producto_foto'],0777);
       if(!unlink("./img/producto/".$datos['producto_foto'])){
        echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El usuario fue elminado con exito exeptuando la foto Solicite ayudua al personal autorizado
    </div>
    ';
    exit();
    }
    
    
   }
   echo'<div class="notification is-success">
         
          <strong>!El usuario se elimino exitosamente</strong>
       </div>';
}else{
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       No podemos procesar su peticion
    </div>
    ';
}
}else{
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       El producto selecionado no existe
    </div>
    ';
}
$check_producto=null;