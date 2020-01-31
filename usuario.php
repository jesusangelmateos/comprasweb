<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>MENU DE USUARIO</h1>
<?php
include "conexion.php";
  
?>
<div class="container ">
<!--Aplicacion-->
<div class="card border-success mb-3" style="max-width: 30rem;">
<div class="card-header">Que desea hacer:</div>
<div class="card-body"></br>
		<div class="form-group">
        Comprar Producto <input type="button" value="Comprar" onclick="window.location.href='comproUsuario.php'">
        </div></br>
        <div class="form-group">
        Consultar Compra <input type="button" value="Consultar" onclick="window.location.href='comconscom.php'">
        </div></br>
        <div class="form-group">
        Cerrar Sesion <input type="button" value="Salir" onclick="window.location.href='salir.php'">
        </div>
		
	</BR>
<?php

?>

<?php
// Funciones utilizadas en el programa

?>

</body>

</html>