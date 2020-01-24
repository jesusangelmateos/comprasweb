<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>CONSULTAR COMPRAS</h1>
<?php
include "conexion.php";


/* Se muestra el formulario la primera vez */
if (!isset($_POST) || empty($_POST)) { 

	$clientes = obtenerClientes($db);
	
    /* Se inicializa la lista valores*/
	echo '<form action="" method="post">';
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Datos</div>
<div class="card-body">
	<div class="form-group">
		<label for="cliente">CLIENTE:</label>
		<select name="cliente">
		<?php foreach($clientes as $clientes) : ?>
					<option> <?php echo $clientes ?> </option>
				<?php endforeach; ?></select><br><br>
		</select>
	</div>
	<div class="form-group">
        FECHA DESDE<input type="date" name="fechaDesde" class="form-control">
    </div></br>
    <div class="form-group">
        FECHA HASTA<input type="date" name="fechaHasta" class="form-control">
    </div>
	</BR>
<?php
	echo '<div><input type="submit" value="Consultar"></div>
	</form>';
} else { 
	
	$fechaDesde=strtotime($_REQUEST['fechaDesde']);
	$fechaDesde=date("Y-m-d", $fechaDesde);
    if($fechaDesde==""){
		trigger_error('Fecha desde no puede estar vacia');	
	}
	$fechaHasta=strtotime($_REQUEST['fechaHasta']);
	$fechaHasta=date("Y-m-d", $fechaHasta);
	if($fechaHasta==""){
		trigger_error('Fecha hasta no puede estar vacia');	
	}
	$nifCliente=$_REQUEST['cliente'];
    
    listar($db, $nifCliente, $fechaDesde, $fechaHasta);
	
}
?>

<?php
// Funciones utilizadas en el programa

function obtenerClientes($db){
    $clientes = array();

    $sql = "SELECT NIF FROM CLIENTE";

    $resultado = mysqli_query($db, $sql);
    if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
			$clientes[] = $row['NIF'];
		}
	}
	return $clientes;
}

function listar($db, $nifCliente, $fechaDesde, $fechaHasta){

	$fechaCompras = array();
	$sql="SELECT FECHA_COMPRA FROM COMPRA WHERE DATE_FORMAT(FECHA_COMPRA,'%Y-%m-%d')>='$fechaDesde' AND DATE_FORMAT(FECHA_COMPRA,'%Y-%m-%d')<='$fechaHasta' AND NIF='$nifCliente'";
	$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
	if ($resultado) {
		while ($row = mysqli_fetch_assoc($resultado)) {
            $fechaCompras[] = $row['FECHA_COMPRA'];
		}
	}
	//var_dump($fechaCompras);
	if(count($fechaCompras)==0){
        echo "No ha hecho ninguna compra en estas fechas";
	}
	else{
		$totalCompras=0;
		foreach($fechaCompras as $fechaCompra) {
			$sql = "SELECT ID_PRODUCTO FROM COMPRA WHERE FECHA_COMPRA='$fechaCompra'";
			$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
			$row=mysqli_fetch_assoc($resultado);
			$idProducto=$row['ID_PRODUCTO'];

			$sql="SELECT ID_PRODUCTO, NOMBRE, PRECIO FROM PRODUCTO WHERE ID_PRODUCTO='$idProducto'";
			$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
			$row=mysqli_fetch_assoc($resultado);
			$producto=$row['ID_PRODUCTO'];
			$nombreProducto=$row['NOMBRE'];
			$precioProducto=$row['PRECIO'];

			$totalCompras++;

			echo "Cliente: ".$nifCliente." || PRODUCTO: ".$producto." || Nombre Producto: ".$nombreProducto." ||  Precio: ".$precioProducto."</br>";
		}
		echo "Total de compras del cliente: ".$totalCompras;	
	}	

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
	

?>



</body>

</html>