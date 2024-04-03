<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Actualizar producto</h2>
</div>

<div class="container pb-6 pt-6">

   <?php  
   require_once("./inc/btn_back.php");
   require_once("./php/main.php");
   if(isset($_GET['product_id_up']) &&  $_GET['product_id_up'] != ""){
    $id=limpiar_cadena($_GET[ 'product_id_up' ]);
   $verificar_producto=conexion();
   $verificar_producto=$verificar_producto->query("SELECT * FROM producto WHERE producto_id= '$id' ");
   if ($verificar_producto->rowCount() == 1) {
    $datos=$verificar_producto->fetch();
    
   
   
   ?>



	<div class="form-rest mb-6 mt-6"></div>
	
	<h2 class="title has-text-centered"><?php echo $datos['producto_nombre'];?></h2>

	<form action="./php/producto_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="producto_id" value="<?php echo $datos['producto_id'];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Código de barra</label>
				  	<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70" value="<?php echo $datos['producto_codigo'];?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" value="<?php echo $datos['producto_nombre'];?>" required >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Precio</label>
				  	<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['producto_precio'];?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Stock</label>
				  	<input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" value="<?php echo $datos['producto_stock'];?>" required >
				</div>
		  	</div>
		  	<div class="column">
				<label>Categoría</label><br>
		    	<div class="select is-rounded">
				  	<select name="producto_categoria" >
                       
                        <?php 
                       
                        $categoria=conexion();
                        $categoria = $categoria->query("SELECT * FROM categoria");
                        if ($categoria->rowCount()!= 0) {
                            $categoria=$categoria->fetchAll();
                            foreach($categoria as $fila){
                                if ($datos['categoria_id']==$fila['categoria_id']) {
                                    echo "<option value='".$fila['categoria_id']."' selected=''> ".$fila['categoria_nombre']." (ACTUAL)</option>";
                                }else{
                                    echo "<option value='".$fila['categoria_id']."'> ".$fila['categoria_nombre']." </option>";
                                }
                                
                            }

                            
                        }else {
                            echo '
                            <div class="notification is-danger">
                                <strong>¡Ocurrio un error inesperado!</strong>
                               No Se logro Procesar la peticion
                            </div>
                            ';
                            exit();
                        }

                        $categoria=null
                        
                        ?>
                        
				  	</select>
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>

    <?php }else{ ?>

    <div class="notification is-danger is-light mb-6 mt-6">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No podemos obtener la información solicitada
    </div>
    <?php } }else{ ?>
    <div class="notification is-danger is-light mb-6 mt-6">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No podemos obtener la información solicitada id no definido
    </div>
    <?php }
    $verificar_producto=null;
      ?>
</div>