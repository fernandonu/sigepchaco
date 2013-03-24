<?
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) 
	extract($parametros,EXTR_OVERWRITE);
cargar_calendario();

if ($borra_efec=='borra_efec'){
	$query="DELETE from sistema.usu_efec  
			WHERE cuie='$cuie'";
	
	sql($query, "Error al eliminar el pcia") or fin_pagina(); 
	$accion="Los datos se han borrado";
}

if ($id_usuario) {
	$query= "SELECT *
			FROM sistema.usuarios  
			WHERE id_usuario=$id_usuario";

	$res_usuario=sql($query, "Error al traer el Comprobantes") or fin_pagina();
	$login=$res_usuario->fields['login'];
	$login=strtoupper($login);
}

if ($_POST['guardar_provincia']=='Guardar'){
	$db->StartTrans();

	for ($i=0;$i<count($cuie);$i++) {     
	$efector = $cuie[$i];   
	$query="INSERT into sistema.usu_efec (cuie, id_usuario)
			VALUES ('$efector', '$id_usuario')";
	sql($query, "Error al insertar Efector") or fin_pagina();
	} 
	$accion="Los datos se han guardado correctamente";    
	$db->CompleteTrans();   
}
//---------------------fin provincia------------------------------

echo $html_header;
?>

<script>
	function editar_campos() {	
		document.all.login.disabled=false;
		document.all.guardar_editar.disabled=false;
		document.all.cancelar_editar.disabled=false;
		document.all.borrar.disabled=false;
		document.all.guardar.enaible=false;
		return true;
	}
	//fin de function control_nuevos()
	//empieza funcion mostrar tabla
	var img_ext='<?=$img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
	var img_cont='<?=$img_cont='../../imagenes/down2.gif' ?>';//imagen contraido

	function muestra_tabla(obj_tabla,nro){
		oimg=eval("document.all.imagen_"+nro);//objeto tipo IMG
		if (obj_tabla.style.display=='none'){
			obj_tabla.style.display='inline';
			oimg.show=0;
			oimg.src=img_ext;
		} else {
			obj_tabla.style.display='none';
			oimg.show=1;
			oimg.src=img_cont;
		}
	}

	//---------------------scrip para provincia------------------------------

	function control_nuevo_provincia(){ 
		if(document.all.cod_provincia.value=="") {
			alert('Debe ingresar un codigo de provincia');
			return false; 
		} 
		if(document.all.nom_provincia.value=="") {
			alert('Debe ingresar una Provincia');
			return false; 
		} 
	} 
	//---------------------fin scrip para provincia---------------------------
</script>

<div class="newstyle-full-container">
<form name='form1' action='usr_efectores_admin.php' method='POST'>
	<input type="hidden" value="<?=$id_usuario?>" name="id_usuario">

	<? 
		if($accion) {
	?>
		<div class="alert alert-info">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<?= $accion ?>
		</div>
	<? 
		}
	?>
	
	<legend>Agregar Efector</legend>
	<div>
		<p><strong>Usuario:</strong> <?= (!$id_usuario)? "Nuevo" : $login ?><p>
		<p><strong>ID Usuario:</strong> <?= ($id_usuario)? $id_usuario : "Nuevo"?><p>
	</div>
	
	<div class="row-fluid">
		
	<? 
		if($id_usuario) {
	?>
		<div class="span6">
			<legend>Efectores</legend>   	
			<select style="width: 100%; height: 150px; margin-bottom: 10px;" multiple name="cuie[]" onKeypress="buscar_combo(this);"	onblur="borrar_buffer();"
				onchange="borrar_buffer();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			<?
				$sql= "select * from nacer.efe_conv order by cuie";
				$res_efectores=sql($sql) or fin_pagina();
				
				while (!$res_efectores->EOF){ 
					$cuiel=$res_efectores->fields['cuie'];
					$nombre_efector=$res_efectores->fields['nombreefector'];
			?>
					<option value='<?=$cuiel?>' <?if ($cuie==$cuiel) echo "selected"?> ><?=$cuiel." - ".$nombre_efector?></option>
			<?
					$res_efectores->movenext();
				}
			?>
			</select>
			
			<div>
				<input class="btn btn-primary pull-right" type="submit" name="guardar_provincia" 
					value="Guardar" title="Guardar" onclick="return control_nuevo_provincia()">
			</div>
		</div>
		
		<div class="span6">
			<legend>Efectores Relacionados</legend>
				
			<table class="table table-condensed table-bordered table-hover" id="prueba_vida">
			<?
				$query="select nacer.efe_conv.nombreefector, nacer.efe_conv.cuie from nacer.efe_conv join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
						join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
						where sistema.usuarios.id_usuario = '$id_usuario' order by cuie";
				
				$res_comprobante=sql($query,"Error al traer los comprobantes") or fin_pagina();
				
				if ($res_comprobante->RecordCount()==0) {
			?>
				<tr>
					<td>
						No existe ningun efector relacionado con este usuario.
					</td>
				</tr>
			 <?
				} else {	 	
			?>
				<thead>
					<tr>

						<th>CUIE</th>
						<th>Efector</th>
						<th>Borrar</th>
					</tr>
				</thead>	
			<?
					$res_comprobante->movefirst();
					while (!$res_comprobante->EOF) {
						$ref = encode_link(" ",array("cuie"=>$res_comprobante->fields['cuie'],"nombreefector"=>$res_comprobante->fields['nombreefector']));
						$onclick_elegir="location.href='$ref'"; 
						$id_tabla="tabla_".$res_comprobante->fields['cuie'];	
						$onclick_check=" javascript:(this.checked)?Mostrar('$id_tabla'):Ocultar('$id_tabla')";
			?>			 		
						<tr>
							
							<td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['cuie']?></td>
							<td onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['nombreefector']?></td>
			<? 
							$ref=encode_link("usr_efectores_admin.php",array("cuie"=>$res_comprobante->fields['cuie'],"borra_efec"=>"borra_efec","id_usuario"=>$id_usuario)); 
							$onclick_provincia="if (confirm('Seguro que desea eliminar el Efector?')) location.href='$ref'"; 
			?>
							<td align="center"><a href="#" onclick="<?=$onclick_provincia?>"><i class="icon-trash"></i> Eliminar</a></td>
						</tr>
			<?
						$res_comprobante->movenext();
					}
				}
			?>	 	
			</table>
		</div>
	</div>

			 
	<?
		} 
	?>
 
	<div class="form-actions">
		<input class="btn btn-info btn-large" type='button' name="volver" value="Volver" onclick="document.location='usr_efectores_listado.php'" title="Volver al Listado">
	</div>   
 </form>
 
</div>
</body>
</html>
