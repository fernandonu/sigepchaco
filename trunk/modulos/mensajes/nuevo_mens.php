<?php
	require_once("../../config.php");
	
	switch ($_POST['bot']){
		case "Cancelar": { 
			header('location: ./mensajes.php');
			break;
			}
		case "Enviar mensaje": {
			require "../mensajes/guardar_mens.php";
			break;
			}
		default: { 
?>
<html>
<head>
	<?
	echo "<link rel=stylesheet type='text/css' href='$html_root/newstyle/bootstrap/css/custom-bootstrap.css'>";
	echo "<link rel=stylesheet type='text/css' href='$html_root/newstyle/css/main.css'>";
	echo "<link rel=stylesheet type='text/css' href='$html_root/newstyle/css/smoothness/jquery-ui-1.8.23.custom.css'>";
	
	echo "<script languaje='javascript' src='$html_root/newstyle/js/jquery-1.8.0.min.js'></script>";
	echo "<script languaje='javascript' src='$html_root/newstyle/js/jquery-ui-1.8.23.custom.min.js'></script>";
	echo "<script languaje='javascript' src='$html_root/newstyle/js/jquery-ui-datepicker-es.js'></script>";
	//
	?>
	<script>
		$(document).ready(function () {
			$(".date-input").datepicker({ minDate: "0" });
		});
		
		function comprueba() {
			if(document.form.venc.value=='') {
				alert("Debe seleccionar fecha de vencimiento.");
				return false;
			}
			if(document.form.para.value=='?') {
				alert("Debe seleccionar usuario.");
				return false;
			}
			if(document.form.nota.value=='') {
				alert("El mansaje está en blanco.");
				return false;
			}
			return true;
		}
	</script>
</head>
<body>
	<div  class="newstyle-full-container">
	
	<form name="form" action="nuevo_mens.php" method="post">
		<legend>Nuevo Mensaje</legend>
		<input type="hidden" name="tipo_m" value=1>
		
		<div class="row-fluid">
			<div class="span3">
				<label>Para:</label>
				<select class="input-large" name="para">
					<option value='?'>Seleccione</option>
					<?php
						$ssql1="select login, nombre, apellido from usuarios where nombre!='root' order by apellido;";
						db_tipo_res('a');
						$result1=$db->Execute($ssql1) or die($db->ErrorMsg());
						while(!$result1->EOF){
					?>
							<option value='<?=$result1->fields['login']?>'>
					<?php 
							echo $result1->fields['apellido']. ' '.$result1->fields['nombre'];
					?>
							</option>
					<?php 
						$result1->MoveNext();
						}//while
						?>
					<option value='Todos'>Todos</option>
				</select>
			</div>
			<div class="span3">
				<label>Fecha de Vencimieto:</label>
				<input class="input-large date-input" name="venc" type=text >
				<input type="hidden" name="hora" value="00:00">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<label>Mensaje:</label>
				<textarea class="span12" name="nota"></textarea>
			</div>
		</div>
		
		<div class="form-actions">
			<input class="btn btn-primary" type="submit" name="bot" value="Enviar mensaje" onClick="return comprueba();">
			<input class="btn" type="submit" name="bot" value="Cancelar">
		</div>
	</form>
	
	</div>
</body>
</html>

<?php
 }//default
} //fin switch
?>
