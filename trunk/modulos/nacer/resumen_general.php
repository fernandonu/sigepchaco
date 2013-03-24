<?php

require_once("../../config.php");

variables_form_busqueda("resumen_general");
if ($cmd == "")  $cmd="activos";
$orden = array(
        "default" => "1",        
        "1" => "nombreefector",        
       );
$filtro = array(		
        "nombreefector" => "Efector",                
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
generar_barra_nav($datos_barra);
$sql_tmp="SELECT 
  smiefectores.nombreefector,
  count(smiafiliados.id_smiafiliados) AS cb
FROM
  facturacion.smiefectores
  left join nacer.smiafiliados on (cuieefectorasignado=cuie)";

if ($cmd=="activos")
    $where_tmp=" (smiafiliados.activo='S')";
    

if ($cmd=="inactivos")
    $where_tmp=" (smiafiliados.activo='N')"; 
    
if ($cmd=="todos")
    $where_tmp=" smiafiliados.activo in ('S', 'N') "; 
    
$where_tmp.=" 
  GROUP BY  
  smiefectores.nombreefector";
echo $html_header;
?>

<div class="newstyle-full-container">
<form name=form1 action="resumen_general" method=POST>
	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
		<div class="span4">
			<? $link=encode_link("resumen_general_excel.php",array());?>
			<a href="#" class="pull-right" onclick="window.open('<?=$link?>')"><i class="icon-share-alt"></i> Exportar Excel</a>
		</div>
	</div>

<?$result = sql($sql) or die;?>

<div class="pull-right">Total: <?=$total_muletos?> <?=$link_pagina?></div> 

<table class="table table-condensed table-bordered table-hover">

	<thead>
		<tr>
			<th>Efector</th>      	
			<th>Total</th>
		</tr>
	</thead>
	

 <?
   while (!$result->EOF) {?>  	
  
    <tr>     
     <td><?=$result->fields['nombreefector']?></td>     
     <td><?=$result->fields['cb']?></td>          
    </tr>
	<?$result->MoveNext();
   }?>
    
</table>
</form>
</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>