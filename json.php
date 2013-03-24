<?php
require_once "config3.php";

$dni = $_GET["dni"];

$sql= "Select clave_beneficiario clave, id_categoria categoria, apellido_benef || ' ' || nombre_benef apynom, 
	clase_documento_benef clasedoc, tipo_documento tipodoc, numero_doc dni, localidad, cuie_ea cuie,
	fecha_probable_parto fpp, fecha_efectiva_parto fep, fecha_nacimiento_benef fechanac, fecha_inscripcion fechains
	from uad.beneficiarios  
where estado_envio = 'n'
and numero_doc = '$dni'";
$result=pg_exec($sql);


while ($row = pg_fetch_object($result)) 
{
$arr[] = $row;
//echo json_encode($row);
}

	
print json_encode($arr);
?>
