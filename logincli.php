<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>ENTRAR/REGISTRARSE</h1>
<?php
session_start();
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) {

	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
		<div class="form-group">
        NOMBRE DE USUARIO <input type="text" name="nombre" placeholder="usuario" class="form-control">
        </div>
		<div class="form-group">
        CLAVE <input type="text" name="clave" placeholder="clave" class="form-control">
		</div>
		<input type="submit" value="ENTRAR">
		<input type="button" value="REGISTRARSE" onclick="window.location.href='registrocli.php'">	
	</BR>
<?php

} else { 

	set_error_handler("errores"); // Establecemos la funcion que va a tratar los errores
	
	$nombre=limpiar_campo($_REQUEST['nombre']);
	if($nombre==""){
		trigger_error('El nombre no puede estar vacio');	
	}
	$clave=limpiar_campo($_REQUEST['clave']);
	if($clave==""){
		trigger_error('La clave no puede estar vacia');	
	}
	$apellido=strrev($clave);
	
	entrar($db, $nombre, $clave, $apellido);
	
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

function entrar($db, $nombre, $clave, $apellido){

    $select="SELECT nombre from cliente where nombre='$nombre' and apellido='$apellido'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$nombreExiste=$row['nombre'];

	if($nombreExiste){
		$_SESSION['nombre']=$nombre;
		//crear cookie
		$cookie_name = "usuario";
		$cookie_value =$nombre." ".$apellido;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 segundos = 1 día

		$nif=select_nif($db, $nombre, $apellido);
		$_SESSION["nif"] = $nif;
		$_SESSION["carrito"] = array();

		header("location: usuario.php");
	}
	else{
		
		trigger_error('El usuario o contraseña no son válidos');
	}
   
}

function select_nif($db, $nombre, $apellido){

	$select="SELECT nif from cliente where nombre='$nombre' and apellido='$apellido'";
    $resultado=mysqli_query($db, $select);//el resultado no es valido, hay que tratarlo
	$row=mysqli_fetch_assoc($resultado);
	$nif=$row['nif'];

	return $nif;
}
	

?>



</body>

</html>