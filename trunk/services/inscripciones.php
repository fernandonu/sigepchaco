<?php
require_once "config.php";

if(!empty($_POST)) {
	if(!empty($_POST["dni"])) {
			$dni = $_POST["dni"];
			$sql= "Select clave_beneficiario clave, id_categoria categoria, apellido_benef || ' ' || nombre_benef apynom, 
			clase_documento_benef clasedoc, tipo_documento tipodoc, numero_doc dni, localidad, cuie_ea cuie,
			fecha_probable_parto fpp, fecha_efectiva_parto fep, fecha_nacimiento_benef fechanac, fecha_inscripcion fechains
			from uad.beneficiarios 
			where estado_envio = 'n'
			and numero_doc = '$dni'";
		}
	}
	
if(!empty($_GET)) {
	if(!empty($_GET["dni"])) {
			$dni = $_GET["dni"];
			$sql= "Select clave_beneficiario clave, id_categoria categoria, apellido_benef || ' ' || nombre_benef apynom, 
			clase_documento_benef clasedoc, tipo_documento tipodoc, numero_doc dni, localidad, cuie_ea cuie,
			fecha_probable_parto fpp, fecha_efectiva_parto fep, fecha_nacimiento_benef fechanac, fecha_inscripcion fechains
			from uad.beneficiarios 
			where estado_envio = 'n'
			and numero_doc = '$dni'";
		} elseif (!empty($_GET["nombre"])) {
			$nombre = $_GET["nombre"];
			$sql= "Select clave_beneficiario clave, id_categoria categoria, apellido_benef || ' ' || nombre_benef apynom, 
			clase_documento_benef clasedoc, tipo_documento tipodoc, numero_doc dni, localidad, cuie_ea cuie,
			fecha_probable_parto fpp, fecha_efectiva_parto fep, fecha_nacimiento_benef fechanac, fecha_inscripcion fechains
			from uad.beneficiarios 
			where estado_envio = 'n'
			and (apellido_benef ilike '%$nombre%' or nombre_benef ilike  '%$nombre%') ";
		} elseif (!empty($_GET["clave"])) {
			$clave = $_GET["clave"];
			$sql= "Select clave_beneficiario clave, id_categoria categoria, apellido_benef || ' ' || nombre_benef apynom, 
			clase_documento_benef clasedoc, tipo_documento tipodoc, numero_doc dni, localidad, cuie_ea cuie,
			fecha_probable_parto fpp, fecha_efectiva_parto fep, fecha_nacimiento_benef fechanac, fecha_inscripcion fechains
			from uad.beneficiarios 
			where estado_envio = 'n'
			and clave_beneficiario = '$clave' ";
		} else {
			$sql= "Select clave_beneficiario clave, id_categoria categoria, apellido_benef || ' ' || nombre_benef apynom, 
			clase_documento_benef clasedoc, tipo_documento tipodoc, numero_doc dni, localidad, cuie_ea cuie,
			fecha_probable_parto fpp, fecha_efectiva_parto fep, fecha_nacimiento_benef fechanac, fecha_inscripcion fechains
			from uad.beneficiarios 
			where estado_envio = 'n'";
		}
	}
	
$result=pg_exec($sql);

while ($row = pg_fetch_object($result)) {$arr[] = $row;}

	
echo json_encode($arr);
?>
