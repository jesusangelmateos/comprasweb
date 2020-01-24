<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA ALMACEN</h1>
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
<div class="card-header">Datos del Almacen</div>
<div class="card-body">
		<div class="form-group">
        LOCALIDAD DEL ALMACEN <input type="text" name="localidad" placeholder="localidad" class="form-control">
        </div>
	
	</BR>
<?php
	echo '<div><input type="submit" value="Alta Almacen"></div>
	</form>';
} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores

    $localidad=limpiar_campo($_REQUEST['localidad']);
    if($localidad==""){
		trigger_error('La localidad no puede estar vacia');	
	}
	
	insert($db, $localidad);
	
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
function insert($db, $localidad){

    $select="SELECT max(NUM_ALMACEN) as num_almacen from almacen";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$num_almacen=$row['num_almacen'];

	if($num_almacen==NULL){
		$num_almacen=10;
	}
	else{
		$num_almacen=$num_almacen+10;
	}
	$sql = "INSERT INTO almacen (NUM_ALMACEN, LOCALIDAD) VALUES ('$num_almacen', '$localidad')";
		if(mysqli_query($db, $sql)){
	
			echo "Almacen: ".$num_almacen." con localidad en: ".$localidad." insertado correctamente<br>";
		} 
        else {
            echo "Error: ".$sql."<br>".mysqli_error($db)."<br>";
        }
   
}
	

?>



</body>

</html>