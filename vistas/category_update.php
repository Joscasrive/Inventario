
<?php 
require_once("./php/main.php");
$id=(isset($_GET['category_id_up']))? $id=$_GET['category_id_up']: 0 ;
$id=limpiar_cadena($id);
?>

<div class="container is-fluid mb-6">
	<h1 class="title">Categorías</h1>
	<h2 class="subtitle">Actualizar categoría</h2>
</div>

<div class="container pb-6 pt-6">


<?php include("./inc/btn_back.php");
	$check_categoria=conexion();
	$check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id ='$id'");
    if ($check_categoria->rowCount()>0) {
    $datos=$check_categoria->fetch();
	
	?>


	<div class="form-rest mb-6 mt-6"></div>


	<form action="./php/categoria_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="categoria_id" value="<?php echo $datos['categoria_id'];?>" required >

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre</label>
				  	<input class="input" type="text" name="categoria_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" maxlength="50" value="<?php echo $datos['categoria_nombre'];?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Ubicación</label>
				  	<input class="input" type="text" name="categoria_ubicacion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" value="<?php echo $datos['categoria_ubicacion'];?>" maxlength="150" >
				</div>
		  	</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
<?php }else {?>

    <div class="notification is-danger is-light mb-6 mt-6">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No podemos obtener la información solicitada
    </div>
    <?php }?>
    
</div>