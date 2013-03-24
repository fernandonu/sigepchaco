<?php
	require_once("../../config.php");
	
	switch ($_POST['Submit']) {
		case "Cancelar":{ 
			header('location: ./mensajes.php');
			break;
			}
		case "Reenviar": {
			include_once "./guardar_mens.php";
			break;
			}
		default:{
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
				alert("El mensaje está en blanco.");
				return false;
			}
			return true;
		}
	</script>
</head>

<body>
	<div class="newstyle-full-container">
		<form name="form" method="post" action="redirige.php">
			<legend>Reenviar Mensaje</legend>
			
			<?php
				$id_mensaje=$_POST['radio']; 
				$ssql_busca="select numero, nro_orden,usuario_destino,comentario,fecha_vencimiento from mensajes where id_mensaje=".$id_mensaje;
				db_tipo_res('a');
				$result=$db->Execute($ssql_busca) or die($db->ErrorMsg());
			?>
			
			<input type="hidden" name="id_m" value="<? echo $id_mensaje;?>">
			<input type="hidden" name="tipo_m" value=0>
			<input type="hidden" name="tipo2" value='MRU'>
			<input type="hidden" name="nro_ord" value="<?php echo $result->fields['nro_orden'] +1;?>" >
			
			<div class="row-fluid">
				<div class="span3">
					<label>Para:</label>
				
					<select class="input-large" name="para">
						<option value='?'></option>
						<?php
							$ssql1="select nombre from usuarios where nombre!='root';";
							db_tipo_res('a');
							$result1=$db->Execute($ssql1) or die($db->ErrorMsg());
							
							while(!$result1->EOF){
						?>
								<option> 
								<? echo $result1->fields['nombre'];?>
								</option>
						<?php 
								$result1->MoveNext();
							}// end while
						?>
						<option selected> 
							<? 
								echo $result->fields['usuario_destino']; 
							?>
						</option>
						<option>Todos</option>
					</select>
				</div>
				<div class="span3">
					<label>Fecha de Vencimieto:</label>
					<? 
						$fech=substr($result->fields['fecha_vencimiento'],0,10);
						$hora=substr($result->fields['fecha_vencimiento'],11,16);
					?>
					
					<input class="input-large date-input" name="venc" value="<?php echo fecha($fech);?>" type=text >
					<input type="hidden" name="hora" value="<? echo $hora;?>">
					
					<script>
						$(document).ready(function () {
							$(".date-input").datepicker({ minDate: "0" });
							//$(".date-input").datepicker("setDate", new Date());
						});
					</script>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<label>Mensaje:</label>
					<textarea class="span12" name="nota"><?php echo $result->fields['comentario']; ?></textarea>
					<input type="hidden" name="anterior" value="<?php echo $result->fields['comentario']; ?>">
				</div>
			</div>
			<div class="form-actions">
				<input class="btn btn-primary" type="submit" name="Submit" value="Reenviar" onClick="return comprueba();">
				<input class="btn" type="submit" name="Submit" value="Cancelar">
			</div>
		</form>
	<?php
			}// end default
		} // end switch
	?>
	</div>
</body>
</html>