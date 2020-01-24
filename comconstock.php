<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>PRODUCTOS POR ALMACEN</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	$productos = obtenerProductos($db);
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
	<div class="form-group">
		<label for="producto">PRODUCTO:</label>
		<select name="producto">
		<?php foreach($productos as $productos) : ?>
					<option> <?php echo $productos ?> </option>
				<?php endforeach; ?></select><br><br>
		</select>
	</div>
	</BR>
<?php
	echo '<div><input type="submit" value="Listar"></div>
	</form>';
} else { 
	
    $nombreProducto=$_REQUEST['producto'];
    
    listar($db, $nombreProducto);
	
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

function listar($db, $nombreProducto){

    $sql="SELECT ID_PRODUCTO from PRODUCTO where NOMBRE= '$nombreProducto'";
    $resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$idProducto=$row['ID_PRODUCTO'];

	$almacenes=array();//numeros de almacenes con el producto
	$sql="SELECT NUM_ALMACEN from ALMACENA where ID_PRODUCTO= '$idProducto'";
	$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
            $almacenes[] = $row['NUM_ALMACEN'];
		}
    }
	
	//var_dump($almacenes);
	foreach($almacenes as $almacen){
		$sql = "SELECT NUM_ALMACEN, CANTIDAD FROM ALMACENA WHERE NUM_ALMACEN='$almacen'";
		$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
		$row=mysqli_fetch_assoc($resultado);
		$numAlmacen=$row['NUM_ALMACEN'];
		$cantidadAlmacen=$row['CANTIDAD'];
		echo "Producto: ".$nombreProducto." || Almacen: ".$numAlmacen." || Stock: ".$cantidadAlmacen."</br>";
	}
	//var_dump($numAlmacen); var_dump($cantidadAlmacen);
}
	

?>



</body>

</html>