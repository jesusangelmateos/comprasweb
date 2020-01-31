<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>COMPRAR PRODUCTO USUARIO</h1>
<?php
session_start();
include "conexion.php";

$productos = obtenerProductos($db);

	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Producto</div>
<div class="card-body">
	<div class="form-group">
		<label for="nombreProducto">PRODUCTO:</label>
		<select name="nombreProducto">
		<?php foreach($productos as $productos) : ?>
					<option> <?php echo $productos ?> </option>
				<?php endforeach; ?></select><br>
		</select>
	</div>
		<div class="form-group">
        UNIDADES DEL PRODUCTO <input type="number" name="unidades" placeholder="0" class="form-control">
        </div>
	</BR>

	<input type="submit" value="Añadir al Carrito">
	<input type="button" value="ATRAS" onclick="window.location.href='usuario.php'">
	</br><h3>CARRITO DE LA COMPRA</h3>
	</form>

<?php

if($_SERVER["REQUEST_METHOD"]=="POST") { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores

	$nombreProducto=$_REQUEST['nombreProducto'];

	$unidades=limpiar_campo($_REQUEST['unidades']);
	if($unidades==""){
		trigger_error('Las unidades no pueden estar vacias');	
	}

	$idProducto = selectCodigo($db, $nombreProducto);
	
	carrito($db, $unidades, $idProducto);
	visualizarCarrito();
	
}
?>


<?php

// Funciones utilizadas en el programa

function visualizarCarrito(){

	foreach ($_SESSION['carrito'] as $idProducto => $unidades){

		echo 'idProducto: '.$idProducto.' unidades: '.$unidades.'</br>';
   
	}
	echo '<form action="compraRealizada.php" method=="post"><input type="submit" value="COMPRAR"></form>';
}

function obtenerProductos($db){
    $productos = array();

    $sql = "SELECT NOMBRE FROM PRODUCTO";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$productos[] = $row['NOMBRE'];
		}
	}
	return $productos;
}

function limpiar_campo($campoformulario) {
    $campoformulario = trim($campoformulario);
    $campoformulario = stripslashes($campoformulario);
    $campoformulario = htmlspecialchars($campoformulario);  
    return $campoformulario;
}

function errores ($error_level, $error_message, $error_file, $error_line, $error_context){
	echo "<b>Codigo error: </b> $error_level - <b> Mensaje: $error_message </b><br>";
	echo "<b>Fichero: $error_file</b><br>";
	echo "<b>Linea: $error_line</b><br>";
	//var_dump($error_context);
	echo "Finalizando script <br>";
	die(); 
//set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
//trigger_error('El DNI '.$DNI.' ya existe previamente');	
}

function carrito($db, $unidades, $idProducto){

	$sql="SELECT SUM(CANTIDAD) as cantidad from ALMACENA where ID_PRODUCTO= '$idProducto'";//Ponerle un alias para el SUM
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$cantidad=$row['cantidad'];//Cantidad del producto de todos los almacenes

	if(!empty($_SESSION["carrito"][$idProducto])){
		$unidades=$_SESSION["carrito"][$idProducto]+$unidades;
	}
	if($cantidad<$unidades){
		echo 'Las Unidades sobrepasan el stock </br>';
	}
	else{
		$_SESSION["carrito"][$idProducto]=$unidades;
	}
	if(!empty($_SESSION["carrito"][$idProducto]) && $_SESSION["carrito"][$idProducto]<=0){
		unset($_SESSION["carrito"][$idProducto]);
	}
	
}

function selectCodigo($db, $nombreProducto){

	$select="SELECT ID_PRODUCTO from PRODUCTO where NOMBRE='$nombreProducto'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$idProducto=$row['ID_PRODUCTO'];

	return $idProducto;
}
	

?>

</body>

</html>