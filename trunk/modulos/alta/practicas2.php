<?php

//require_once '../../config2.php';

$cuie = $_GET['cuie']; 

print $cuie;
//echo "<script>alert('$cuie')</script>";

## generamos la consulta
echo "select top 10 clavebeneficiario, dni, nombre, cuie from practicastempweb where cuie =$cuie"  ;


str_replace ('\\', $cuie, "{*}");
 
//mssql_close($link);

?>
