<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>COMPRAR PRODUCTO</h1>
<?php
include "conexion.php";

$productos = obtenerProductos($db);

/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Producto</div>
<div class="card-body">
	<div class="form-group">
        NIF CLIENTE<input type="text" name="nif" placeholder="nif" class="form-control">
    </div>
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
<?php
	echo '<div><input type="submit" value="Comprar Producto"></div>
	</form>';
} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores

	$nombreProducto=$_REQUEST['nombreProducto'];
	$fechaCompra=date("Y-m-d H:m:s"); //fecha actual
	
	$nif=limpiar_campo($_REQUEST['nif']);
	if($nif==""){
		trigger_error('El nif no puede estar vacio');	
	}

	$unidades=limpiar_campo($_REQUEST['unidades']);
	if($unidades==""){
		trigger_error('Las unidades no pueden estar vacias');	
	}
	
    insert($db, $nombreProducto, $unidades, $nif, $fechaCompra);
	
}
?>

<?php

// Funciones utilizadas en el programa

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

function insert($db, $nombreProducto, $unidades, $nif, $fechaCompra){

    $sql="SELECT ID_PRODUCTO from PRODUCTO where NOMBRE= '$nombreProducto'";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$idProducto=$row['ID_PRODUCTO'];
	
	$sql="SELECT SUM(CANTIDAD) as cantidad from ALMACENA where ID_PRODUCTO= '$idProducto'";//Ponerle un alias para el SUM
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$cantidad=$row['cantidad'];//Cantidad del producto de todos los almacenes
	
	$sql="SELECT NIF from CLIENTE where NIF= '$nif'";//Ponerle un alias para el SUM
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$nifExiste=$row['NIF'];

	if($nifExiste){
		if($unidades<$cantidad){  

			$sql="INSERT INTO COMPRA (NIF, ID_PRODUCTO, FECHA_COMPRA, UNIDADES) VALUES ('$nifExiste','$idProducto','$fechaCompra','$unidades')";
			if(mysqli_query($db, $sql)){
				
				$cont=0;
				$numAlmancen=10;//numero del primer almacen
				
				while($unidades>$cont){

					$sql="SELECT CANTIDAD FROM ALMACENA WHERE  NUM_ALMACEN='$numAlmancen' AND ID_PRODUCTO='$idProducto'";
					$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
					$row=mysqli_fetch_assoc($resultado);
					$cantidadAlmacen=$row['CANTIDAD'];

					if($cantidadAlmacen>0){
						$sql="UPDATE ALMACENA SET CANTIDAD=CANTIDAD-1 WHERE NUM_ALMACEN='$numAlmancen' AND ID_PRODUCTO='$idProducto'";
						$resultado=mysqli_query($db, $sql);
						$cont++;
					}
					else{
						$numAlmancen=$numAlmancen+10;
					}
					
				}

				echo "Compra realizada<br>";
			}
			else {
				echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
			}

			
			
		}
		else{
			trigger_error("NO hay suficiente stock");
		}
	}
	else{
		trigger_error("NIF incorrecto o no existente");
	}

}
	

?>



</body>

</html>