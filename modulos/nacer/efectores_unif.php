<?php

require_once("../../config.php");

if ($_POST['mail_percentilo_nino_bajo']=='Enviar Mail'){
	$query="SELECT * FROM nacer.efe_conv where com_gestion='VERDADERO' ORDER BY cuie";
	$res_efect=sql($query, "Error al traer el Efectores") or fin_pagina();
	
	$contenido_mail_control="Se Enviaron a los Siguientes CAPS, ESTADISTICAS de niños que fueron informados para Trazadoras ";
	$contenido_mail_control.="al PLAN NACER cuyo Percentilo Peso Talla y Percentilo Peso Edad ";
	$contenido_mail_control.="estan por DEBAJO de lo NORMAL.\n\n";
	$res_efect->MoveFirst;
	while (!$res_efect->EOF) {
		$contenido_mail='';
	 	$cuie=$res_efect->fields['cuie'];
	 	$nombre_caps=$res_efect->fields['nombre'];
	 	$query="SELECT mail FROM nacer.mail_efe_conv where cuie='$cuie'";
	    $res_mail=sql($query, "Error al traer el Efectores") or fin_pagina();
	    $query="SELECT 
				  trazadoras.nino.cuie,
				  trazadoras.nino.tipo_doc,
				  trazadoras.nino.apellido,
				  trazadoras.nino.num_doc,
				  trazadoras.nino.nombre,
				  trazadoras.nino.fecha_nac,
				  trazadoras.nino.fecha_control,
				  trazadoras.nino.peso,
				  trazadoras.nino.talla,
				  trazadoras.nino.perim_cefalico,
				  trazadoras.nino.percen_peso_edad,
				  trazadoras.nino.percen_talla_edad,
				  trazadoras.nino.percen_perim_cefali_edad,
				  trazadoras.nino.percen_peso_talla,
				  trazadoras.nino.triple_viral
				FROM
				  trazadoras.nino
				WHERE
				  (trazadoras.nino.cuie = '$cuie') AND 
				  (trazadoras.nino.percen_peso_edad = 'A') AND 
				  (trazadoras.nino.percen_peso_talla = 'A')
				ORDER BY fecha_control DESC
				LIMIT 20";
	    $res_traza=sql($query, "Error al traer el Efectores") or fin_pagina();
        
	    if ($res_traza->RecordCount()>0){
	    	$contenido_mail_control.=$nombre_caps."\n";
	    	
	    	$contenido_mail.="El siguiente listado del CAPS: $nombre_caps contiene los niños que fueron informados para Trazadoras ";
	    	$contenido_mail.="al PLAN NACER cuyo Percentilo Peso Talla y Percentilo Peso Edad estan por DEBAJO de lo NORMAL\n\n";
	    	
	    	while (!$res_traza->EOF) {
		    	$apellido= $res_traza->fields['apellido'];
		    	$nombre= $res_traza->fields['nombre'];
		    	$fecha_nac= $res_traza->fields['fecha_nac'];
		    	$fecha_control= $res_traza->fields['fecha_control'];
		    	$peso= $res_traza->fields['peso'];
		    	$talla= $res_traza->fields['talla'];
		    	$contenido_mail.="Apellido:$apellido Nombre:$nombre Fecha Nacimiento:$fecha_nac Fecha Control:$fecha_control Peso:$peso Talla:$talla \n";
		    	$res_traza->MoveNext();
	    	}
	    	while (!$res_mail->EOF) {
	    		$destino=$res_mail->fields['mail'];
	    		enviar_mail($destino,'','','Estadistica Trazadoras',$contenido_mail,'','');
	    		$res_mail->MoveNext();
	    	}	    	    	
	    }	 	
		$res_efect->MoveNext();	
	}
	enviar_mail('','','','Estadistica Trazadoras',$contenido_mail_control,'','');
	echo 'Se Enviaron los Mail Correctamente';
}

if ($_POST['mail_embarazo_adol']=='Enviar Mail'){
	$query="SELECT * FROM nacer.efe_conv where com_gestion='VERDADERO' ORDER BY cuie";
	$res_efect=sql($query, "Error al traer el Efectores") or fin_pagina();
	
	$contenido_mail_control="Se Enviaron a los Siguientes CAPS, ESTADISTICAS de embarazadas inscriptas ";
	$contenido_mail_control.="al PLAN NACER cuya edad de la mujer es menor de 17 años.\n\n";
	$res_efect->MoveFirst;
	while (!$res_efect->EOF) {
		$contenido_mail='';
	 	$cuie=$res_efect->fields['cuie'];
	 	$nombre_caps=$res_efect->fields['nombre'];
	 	$query="SELECT mail FROM nacer.mail_efe_conv where cuie='$cuie'";
	    $res_mail=sql($query, "Error al traer el Efectores") or fin_pagina();
	    $query="SELECT 
				  nacer.SMIAfiliados.afiApellido,
				  SMIAfiliados.CUIEEfectorAsignado,
				  nacer.SMIAfiliados.afiNombre,
				  nacer.SMIAfiliados.afiTipoDoc,
				  nacer.SMIAfiliados.afiDNI,
				  facturacion.SMIEfectores.NombreEfector,
				  nacer.SMIAfiliados.afiFechaNac,
				  nacer.SMIAfiliados.FechaDiagnosticoEmbarazo,
				  (nacer.SMIAfiliados.FechaDiagnosticoEmbarazo-nacer.SMIAfiliados.afiFechaNac)/365 as edad_emb
				 
				FROM
				  nacer.SMIAfiliados
				  INNER JOIN facturacion.smiefectores ON (nacer.SMIAfiliados.CUIEEfectorAsignado = facturacion.SMIEfectores.CUIE)
				WHERE
				  nacer.SMIAfiliados.afiTipoCategoria = 1 AND 
				  (nacer.SMIAfiliados.FechaDiagnosticoEmbarazo-nacer.SMIAfiliados.afiFechaNac)/365 < 17
				  AND  nacer.SMIAfiliados.CUIEEfectorAsignado = '$cuie'
				LIMIT 20";
	    $res_traza=sql($query, "Error al traer el Efectores") or fin_pagina();
        
	    if ($res_traza->RecordCount()>0){
	    	$contenido_mail_control.=$nombre_caps."\n";
	    	
	    	$contenido_mail.="El siguiente listado del CAPS: $nombre_caps contiene los mujeres que fueron informadas ";
	    	$contenido_mail.="al PLAN NACER cuyo Embarazo es menor a los 17 Años\n\n";
	    	
	    	while (!$res_traza->EOF) {
		    	$apellido= $res_traza->fields['afiapellido'];
		    	$nombre= $res_traza->fields['afinombre'];
		    	$afiDNI= $res_traza->fields['afidni'];
		    	$fecha_nac= $res_traza->fields['afifechanac'];
		    	$FechaDiagnosticoEmbarazo= $res_traza->fields['fechadiagnosticoembarazo'];
		    	$edad_emb= $res_traza->fields['edad_emb'];
		    	$contenido_mail.="Apellido:$apellido Nombre:$nombre DNI:$afiDNI Fecha Nacimiento:$fecha_nac Fecha Diag Emb:$FechaDiagnosticoEmbarazo Edad en Embarazo:$edad_emb \n";
		    	$res_traza->MoveNext();
	    	}
	    	while (!$res_mail->EOF) {
	    		$destino=$res_mail->fields['mail'];
	    		enviar_mail($destino,'','','Estadistica Trazadoras',$contenido_mail,'','');
	    		$res_mail->MoveNext();
	    	}	    	    	
	    }	 	
		$res_efect->MoveNext();	
	}
	enviar_mail('','','','Estadistica Trazadoras',$contenido_mail_control,'','');
	
	echo 'Se Enviaron los Mail Correctamente';
}


	$query="SELECT 
  			nacer.mail_efe_conv.mail,
  			nacer.mail_efe_conv.descripcion,
  			nacer.efe_conv.nombre,
			dpto.nombre as dpto_nombre
			FROM
  			nacer.efe_conv
  			left JOIN nacer.mail_efe_conv ON (nacer.efe_conv.cuie = nacer.mail_efe_conv.cuie)
			left join nacer.dpto on dpto.codigo=efe_conv.departamento
  			WHERE  (efe_conv.com_gestion='VERDADERO')
			order by dpto.nombre";
	$res_mail=sql($query, "Error lista mail") or fin_pagina();
	$res_mail->movefirst();
	
	$ar=fopen("mail.txt","w") or die("Problemas en la creacion");	
	$ar1=fopen("mail_solos.txt","w") or die("Problemas en la creacion");
  	while (!$res_mail->EOF) {
		$str='"';
		$str.=$res_mail->fields['nombre'];
		$str.=' - ';
		$str.=$res_mail->fields['descripcion'];
		$str.='" '.chr(60);
		$str.=$res_mail->fields['mail'];
		$str.=chr(62).', ';
		$str1='"Dpto: ';
		$str1.=$res_mail->fields['dpto_nombre'];
		$str1.='" '.chr(60);
		$str1.=$res_mail->fields['mail'];
		$str1.=chr(62).', ';
		fputs($ar,$str);
		fputs($ar1,$str1);
		$res_mail->movenext();
	}
	fclose($ar);
	fclose($ar1);

variables_form_busqueda("efectores_unif");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="VERDADERO";

$orden = array(
        "default" => "1",
        "1" => "cuie",
        "2" => "efe_conv.nombre",        
        "3" => "cuidad",    
        "9" => "nombre_dpto"    
       );
       
$filtro = array(
		"cuie" => "CUIE",
        "efe_conv.nombre" => "Nombre",
        "referente" => "Referente"
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
  efe_conv.*,zona_sani.*,dpto.nombre as nombre_dpto
FROM
  nacer.efe_conv
  left join facturacion.nomenclador_detalle using (id_nomenclador_detalle)
  left join nacer.zona_sani using (id_zona_sani)
  left join nacer.dpto on dpto.codigo=efe_conv.departamento";


if ($cmd=="VERDADERO")
    $where_tmp=" (efe_conv.com_gestion='VERDADERO')";
    

if ($cmd=="FALSO")
    $where_tmp=" (efe_conv.com_gestion='FALSO')";

echo $html_header;
?>
<form name=form1 action="efectores_unif.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	     &nbsp;&nbsp;
	    <? $link=encode_link("efectores_unif_excel.php",array("cmd"=>$cmd));?>
        <img src="../../imagenes/excel.gif" style='cursor:hand;'  onclick="window.open('<?=$link?>')">
        &nbsp;&nbsp;
        <b><a href=mail.txt target="_blank">Mail con Descripcion</a></b>
        &nbsp;&nbsp;
        <b><a href=mail_solos.txt target="_blank">Mail</a></b>
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"1","up"=>$up))?>'>CUIE</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"2","up"=>$up))?>'>Nombre</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"3","up"=>$up))?>'>Cuidad</a></td>        
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"5","up"=>$up))?>'>Referente</a></td>        
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"6","up"=>$up))?>'>Telefono</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"7","up"=>$up))?>'>Mail</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"8","up"=>$up))?>'>Comp Gestion</a></td>    
    <td align=right id=mo><a id=mo href='<?=encode_link("efectores_unif.php",array("sort"=>"9","up"=>$up))?>'>Departamento</a></td>    
   </tr>
 <?
   while (!$result->EOF) {
  	$ref = encode_link("efectores_unif_admin.php",array("id_efe_conv"=>$result->fields['id_efe_conv']));
    $onclick_elegir="location.href='$ref'";?>
    
    <tr <?=atrib_tr()?>>        
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuie']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuidad']?></td>       
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['referente']?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['tel']?></td> 
     <?$cuie=$result->fields['cuie'];
     $sql="select * from nacer.mail_efe_conv where cuie = '$cuie'";
     $result_mail=sql($sql,'Error');
     $result_mail->movefirst();
     $contenido_mail='';
     while (!$result_mail->EOF) {
     	$contenido_mail.=$result_mail->fields['descripcion'].': '.$result_mail->fields['mail'].' ';
     	$result_mail->MoveNext();
     }
     ?>  
     <td onclick="<?=$onclick_elegir?>"><?=$contenido_mail?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_comp_ges'])?></td>  
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre_dpto']?></td>  
    </tr>
	<?$result->MoveNext();
    }

    if (permisos_check('inicio','mail_masivos')){	
    ?>    
    <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left>
        <b>Envia Mail a TODOS los CAPS con Convenio, de todos los NIÑOS con Percentilos de Peso Bajo:</b>
        <input type="submit" value="Enviar Mail" name="mail_percentilo_nino_bajo">
       </td>       
      </tr>	
    </table>
   </td>
  </tr>
   <tr>
  	<td colspan=15 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left>
        <b>Envia Mail a TODOS los CAPS con Convenio, de Mujeres con embarazo Adolecente:</b>
        <input type="submit" value="Enviar Mail" name="mail_embarazo_adol">
       </td>       
      </tr>	
    </table>
   </td>
  </tr>
  <?}?>
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>