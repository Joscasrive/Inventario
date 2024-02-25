<?php
$modulo_buscador=limpiar_cadena($_POST['modulo_buscador']);
$modulos=["usuario","categoria","producto"];

if (in_array($modulo_buscador, $modulos)) {
    
	$modulos_url=[
        "usuario"=>"user_search",
        "categorias"=>"category_search",
        "producto"=>"producto_search"
    ];
    
	$modulos_url=$modulos_url[$modulo_buscador];
    
	$modulo_buscador="busqueda_".$modulo_buscador;
    
    #Iniciar Busqueda#
    if (isset($_POST['txt_buscador'])) {
        
        $txt=limpiar_cadena($_POST['txt_buscador']);
        
		if ($txt=="") {
            echo '
        <div class="notification is-danger">
            <strong>¡Ocurrio un error inesperado!</strong>
        Introduce un termino de busqueda
        </div>
        ';
        }else {
            
            if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}",$txt)) {
            echo ' 
            <div class="notification is-danger">
                <strong>¡Ocurrio un error inesperado!</strong>
             Termino de busqueda no coincide con el formato solicitado
            </div>
               ';
            } else {
                $_SESSION[$modulo_buscador]=$txt;
                header("Location: index.php?vista=$modulos_url",true,303 );
                exit();
                
            }
            
        }
        
    }
    
    #Eliminar Busqueda#
    
    if (isset($_POST['eliminar_buscador'])) {
		unset($_SESSION[$modulo_buscador]);
		header("Location: index.php?vista=$modulos_url",true,303); 
		 exit();
        
    }
} else {
    echo '
    <div class="notification is-danger">
        <strong>¡Ocurrio un error inesperado!</strong>
       No podemos procesar la peticion
    </div>
    ';
    
}

