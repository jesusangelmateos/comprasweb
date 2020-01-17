<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA CATEGORÍAS - JESUS MATEOS</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos Categoría</div>
<div class="card-body">
		<div class="form-group">
        ID CATEGORIA <input type="text" name="idcategoria" placeholder="idcategoria" class="form-control">
        </div>
		<div class="form-group">
        NOMBRE CATEGORIA <input type="text" name="nombre" placeholder="nombre" class="form-control">
        </div>

		</BR>
<?php
	echo '<div><input type="submit" value="Alta Categoría"></div>
	</form>';
} else { 
    set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
    $idCategoria=limpiar_campo($_REQUEST['idcategoria']);
    if($idCategoria==""){
		trigger_error('El campo no puede estar vacio');	
	}
    $nombreCategoria=limpiar_campo($_REQUEST['nombre']);
    if($nombreCategoria==""){
		trigger_error('El campo no puede estar vacio');	
	}
    insert($db, $idCategoria, $nombreCategoria);
	
}
?>

<?php
// Funciones utilizadas en el programa
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
function insert($db, $idCategoria, $nombreCategoria){
    $sql = "INSERT INTO categoria (ID_CATEGORIA, NOMBRE) VALUES ('$idCategoria', '$nombreCategoria')";
	if(mysqli_query($db, $sql)){

		echo "Categoria insertada correctamente<br>";
	}
}

	




?>



</body>

</html>
