<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web compras</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body>
<h1>RESUMEN COMPRA</h1>
<?php
session_start();
include "conexion.php";

$fechaCompra=date("Y-m-d H:m:s");

actualizar($db, $fechaCompra);

//vaciar la cesta
$_SESION['carrito']=array();


//funciones propias del programa

function actualizar($db, $fechaCompra){

    $nif=$_SESSION['nif'];

    foreach ($_SESSION['carrito'] as $idProducto => $unidades){

        $sql="INSERT INTO COMPRA (NIF, ID_PRODUCTO, FECHA_COMPRA, UNIDADES) VALUES ('$nif','$idProducto','$fechaCompra','$unidades')";
        if(mysqli_query($db, $sql)){
            echo 'idProducto: '.$idProducto.' unidades: '.$unidades;
        }
        else{
            echo 'HA OCURRIDO UN ERROR';
        }

        $cont=0;
				$numAlmancen=10;//numero del primer almacen
				
				while($unidades>$cont){

					$sql="SELECT CANTIDAD FROM ALMACENA WHERE  NUM_ALMACEN='$numAlmancen' AND ID_PRODUCTO='$idProducto'";
					$resultado=mysqli_query($db, $sql);//el resultado no es valido, hay que tratarlo
					$row=mysqli_fetch_assoc($resultado);
					$cantidadAlmacen=$row['CANTIDAD'];

					if($cantidadAlmacen>0){
						$sql="UPDATE ALMACENA SET CANTIDAD=CANTIDAD-1 WHERE NUM_ALMACEN='$numAlmancen' AND ID_PRODUCTO='$idProducto'";
						$resultado=mysqli_query($db, $sql);
						$cont++;
					}
					else{
						$numAlmancen=$numAlmancen+10;
					}
					
				}
           
    }

}

?>

</body>

</html>