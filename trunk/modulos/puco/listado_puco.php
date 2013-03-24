<?php

require_once("../../config.php");

variables_form_busqueda("listado_puco");

$orden = array(
        "default" => "1",
        "1" => "documento"        
       );
$filtro = array(
		"documento" => "DNI"         
       );
$sql_tmp="select documento,tipo_doc,puco.nombre,obras_sociales.nombre as nom_os
			from puco.puco
			inner join puco.obras_sociales using (cod_os)";

echo $html_header;?>

<div class="newstyle-full-container">

<form name=form1 action="listado_puco.php" method=POST>
	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input type=submit name="buscar" value='Buscar' class='btn'>
		</div>
	
<?$result = sql($sql) or die;?>

		<div class="span4">
			<p width=30% align=right><b>Total:</b> <?=$total_muletos?></p>
			<p width=40% align=right><?=$link_pagina?></p>
		</div>
	</div>	
	
<table class="table table-condensed table-bordered table-hover">
		
		<thead>
			<tr>
				<th>Número</th>
				<th>Tipo</th>      	  
				<th>Apellido y Nombre</a></th>
				<th>Obra Social</th>
			</tr>
		</thead>

 <?
   while (!$result->EOF) {?>    	  
    <tr>     
     <td ><?=$result->fields['documento']?></td>
     <td ><?=$result->fields['tipo_doc']?></td>
     <td ><?=$result->fields['nombre']?></td>     
     <td ><?=$result->fields['nom_os']?></td>     
    </tr>
	<?$result->MoveNext();
    }?>
    
</table>
</form>
</div>
</body>
</html>
<?echo fin_pagina();// aca termino ?>