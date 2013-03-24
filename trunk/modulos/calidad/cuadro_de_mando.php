<?php

require_once("../../config.php");

variables_form_busqueda("cuadro_de_mando");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="VERDADERO";

$orden = array(
        "default" => "2",
        "1" => "cuie",
        "2" => "nombreefector",
             
       );
$filtro = array(
		"cuie" => "CUIE",
        "nombreefector" => "Nombre",
       
       );
$datos_barra = array(
     array(
        "descripcion"=> "Convenio",
        "cmd"        => "VERDADERO"
     ),
     array(
        "descripcion"=> "Sin Convenio",
        "cmd"        => "FALSO"
     ),
     array(
        "descripcion"=> "Todos",
        "cmd"        => "TODOS"
     )
);

generar_barra_nav($datos_barra);

$sql_tmp="SELECT 
  nacer.efe_conv.id_efe_conv,
  nacer.efe_conv.nombreefector,  
  nacer.efe_conv.cuie
FROM
  nacer.efe_conv";


if ($cmd=="VERDADERO")
    $where_tmp=" (efe_conv.com_gestion='VERDADERO')";
    

if ($cmd=="FALSO")
    $where_tmp=" (efe_conv.com_gestion='FALSO')";

echo $html_header;
?>

<div class="newstyle-full-container">
<form name=form1 action="cuadro_de_mando.php" method=POST>
	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
		<div class="span4">
			<? $link=encode_link("cuadro_de_mando_excel.php",array());?>
			<a href="#" class="pull-right" onclick="window.open('<?=$link?>')"><i class="icon-share-alt"></i> Exportar Excel</a>
		</div>
	</div>
	
<?$result = sql($sql) or die;?>

<div class="pull-right">Total: <?=$total_muletos?> <?=$link_pagina?></div> 
<table class="table table-condensed table-bordered table-hover">
	<thead>
		<tr>
			<th><a id=mo href='<?=encode_link("cuadro_de_mando.php",array("sort"=>"1","up"=>$up))?>'>CUIE</a></th>      	
			<th><a id=mo href='<?=encode_link("cuadro_de_mando.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></th>
			<th align=right id=mo>Tot. Ins. ACT.</th>    
			<th align=right id=mo>Tot. Ins. INAC.</th>    
			<th align=right id=mo>Tot. Ins.</th>  		
		</tr>
	</thead>
  
 <?
   while (!$result->EOF) {?>
    
    <tr>        
     <td><?=$result->fields['cuie']?></td>
     <td><?=$result->fields['nombreefector']?></td>
     <?$cuie=$result->fields['cuie'];
     $sql = "SELECT count (smiafiliados.id_smiafiliados)as r1 from nacer.smiafiliados 
     WHERE cuieefectorasignado='$cuie' and activo='S'";
     $r1=sql($sql,"error R1");     
     ?>
     <td><?=$r1->fields['r1']?></td>
     <?$cuie=$result->fields['cuie'];
     $sql = "SELECT count (smiafiliados.id_smiafiliados)as r1 from nacer.smiafiliados 
     WHERE cuieefectorasignado='$cuie' and activo='N'";
     $r1=sql($sql,"error R1");     
     ?>
     <td><?=$r1->fields['r1']?></td>
     <?$cuie=$result->fields['cuie'];
     $sql = "SELECT count (smiafiliados.id_smiafiliados)as r1 from nacer.smiafiliados 
     WHERE cuieefectorasignado='$cuie'";
     $r1=sql($sql,"error R1");     
     ?>
     <?$total_ins=$r1->fields['r1']?>
     <td><?=$total_ins?></td>


    
	
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>