<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>APROVISIONAR PRODUCTOS</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	$productos = obtenerProductos($db);
	$almacenes= obtenerAlmacenes($db);
	
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
	<div class="form-group">
		<label for="almacen">NUMERO DE ALMACEN:</label>
		<select name="almacen">
		<?php foreach($almacenes as $almacenes) : ?>
					<option> <?php echo $almacenes ?> </option>
				<?php endforeach; ?></select><br><br>
		</select>
	</div>
	<div class="form-group">
        CANTIDAD <input type="number" name="cantidad" placeholder="cantidad" class="form-control">
    </div>
	</BR>
<?php
	echo '<div><input type="submit" value="Aprovisionar"></div>
	</form>';
} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
	
    $nombreProducto=limpiar_campo($_REQUEST['producto']);
    if($nombreProducto==""){
		trigger_error('El campo no puede estar vacio');	
	}
    $numAlmacen=limpiar_campo($_REQUEST['almacen']);
    if($numAlmacen==""){
		trigger_error('El campo no puede estar vacio');	
    }
    $cantidad=limpiar_campo($_REQUEST['cantidad']);
    if($cantidad==""){
		trigger_error('El campo no puede estar vacio');	
    }
    
    insert($db, $nombreProducto, $numAlmacen, $cantidad);
	
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
function obtenerAlmacenes($db){
    $almacenes = array();

    $sql = "SELECT NUM_ALMACEN FROM almacen";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$almacenes[] = $row['NUM_ALMACEN'];
		}
	}
	return $almacenes;
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
function insert($db, $nombreProducto, $numAlmacen, $cantidad){

    $select="SELECT ID_PRODUCTO from producto where NOMBRE= '$nombreProducto'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$idProducto=$row['ID_PRODUCTO'];

    $sql = "INSERT INTO almacena (NUM_ALMACEN, ID_PRODUCTO, CANTIDAD) VALUES ('$numAlmacen', '$idProducto', '$cantidad')";
	if(mysqli_query($db, $sql)){

		echo "Almacen aprovisionado correctamente<br>";
	}
	else {
		echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
	}
}
	

?>



</body>

</html>