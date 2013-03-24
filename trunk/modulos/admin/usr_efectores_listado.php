<?php
/*
Author: JEM

modificada por
$Author: JEM $
$Revision: 1.0 $
$Date: 2011/07/18 13:41:45 $
*/
require_once("../../config.php");

variables_form_busqueda("usr_efectores_listado");

$orden = array(
        "default" => "1",
        "1" => "login",
		"2" => "apellido"
       );
$filtro = array(
		"login" => "login",
		"apellido" => "apellido"  
       );
$sql_tmp="select * from sistema.usuarios";

echo $html_header;
?>

<div class="newstyle-full-container">
<form name=form1 action="usr_efectores_listado.php" method=POST>

	<div class="row-fluid">
		<div class="span8">
			<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
			<input class="btn" type=submit name="buscar" value='Buscar'>
		</div>
	</div>
	
	<?$result = sql($sql,"No se ejecuto en la consulta principal") or die;?>
	
	<div class="pull-right paginador">
		<?=$total_muletos?> usuarios.
		<?=$link_pagina?>
	</div> 
	
<table class="table table-condensed table-bordered table-hover">

	<thead>
		<tr>
			<th><a id=mo href='<?=encode_link("usr_efectores_listado.php",array("sort"=>"1","up"=>$up))?>'>Nombre de Usuario</a></th>
			<th>Apellido</th>      	
			<th>Nombre</th>
		</tr>
	</thead>


  <?
   while (!$result->EOF) {
   		$ref = encode_link("usr_efectores_admin.php",array("id_usuario"=>$result->fields['id_usuario'],"pagina"=>"usr_efectores_listado"));
    	$onclick_elegir="location.href='$ref'";
   	?>
  
    <tr>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['login']?></td>
	 <td><?=$result->fields['apellido']?></td>
	 <td><?=$result->fields['nombre']?></td>
	 
    </tr>    
	<?$result->MoveNext();
    }?>
  	
</table>
</form>
</div>
</body>
</html>
