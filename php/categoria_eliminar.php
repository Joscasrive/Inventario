<?php
$cat_id=limpiar_cadena($_GET['category_id_del']);
$check_categoria=conexion() ;
#Verificamos si la categoria existe#
$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id=$cat_id");
if($check_categoria->rowCount()==1){
    #Verificando productos#
    $check_producto=conexion();
    $check_producto=$check_producto->query("SELECT categoria_id FROM producto WHERE categoria_id ='$cat_id' LIMIT 1");
    if ($check_producto->rowCount()<=0) {
        $eliminar_categoria=conexion();
        $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria  WHERE categoria_id=:ID");
        $eliminar_categoria->execute(array(':ID'=>$cat_id));
        if ($eliminar_categoria->rowCount()>0){
            echo '
        <div class="notification is-success">
            <strong>¡La categoria selecionada fue eliminada!</strong>
            
        </div>
        '; 
        }else{
            echo '
            <div class="notification is-danger">
                <strong>¡Ocurrio un error inesperado!</strong>
               No se pudo procesar la peticion
            </div>
            '; 
        }
    } else {
        echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
          La categoria selecionada Tiene productos agragados
        </div>
        ';
    }

}else{
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
      La categoria selecionada no existe
    </div>
    ';
};
$check_categoria=null;
$check_producto=null;
$eliminar_categoria=null;