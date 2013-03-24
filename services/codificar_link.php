<?
	require_once ("../config.php");
	include_once('../modulos/inscripcion/lib_inscripcion.php');
	$id = $_POST['bid'];
	echo encode_link("ins_admin.php",array("id_planilla"=>$id));
?>