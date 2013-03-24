<?php
/*
Author: gaby 

modificada por
$Author: gaby $
$Revision: 1.0 $
$Date: 2006/07/20 15:22:40 $
*/
require_once("../../config.php");


echo $html_header;?>

<div class="newstyle-full-container">

<form name=form1 action="puco_listado_express.php" method=POST>

<div class="row-fluid">
		<div class="span8">
			<input type="text" size="30" value="" name="documento" >
			<input type=submit name="buscar" value='Buscar' class='btn'>
		</div>
</div>

<table class="table table-condensed table-bordered table-hover">
		
		<thead>
			<tr>
				<th>Documento</th>      	  
				<th>Nombre</a></th>
				<th>Obra Social</th>
			</tr>
		</thead>
  
<? if ($_POST['buscar']){
    	$documento=$_POST['documento'];
    	if(strlen($documento)>=7){
    			$sql_tmp="SELECT puco.documento,puco.nombre,obras_sociales.nombre AS obra_social
				FROM puco.puco
				INNER JOIN puco.obras_sociales ON (puco.puco.cod_os = puco.obras_sociales.cod_os)
				WHERE (puco.puco.documento ='$documento')";
				$query=sql($sql_tmp,"ERROR al realizar la consulta")or fin_pagina();
    					
    			if($query->recordCount()==0){?> 
    				   	<tr>   
						     <td align="center" colspan="3">NO SE ENCONTRARON DATOS</td> 						      
						</tr> 
				<?}
				else{ 
					while (!$query->EOF) {?>
						    <tr>   
						     <td><?=$query->fields['documento']?></td>      
						     <td><?=$query->fields['nombre']?></td>
						     <td ><?=$query->fields['obra_social']?></td> 
						    </tr>    
							<?$query->MoveNext();
					}//FIN WHILE
			  	}//fin else  
    	 }  
    	 else{?>

    	 		<p align="center" colspan="3">DEBE INGRESAR A MENOS 7 NUMEROS</p> 						      
 	 	
    	 <?}
     	}//fin IF
     ?>
</table>


</form>
</div>
</body>
</html>

