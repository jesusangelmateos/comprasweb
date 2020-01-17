<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA PRODUCTOS - JESUS MATEOS</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	$categorias = obtenerCategorias($db);
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Producto</div>
<div class="card-body">
		<div class="form-group">
        ID PRODUCTO <input type="text" name="idproducto" placeholder="idproducto" class="form-control">
        </div>
		<div class="form-group">
        NOMBRE PRODUCTO <input type="text" name="nombre" placeholder="nombre" class="form-control">
        </div>
		<div class="form-group">
        PRECIO PRODUCTO <input type="text" name="precio" placeholder="precio" class="form-control">
        </div>
	<div class="form-group">
	<label for="categoria">Categorías:</label>
	<select name="categoria">
    <?php foreach($categorias as $categorias) : ?>
				<option> <?php echo $categorias ?> </option>
			<?php endforeach; ?></select><br><br>
	</select>
	</div>
	</BR>
<?php
	echo '<div><input type="submit" value="Alta Producto"></div>
	</form>';
} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
    $idProducto=limpiar_campo($_REQUEST['idproducto']);
    if($idProducto==""){
		trigger_error('El campo no puede estar vacio');	
	}
    $nombreProducto=limpiar_campo($_REQUEST['nombre']);
    if($nombreProducto==""){
		trigger_error('El campo no puede estar vacio');	
    }
    $precioProducto=limpiar_campo($_REQUEST['precio']);
    if($precioProducto==""){
		trigger_error('El campo no puede estar vacio');	
    }
    
    insert($db, $idProducto, $nombreProducto, $precioProducto);
	
}
?>

<?php
// Funciones utilizadas en el programa
function obtenerCategorias($db){
    $categorias = array();

    $sql = "SELECT NOMBRE FROM categoria";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$categorias[] = $row['NOMBRE'];
		}
	}
	return $categorias;
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
function insert($db, $idProducto, $nombreProducto, $precioProducto){

    $select="SELECT ID_CATEGORIA from categoria where NOMBRE= '$nombreProducto'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$codigo=$row['ID_CATEGORIA'];

    $sql = "INSERT INTO producto (ID_PRODUCTO, NOMBRE, PRECIO, ID_CATEGORIA) VALUES ('$idProducto', '$nombreProducto', '$precioProducto', '$codigo')";
	if(mysqli_query($db, $sql)){

		echo "Categoria insertada correctamente<br>";
	}
}
	

?>



</body>

</html>
