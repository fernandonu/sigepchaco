<?php
/*
Author: ferni 

modificada por
$Author: ferni $
$Revision: 1.30 $
$Date: 2006/07/20 15:22:40 $
*/
require_once("../../config.php");

variables_form_busqueda("par_listado");

$orden = array(
        "default" => "1",
        "1" => "nombreefector",
        "2" => "num_doc",       
        "3" => "apellido",       
        "4" => "nombre",        
        "5" => "id_par",        
        "6" => "fecha_parto"        
       );
$filtro = array(		
		"nombreefector" => "Nombre Efector",		
		"num_doc" => "Documento",		
		"apellido" => "Apellido",		
		"nombre" => "Nombre",		
       );
$sql_tmp="SELECT * FROM trazadoras.partos
			left join facturacion.smiefectores using (CUIE)";

echo $html_header;

if (permisos_check("inicio","genera_archivo_permiso")) $permiso="";
else $permiso="disabled";
?>
<form name=form1 action="par_listado.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	    &nbsp;&nbsp;<? $link=encode_link("par_listado_excel.php",array());?>
        <img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">
	  
	    &nbsp;&nbsp;<input type='button' name="nueva_par" value='Nuevo Dato' onclick="document.location='par_admin.php'">
	    &nbsp;&nbsp;<input type=submit name="generarpar" value='Generar Archivo' <?=$permiso?>>
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;

if ($_POST['generarpar']){
	$filename = 'P12200800000002.txt';	

	  	if (!$handle = fopen($filename, 'a')) {
        	 echo "No se Puede abrir ($filename)";
         	exit;
    	}
		$result1=sql($sql_tmp) or die;
    	$result1->movefirst();
    	while (!$result1->EOF) {
    		$contenido=$result1->fields['cuie'];
    		$contenido.=chr(9);
    		$contenido.=chr(9);
    		$contenido.=$result1->fields['tipo_doc'];
    		$contenido.=chr(9);
    		$contenido.=number_format($result1->fields['num_doc'],0,'','');
    		$contenido.=chr(9);
    		$contenido.=trim($result1->fields['apellido']);
    		$contenido.=chr(9);
    		$contenido.=trim($result1->fields['nombre']);
    		$contenido.=chr(9);
    		$contenido.=$result1->fields['fecha_parto'];
    		$contenido.=chr(9);
    		$contenido.=number_format($result1->fields['apgar'],0,"","");
    		$contenido.=chr(9);
    		$contenido.=number_format($result1->fields['peso'],3,".","");
    		$contenido.=chr(9);
    		$contenido.=$result1->fields['vdrl'];
    		$contenido.=chr(9);
    		$contenido.=$result1->fields['antitetanica'];
    		$contenido.=chr(9);
    		if ($result1->fields['fecha_conserjeria']!="1980-01-01") $contenido.=$result1->fields['fecha_conserjeria'];    		
    		else $contenido.="";
    		$contenido.="\n";
    		if (fwrite($handle, $contenido) === FALSE) {
        		echo "No se Puede escribir  ($filename)";
        		exit;
    		}
    		$result1->MoveNext();
    	}
    	echo "El Archivo ($filename) se genero con exito";
    
    	fclose($handle);
	
}?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=10 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id=mo><a id=mo href='<?=encode_link("par_listado.php",array("sort"=>"5","up"=>$up))?>'>ID</a></td>      	    
    <td align=right id=mo><a id=mo href='<?=encode_link("par_listado.php",array("sort"=>"6","up"=>$up))?>'>Fecha Parto</a></td>      	    
    <td align=right id=mo><a id=mo href='<?=encode_link("par_listado.php",array("sort"=>"1","up"=>$up))?>'>Efector</a></td>      	    
    <td align=right id=mo><a id=mo href='<?=encode_link("par_listado.php",array("sort"=>"2","up"=>$up))?>'>Documento</a></td>      	    
    <td align=right id=mo><a id=mo href='<?=encode_link("par_listado.php",array("sort"=>"3","up"=>$up))?>'>Apellido</a></td>      	    
    <td align=right id=mo><a id=mo href='<?=encode_link("par_listado.php",array("sort"=>"4","up"=>$up))?>'>Nombre</a></td>      	    
  </tr>
 <?
   while (!$result->EOF) {
   	$ref = encode_link("par_admin.php",array("id_planilla"=>$result->fields['id_par']));
    $onclick_elegir="location.href='$ref'";?>
  
    <tr <?=atrib_tr()?>>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['id_par']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=Fecha($result->fields['fecha_parto'])?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombreefector']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=number_format($result->fields['num_doc'],0,'','')?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>     
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>