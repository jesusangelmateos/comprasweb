<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>CONSULTAR ALMACENES</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	$almacenes = obtenerAlmacenes($db);
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
	<div class="form-group">
		<label for="almacen">ALMACEN:</label>
		<select name="almacen">
		<?php foreach($almacenes as $almacenes) : ?>
					<option> <?php echo $almacenes ?> </option>
				<?php endforeach; ?></select><br><br>
		</select>
	</div>
	</BR>
<?php
	echo '<div><input type="submit" value="Consultar"></div>
	</form>';
} else { 
	
    $numAlmacen=$_REQUEST['almacen'];
    
    listar($db, $numAlmacen);
	
}
?>

<?php
// Funciones utilizadas en el programa

function obtenerAlmacenes($db){
    $almacenes = array();

    $sql = "SELECT NUM_ALMACEN FROM ALMACEN";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$almacenes[] = $row['NUM_ALMACEN'];
		}
	}
	return $almacenes;
}

function listar($db, $numAlmacen){

	$productos=array();//id de productos en almacen
	$sql="SELECT ID_PRODUCTO from ALMACENA where NUM_ALMACEN='$numAlmacen'";
	$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
            $productos[] = $row['ID_PRODUCTO'];
		}
	}
	
	//var_dump($productos);
	foreach($productos as $producto) {
		$sql = "SELECT NUM_ALMACEN, ID_PRODUCTO, CANTIDAD FROM ALMACENA WHERE NUM_ALMACEN='$numAlmacen' AND ID_PRODUCTO='$producto'";
		$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
		$row=mysqli_fetch_assoc($resultado);
		$numAlmacen=$row['NUM_ALMACEN'];
		$idProducto=$row['ID_PRODUCTO'];
		$cantidadAlmacen=$row['CANTIDAD'];
		echo "ALMACEN: ".$numAlmacen." || PRODUCTO: ".$idProducto." || Stock: ".$cantidadAlmacen."</br>";
	}
	

}
	

?>



</body>

</html>