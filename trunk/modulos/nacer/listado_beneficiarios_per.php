<?php

require_once("../../config.php");

variables_form_busqueda("listado_beneficiarios_per");

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
        "8" => "periodo",
        "9" => "activo",
        "10" => "fechainscripcion",
        "11" => "fechacarga",
        "12" => "usuariocarga"
       );
$filtro = array(
		"afidni" => "DNI",
        "afiapellido" => "Apellido",
        "afinombre" => "Nombre",
        "descripcion" => "Tipo Afiliado",
        "nombreefector"=>"Nombre Efector",
        "activo"=>"Activo",
        "smiafiliados.clavebeneficiario"=>"Clave Beneficiario",       
        "periodo"=>"Periodo",
        "fechainscripcion"=>"Fecha de Inscripcion",
        "fechacarga"=>"Fecha de Carga",
        "usuariocarga"=>"Usuario Carga"      
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



$sql_tmp="SELECT
      DISTINCT
      
      SMIAfiliados.ClaveBeneficiario,
      SMIAfiliados.afiApellido    ,
      SMIAfiliados.afiNombre    ,
      SMIAfiliados.afiTipoDoc ,
      SMIAfiliados.afiDNI       ,
      SMIAfiliados.afiSexo ,
      SMIAfiliados.CUIEEfectorAsignado,
      SMIAfiliados.MotivoBaja,
      SMIAfiliados.MensajeBaja,
      SMIAfiliados.activo,
      SMIAfiliados.fechainscripcion,
      SMIAfiliados.fechacarga,
      SMIAfiliados.usuariocarga,
      SMIEfectores.NombreEfector,
      SMITiposCategorias.Descripcion,
      SMIProcesoAfiliados.Id_ProcAfiliado,
      SMIProcesoAfiliados.Periodo,
      SMIProcesoAfiliados.CodigoCIAltaDatos


FROM nacer.smiafiliados
left join nacer.SMIAfiliadosAux on (SMIAfiliados.clavebeneficiario = SMIAfiliadosAux.clavebeneficiario)
left join nacer.SMIProcesoAfiliados on (SMIProcesoAfiliados.Id_ProcAfiliado = SMIAfiliadosAux.Id_ProcesoIngresoAfiliados)
left join facturacion.SMIEfectores on (SMIAfiliados.CUIEEfectorAsignado = SMIEfectores.CUIE)

left join nacer.smitiposcategorias on (SMIAfiliados.afiTipoCategoria = smitiposcategorias.CodCategoria)";


if ($cmd=="activos")
    $where_tmp=" (smiafiliados.activo='S')";
    

if ($cmd=="inactivos")
    $where_tmp=" (smiafiliados.activo='N')";

echo $html_header;
?>

<div class="newstyle-full-container">

<? generar_barra_nav($datos_barra); ?>

<form name=form1 action="listado_beneficiarios_per.php" method=POST>

	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
		<div class="span4">
			<? $link=encode_link("listado_beneficiarios_excel_per.php",array("cmd"=>$cmd));?>
			<a href="#" class="pull-right" onclick="window.open('<?=$link?>')"><i class="icon-share-alt"></i> Exportar Excel</a>
		</div>
	</div>

<?$result = sql($sql) or die;?>

<div class="pull-right paginador">
	<?=$total_muletos?> beneficiarios encontrados.
	<?=$link_pagina?>
</div> 

<table class="table table-condensed table-hover">
 
	<thead>
		<tr>
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"1","up"=>$up))?>' >Apellido</a></th>      	    
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></th>      	    
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"3","up"=>$up))?>'>DNI</a></th>      	    
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"4","up"=>$up))?>'>Tipo Beneficiario</a></th>
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"5","up"=>$up))?>'>Nombre Efector</a></th>
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"8","up"=>$up))?>'>Periodo</a></th>
			<?if (($cmd=="todos")||($cmd=="inactivos")){?>
				<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"9","up"=>$up))?>'>Activo</a></th>
				<th>Cod Baja</th>
				<th>Mensaje Baja</th>    
			<?}?>  
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"7","up"=>$up))?>'>Clave Beneficiario</a></th>
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"10","up"=>$up))?>'>F Ins</a></th>
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"11","up"=>$up))?>'>F Carga</a></th>
			<th><a id=mo href='<?=encode_link("listado_beneficiarios_per.php",array("sort"=>"12","up"=>$up))?>'>Usu Carga</a></th>
		</tr>
	</thead>
		
  <tbody>
 
 <?
   while (!$result->EOF) {?>
    <tr>     
     <td><?=$result->fields['afiapellido']?></td>
     <td><?=$result->fields['afinombre']?></td>
     <td><?=$result->fields['afidni']?></td>     
     <td><?=$result->fields['descripcion']?></td>     
     <td><?=$result->fields['nombreefector']?></td> 
     <td><?=$result->fields['periodo']?></td> 
     <?if (($cmd=="todos")||($cmd=="inactivos")){?>    
     	<td><?=$result->fields['activo']?></td> 
     	<td><?=$result->fields['motivobaja']?></td> 
     	<td><?=$result->fields['mensajebaja']?></td> 
     <?}?>      
     <td><?=$result->fields['clavebeneficiario']?></td> 
     <td><?=fecha($result->fields['fechainscripcion'])?></td>  
     <td><?=fecha($result->fields['fechacarga'])?></td>  
     <td><?=$result->fields['usuariocarga']?></td>   
    </tr>
	<?$result->MoveNext();
    }?>
    
	</tbody>
</table>
</form>
</div>
</body>
</html>
<?//echo fin_pagina();// aca termino ?>