<?php
	require_once("../../config.php");

	variables_form_busqueda("listado_beneficiarios");

	$fecha_hoy=date("Y-m-d H:i:s");
	$fecha_hoy=fecha($fecha_hoy);

	if ($cmd == "")  $cmd="activos";

	$orden = array(
			"default" => "1",
			"1" => "afiapellido",
			"2" => "afinombre",
			"3" => "afidni",
			"4" => "afitipocategoria",
			"5" => "nombreefector",
			"6" => "activo",
			"7" => "clavebeneficiario",
			"8" => "activo",
			"9" => "fechainscripcion",
			"10" => "fechacarga",
			"11" => "usuariocarga",
			"12" => "motivobaja",
			"13" => "mensajebaja"
		   );
	$filtro = array(
			"afidni" => "DNI",
			"afiapellido" => "Apellido",
			"afinombre" => "Nombre",
			"descripcion" => "Tipo Afiliado",
			"nombreefector"=>"Nombre Efector",
			"activo"=>"Activo",
			"clavebeneficiario"=>"Clave Beneficiario", 
			"fechainscripcion"=>"Fecha de Inscripcion",
			"fechacarga"=>"Fecha de Carga",
			"usuariocarga"=>"Usuario Carga",     
			"motivobaja"=>"Cod. Baja",     
			"mensajebaja"=>"Mensaje Baja"     
		   );
	$datos_barra = array(
		 array(
			"descripcion"=> "Activos",
			"cmd"        => "activos"
		 ),
		 array(
			"descripcion"=> "Inactivos",
			"cmd"        => "inactivos"
		 ),
		 array(
			"descripcion"=> "Todos",
			"cmd"        => "todos"
		 )
	);

	$sql_tmp="select * from nacer.smiafiliados
		 left join nacer.smitiposcategorias on (afitipocategoria=codcategoria)
		 left join facturacion.smiefectores on (cuieefectorasignado=cuie)";


	if ($cmd=="activos")
		$where_tmp=" (smiafiliados.activo='S')";
		

	if ($cmd=="inactivos")
		$where_tmp=" (smiafiliados.activo='N')";

	// MAIN HTML HEADER (config.php)
	echo $html_header;

?>

<div class="newstyle-full-container">

<?

	// SUBMENU
	generar_barra_nav($datos_barra);

?>

<form name=form1 action="listado_beneficiarios.php" method=POST>

	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
		<div class="span4">
			<? $link=encode_link("listado_beneficiarios_excel.php",array("cmd"=>$cmd));?>
			<a href="#" class="pull-right" onclick="window.open('<?=$link?>')"><i class="icon-share-alt"></i> Exportar Excel</a>
		</div>
	</div>

<?$result = sql($sql) or die;?>

<div class="pull-right paginador">
	<?=$total_muletos?> beneficiarios encontrados.
	<?=$link_pagina?>
</div> 

	<table class="table table-condensed table-hover table-striped">
		<thead>
			<tr>
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>' >Apellido</a></th>      	    
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></th>      	    
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></th>      	    
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"4","up"=>$up))?>'>Tipo Beneficiario</a></th>
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"5","up"=>$up))?>'>Efector</a></th>
				<?
					if (($cmd=="todos")||($cmd=="inactivos")) {
				?>
					<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"8","up"=>$up))?>'>Activo</a></th>
					<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"12","up"=>$up))?>'>Codigo de Baja</th>
					<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"13","up"=>$up))?>'>Mensaje de Baja</th>    
				<?
					}
				?>  
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"7","up"=>$up))?>'>Clave Beneficiario</a></th>
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"9","up"=>$up))?>'>Inscripción</a></th>
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"10","up"=>$up))?>'>Carga</a></th>
				<th><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"11","up"=>$up))?>'>Usuario</a></th>
				<?
					if ($cmd=="todos"){
				?>
					<th align=right id=mo>Certificado</th>
				<?
					}
				?>  
			</tr>
		</thead>
		<tbody>
		
		<?
			while (!$result->EOF) {
		?>
		<tr>     
			<td><?=$result->fields['afiapellido']?></td>
			<td><?=$result->fields['afinombre']?></td>
			<td><?=$result->fields['afidni']?></td>     
			<td><?=$result->fields['descripcion']?></td>  
			<td><?=$result->fields['nombreefector']?></td> 
			<?
				if (($cmd=="todos")||($cmd=="inactivos")) {
			?>    
			<td><?=$result->fields['activo']?></td> 
			<td><?=$result->fields['motivobaja']?></td> 
			<td><?=$result->fields['mensajebaja']?></td> 
			<?
				}
			?>     
			<td><?=$result->fields['clavebeneficiario']?></td>  
			<td><?=fecha($result->fields['fechainscripcion'])?></td>  
			<td><?=fecha($result->fields['fechacarga'])?></td>  
			<td><?=$result->fields['usuariocarga']?></td>  
			
			<?
				if ($cmd=="todos") {
					$link=encode_link("certificado_pdf.php", array("id_smiafiliados"=>$result->fields['id_smiafiliados']));	
					echo "<td><a target='_blank' href='".$link."' title='Imprime Certificado'><i class='icon-print'></i> Imprimir</a></td>";
				}
			?> 
			
		</tr>
		<?
				$result->MoveNext();
			}
		?>
		</tbody>
	</table>
</form>
</div>
</body>
</html>

<? // echo fin_pagina(); aca termino ?>