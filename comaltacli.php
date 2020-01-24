<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ALTA CLIENTE</h1>
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
<div class="card-header">Datos del Cliente</div>
<div class="card-body">
		<div class="form-group">
        NIF <input type="text" name="nif" placeholder="nif" class="form-control">
        </div>
		<div class="form-group">
        NOMBRE <input type="text" name="nombre" placeholder="nombre" class="form-control">
        </div>
		<div class="form-group">
        APELLIDO <input type="text" name="apellido" placeholder="apellido" class="form-control">
		</div>
		<div class="form-group">
        CODIGO POSTAL <input type="text" name="cp" placeholder="codigo postal" class="form-control">
		</div>
		<div class="form-group">
        DIRECCION <input type="text" name="direccion" placeholder="direccion" class="form-control">
		</div>
		<div class="form-group">
        CIUDAD <input type="text" name="ciudad" placeholder="ciudad" class="form-control">
        </div>
	
	</BR>
<?php
	echo '<div><input type="submit" value="Alta Cliente"></div>
	</form>';
} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
	
    $nif=limpiar_campo($_REQUEST['nif']);
    if($nif==""){
		trigger_error('El NIF no puede estar vacio');	
	}
	if(!preg_match("/^[\d]{8}[a-z]$/i", $nif)){
		trigger_error('Campo NIF incorrecto');	
	}
	$nombre=limpiar_campo($_REQUEST['nombre']);
	if($nombre==""){
		trigger_error('El nombre no puede estar vacio');	
	}
	$apellido=limpiar_campo($_REQUEST['apellido']);
	if($apellido==""){
		trigger_error('El apellido no puede estar vacio');	
	}
	$cp=limpiar_campo($_REQUEST['cp']);
	if($cp==""){
		trigger_error('El codigo postal no puede estar vacio');	
	}
	$direccion=limpiar_campo($_REQUEST['direccion']);
	if($direccion==""){
		trigger_error('La direccion no puede estar vacia');	
	}
	$ciudad=limpiar_campo($_REQUEST['ciudad']);
	if($ciudad==""){
		trigger_error('La ciudad no puede estar vacia');	
	}
	
	insert($db, $nif, $nombre, $apellido, $cp, $direccion, $ciudad);
	
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
function insert($db, $nif, $nombre, $apellido, $cp, $direccion, $ciudad){

    $select="SELECT nif from cliente where nif='$nif'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$nifExiste=$row['nif'];

	if(!$nifExiste){//si no tienen nada es que no existe ese nif
		$sql = "INSERT INTO cliente (NIF, NOMBRE, APELLIDO, CP, DIRECCION, CIUDAD) VALUES ('$nif', '$nombre', '$apellido', '$cp', '$direccion', '$ciudad')";
		if(mysqli_query($db, $sql)){
	
			echo "Cliente: ".$nif." insertado correctamente<br>";
		} 
        else {
            echo "Error: ".$sql."<br>".mysqli_error($conn)."<br>";
        }
	}
	else{
		
		trigger_error('Ya existe un cliente con ese NIF');
	}
   
}
	

?>



</body>

</html>