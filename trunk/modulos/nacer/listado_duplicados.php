<?php

require_once("../../config.php");

variables_form_busqueda("listado_duplicados");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="activos";

$orden = array(
        "default" => "1",
        "1" => "manrodocumento",
        "2" => "afifechanac",
        "3" => "afiapellido",
        "4" => "afinombre",
        "5" => "afidni",        
        "6" => "fechainscripcion",         
       );
$filtro = array(
		"afidni" => "DNI",
        "afiapellido" => "Apellido",
        "afinombre" => "Nombre",          
       );

$sql_tmp="SELECT * FROM nacer.smiafiliados";
$where_tmp=" (activo, manrodocumento, afifechanac)
IN(
SELECT activo, manrodocumento, afifechanac FROM nacer.smiafiliados
GROUP BY activo, manrodocumento, afifechanac
HAVING count(*)>1) and activo='S' and manrodocumento<>''";
    
echo $html_header;
?>

<div class="newstyle-full-container">
<form name=form1 action="listado_duplicados.php" method=POST>

	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
		<div class="span4">
			<? $link=encode_link("listado_duplicado_excel.php",array());?>
			<a href="#" class="pull-right" onclick="window.open('<?=$link?>')"><i class="icon-share-alt"></i> Exportar Excel</a>
		</div>
	</div>

<?$result = sql($sql) or die;?>

<div class="pull-right paginador">
	<?=$total_muletos?> beneficiarios encontrados. 
	<?=$link_pagina?>
</div> 

<table class="table table-condensed table-bordered table-hover">
		
	<thead>
		<tr>
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"1","up"=>$up))?>'>DNI Madre</a></th>      	
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"2","up"=>$up))?>'>Madre</a></th>
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"2","up"=>$up))?>'>Nacimiento Niño</a></th>
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"3","up"=>$up))?>'>Apellido</a></th>
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"4","up"=>$up))?>'>Nombre</a></th>
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"5","up"=>$up))?>'>DNI</a></th>
			<th><a id=mo href='<?=encode_link("listado_duplicados.php",array("sort"=>"6","up"=>$up))?>'>Inscripción</a></th> 				
		</tr>
	</thead>

 <?
   while (!$result->EOF) {?>
  
    <tr>        
     <td><?=$result->fields['manrodocumento']?></td>
     <td><?=$result->fields['maapellido'].", ".$result->fields['manombre']?></td> 
     <td><?=fecha($result->fields['afifechanac'])?></td>
     <td><?=$result->fields['afiapellido']?></td>     
     <td><?=$result->fields['afinombre']?></td>     
     <td><?=$result->fields['afidni']?></td> 
     <td><?=fecha($result->fields['fechainscripcion'])?></td>  
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</div>
</body>
</html>
