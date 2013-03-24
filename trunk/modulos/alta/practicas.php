<?php

require_once '../../config2.php';

$cuie = $_GET['cuie']; 
//$cuie = 'H04407';

## generamos la consulta
$result=mssql_query("select top 10 clavebeneficiario, dni, nombre, cuie from practicastempweb where cuie = $cuie" ,$link);

echo "<?xml version='1.0' encoding='utf-8'?>";

echo "<rows>";
echo "<page>1</page>";
echo "<total>1</total>";
echo "<records>100</records>";

## recorremos todos los registros
while($row=mssql_fetch_array($result))
{
	
	print "<row>";
	for($i=0;$i<count($row);$i++)
		print "<cell>".$row[$i]."</cell>";
	print "</row>";
}
echo "</rows>";
## cerramos la conexion
mssql_close($link);

?>
