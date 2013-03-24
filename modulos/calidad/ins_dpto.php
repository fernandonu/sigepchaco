<?php

require_once("../../config.php");

variables_form_busqueda("ins_dpto");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

$orden = array(
        "default" => "1",
        "1" => "nombre"
        
             
       );
$filtro = array(
		"nombre" => "nombre"
                
       );

$sql_tmp="SELECT 
  *
FROM
  nacer.dpto";

echo $html_header;
?>

<div class="newstyle-full-container">
<form name=form1 action="ins_dpto.php" method=POST>

	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
		<div class="span4">
			<? $link=encode_link("ins_dpto_excel.php",array());?>
			<a href="#" class="pull-right" onclick="window.open('<?=$link?>')"><i class="icon-share-alt"></i> Exportar Excel</a>
		</div>
	</div>

<?$result = sql($sql) or die;?>

<table class="table table-condensed table-bordered table-hover">

	<thead>
		<tr>
			<th><a id=mo href='<?=encode_link("ins_dpto.php",array("sort"=>"1","up"=>$up))?>'>NOMBRE</a></th>      	
			<th align=right id=mo>Inscriptos ACTIVOS</th>    
			<th align=right id=mo>Inscriptos INACTIVOS</th>    
			<th align=right id=mo>Total Inscriptos</th>  
		</tr>
	</thead>
  
 <?
   while (!$result->EOF) {
   	$ref = encode_link("ins_dpto_admin.php",array("id_dpto"=>$result->fields['id_dpto']));
    	$onclick_elegir="location.href='$ref'";?>
    
    <tr>             
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>
     
     <?$codigo=$result->fields['codigo'];
     $sql = "SELECT count (smiafiliados.id_smiafiliados)as r1 
			from nacer.smiafiliados 
			left join nacer.efe_conv ON (nacer.efe_conv.cuie = nacer.smiafiliados.cuieefectorasignado)
     		WHERE departamento='$codigo' and activo='S'";
     $r1=sql($sql,"error R1");     
     ?>
     <td onclick="<?=$onclick_elegir?>"><?=$r1->fields['r1']?></td>
     
     <?
     $sql = "SELECT count (smiafiliados.id_smiafiliados)as r1 
			from nacer.smiafiliados 
			left join nacer.efe_conv ON (nacer.efe_conv.cuie = nacer.smiafiliados.cuieefectorasignado)
     		WHERE departamento='$codigo' and activo='N'";
     $r2=sql($sql,"error R1");     
     ?>
     <td onclick="<?=$onclick_elegir?>"><?=$r2->fields['r1']?></td>
     
     <td onclick="<?=$onclick_elegir?>"><?=$r1->fields['r1']+$r2->fields['r1']?></td>
     
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>