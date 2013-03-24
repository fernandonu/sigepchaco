<?php // Principal

require_once ("../../config.php");
include_once('lib_inscripcion.php');

Header('Content-Type: text/html; charset=LATIN1');

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
cargar_calendario();
//Coloca fechas a fecha actual pq los valores estan programados con una fecha de 1890 en formato de base de datos, por ello al habilitar los valores deja la fecha con el valor q corresponde.
($_POST['fecha_nac']=='')?$fecha_nac=date("d/m/Y"):$fecha_nac=$_POST['fecha_nac'];
($_POST['fum']=='')?$fum=date("d/m/Y"):$fum=$_POST['fum'];
($_POST['fecha_diagnostico_embarazo']=='')?$fecha_diagnostico_embarazo=date("d/m/Y"):$fecha_diagnostico_embarazo=$_POST['fecha_diagnostico_embarazo'];
($_POST['fecha_probable_parto']=='')?$fecha_probable_parto=date("d/m/Y"):$fecha_probable_parto=$_POST['fecha_probable_parto'];
($_POST['fecha_efectiva_parto']=='')?$fecha_efectiva_parto=date("d/m/Y"):$fecha_efectiva_parto=$_POST['fecha_efectiva_parto'];
($_POST['fecha_inscripcion']=='')?$fecha_inscripcion=date("d/m/Y"):$fecha_inscripcion=$_POST['fecha_inscripcion'];
$edad=$_POST['edades'];

function calculo_dias($fecha_eq){ // calculamos la diferencia de dias en entero 
		//defino fecha 1
		$anio1 = date('Y');
		$mes1 = date('m');
		$dia1 = date('d');
		//defino fecha 2			
			
		 $dia2 = substr($fecha_eq,0,2);
		 $mes2 = substr($fecha_eq,3,-5);
		 $anio2 = substr($fecha_eq,6,9);
		//calculo timestam de las dos fechas
		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$anio1);
		$timestamp2 = mktime(0,0,0,$mes2,$dia2,$anio2); 
		//resto a una fecha la otra
		$segundos_diferencia = $timestamp1 - $timestamp2;
		//echo $segundos_diferencia;
		
		//convierto segundos en días
		/*$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
		//obtengo el valor absoulto de los días (quito el posible signo negativo)
		$dias_diferencia = abs($dias_diferencia);
		
		//quito los decimales a los días de diferencia
		$dias_diferencia = floor($dias_diferencia); */
		 return ($dias_diferencia); 
}

// Update de Beneficiarios
if ($_POST['guardar_editar']=="Guardar"){
	$db->StartTrans();
	$fecha_carga=date("Y-m-d H:m:s");
	$usuario=$_ses_user['login'];
   	$fecha_nac=Fecha_db($fecha_nac);
   	$fum=Fecha_db($fum);
   	$fecha_diagnostico_embarazo=Fecha_db($fecha_diagnostico_embarazo);
   	$semanas_embarazo=$_POST['semanas_embarazo'];
   	$fecha_probable_parto=Fecha_db($fecha_probable_parto);
   	$clave_beneficiario=$_POST['clave_beneficiario'];
   	$alfabeta=$_POST['alfabeta'];
   	$sexo=$_POST['sexo'];
   	$pais_nac=$_POST['pais_nac'];
   	$indigena=$_POST['indigena'];
    $id_tribu=$_POST['id_tribu'];
    $id_lengua= $_POST['id_lengua'];
    $departamento=$_POST['departamento'];
   	$localidad=$_POST['localidad'];
   	$municipio=$_POST['municipio'];
   	$barrio=$_POST['barrio'];
   	$estudios=$_POST['estudios'];
   	$id_categoria=$_POST['id_categoria'];
	$anio_mayor_nivel=$_POST['anio_mayor_nivel'];
	$responsable=$_POST['responsable'];
	$menor_convive_con_adulto=$_POST['menor_convive_con_adulto'];
	$tipo_doc_madre=$_POST['tipo_doc_madre'];
	$nro_doc_madre=$_POST['nro_doc_madre'];
	$apellido_madre=$_POST['apellido_madre'];
	$nombre_madre=$_POST['nombre_madre'];
	$estudios_madre=$_POST['estudios_madre'];
	$anio_mayor_nivel_madre=$_POST['anio_mayor_nivel_madre'];
   	$score_riesgo=$_POST['score_riesgo'];
   	$mail=$_POST['mail'];
	$celular=$_POST['celular'];
	$otrotel=$_POST['otrotel'];
	$estadoest=$_POST['estadoest'];
	$discv=$_POST['discv'];
	$disca=$_POST['disca'];
	$discmo=$_POST['discmo'];
	$discme=$_POST['discme'];
	$otradisc=$_POST['otradisc'];
	$obsgenerales=$_POST['obsgenerales'];
	$estadoest_madre=$_POST['estadoest_madre'];
	$menor_embarazada=$_POST['menor_embarazada'];
	$clase_doc=$_POST['clase_doc'];	
	$fecha_inscripcion=Fecha_db($fecha_inscripcion);
	echo $semanas_embarazo;
    if($menor_embarazada=='N'){
		$fecha_diagnostico_embarazo='1899-12-30';
		$semanas_embarazo=0;
    	$fecha_probable_parto='1899-12-30';
    	$fecha_efectiva_parto='1899-12-30';	
    	$fum='1899-12-30';
    }
    else $fecha_efectiva_parto='1899-12-30';	
     //Responsable Padre, menor no embarazada o menor de 9 años (Insert)
    if($responsable=='PADRE'){

    	$estadoest_madre='';
    	$tipo_doc_padre=$tipo_doc_madre;
    	$nro_doc_padre=$nro_doc_madre;
    	$apellido_padre=$apellido_madre;
    	$nombre_padre=$nombre_madre;
    	$alfabeta_padre=$alfabeta_madre;
    	$estudios_padre=$estudios_madre;
    	$anio_mayor_nivel_padre=$anio_mayor_nivel_madre;
    	$estadoest_padre=$estadoest_madre;
    	$tipo_doc_madre='';
    	$nro_doc_madre=0;
    	$apellido_madre='';
    	$nombre_madre='';
    	$alfabeta_madre='';
    	$estudios_madre='';
    	$anio_mayor_nivel_madre=0;
    	$tipo_doc_tutor='';
    	$nro_doc_tutor=0;
    	$apellido_tutor='';
    	$nombre_tutor='';
    	$alfabeta_tutor='';
    	$estudios_tutor='';
    	$anio_mayor_nivel_tutor=0;
    	$estadoest_tutor='';
    	
    }elseif ($responsable== 'TUTOR' ){
    	
    	$tipo_doc_tutor=$tipo_doc_madre;
    	$nro_doc_tutor=$nro_doc_madre;
    	$apellido_tutor=$apellido_madre;
    	$nombre_tutor=$nombre_madre;
    	$alfabeta_tutor=$alfabeta_madre;
    	$estudios_tutor=$estudios_madre;
    	$anio_mayor_nivel_tutor=$anio_mayor_nivel_madre;
    	$estadoest_tutor=$estadoest_madre;
    	$tipo_doc_madre='';
    	$nro_doc_madre=0;
    	$apellido_madre='';
    	$nombre_madre='';
    	$alfabeta_madre='';
    	$estudios_madre='';
    	$anio_mayor_nivel_madre=0;
    	$estadoest_madre='';
    	$tipo_doc_padre='';
    	$nro_doc_padre=0;
    	$apellido_padre='';
    	$nombre_padre='';
    	$alfabeta_padre='';
    	$estudios_padre='';
    	$anio_mayor_nivel_padre=0;
    	$estadoest_padre='';

    } elseif ($responsable!= 'MADRE'){
    	 
    	 $tipo_doc_madre='';
    	$nro_doc_madre=0;
    	$apellido_madre='';
    	$nombre_madre='';
    	$alfabeta_madre='';
    	$estudios_madre='';
    	$anio_mayor_nivel_madre=0;
    	$estadoest_madre='';
    	$tipo_doc_padre='';
    	$nro_doc_padre=0;
    	$apellido_padre='';
    	$nombre_padre='';
    	$alfabeta_padre='';
    	$estudios_padre='';
    	$anio_mayor_nivel_padre=0;
    	$estadoest_padre='';
    	$tipo_doc_tutor='';
    	$nro_doc_tutor=0;
    	$apellido_tutor='';
    	$nombre_tutor='';
    	$alfabeta_tutor='';
    	$estudios_tutor='';
    	$anio_mayor_nivel_tutor=0;
    	$estadoest_tutor='';
    }else {
    	$tipo_doc_padre='';
    	$nro_doc_padre=0;
    	$apellido_padre='';
    	$nombre_padre='';
    	$alfabeta_padre='';
    	$estudios_padre='';
    	$anio_mayor_nivel_padre=0;
    	$estadoest_padre='';
    	$tipo_doc_tutor='';
    	$nro_doc_tutor=0;
    	$apellido_tutor='';
    	$nombre_tutor='';
    	$alfabeta_tutor='';
    	$estudios_tutor='';
    	$anio_mayor_nivel_tutor=0;
    	$estadoest_tutor='';
    }
	
	//en caso de no ser embarazada colocamos a todos los campos valores por default
	$query1="SELECT tipo_transaccion,estado_envio
			FROM uad.beneficiarios
			LEFT JOIN nacer.efe_conv ON beneficiarios.cuie_ea=efe_conv.cuie 
			WHERE id_beneficiarios=$id_planilla";

	$result1=sql($query1, "Error en consulta 1") or fin_pagina();
	//Datos de la inscripcion
		    $trans_tip=trim($result1->fields['tipo_transaccion']);
		    $estado_env=trim($result1->fields['estado_envio']);
	
	if ($estado_env=='n' and $trans_tip=='A') {
		$tipo_transaccion='A';		
	} else $tipo_transaccion='M';
	
 	$dias=calculo_dias(fecha($result->fields['$fecha_inscripcion']));
   	$query = "UPDATE uad.beneficiarios SET 
   			estado_envio='n', tipo_transaccion='$tipo_transaccion',
            cuie_ea='$cuie', id_categoria=$id_categoria, tipo_ficha='2', observaciones=upper('$observaciones'), 
            fecha_inscripcion='$fecha_inscripcion',fecha_carga='$fecha_carga', usuario_carga=upper('$usuario'),
            nombre_benef=upper('$nombre'), apellido_benef=upper('$apellido'), numero_doc='$num_doc', fecha_nacimiento_benef='$fecha_nac',
            clase_documento_benef=upper('$clase_doc'), pais_nac=upper('$paisn'),
            alfabeta=upper('$alfabeta'),estudios=upper('$estudios'), anio_mayor_nivel='$anio_mayor_nivel',
            indigena=upper('$indigena'),id_tribu=$id_tribu,id_lengua=$id_lengua,
            calle=upper('$calle'),numero_calle='$numero_calle',piso='$piso',dpto=upper('$dpto'),manzana='$manzana',entre_calle_1=upper('$entre_calle_1'),
            entre_calle_2=upper('$entre_calle_2'), departamento=upper('$departamenton'), localidad=upper('$localidadn'), municipio=upper('$municipion'), 
            barrio=upper('$barrion'),telefono='$telefono',cod_pos='$cod_posn',
            mail=upper('$mail'), celular='$celular',otrotel='$otrotel', estadoest=upper('$estadoest'),
            menor_convive_con_adulto=upper('$menor_convive_con_adulto'),  responsable=upper('$responsable'),
            nombre_madre=upper('$nombre_madre'),anio_mayor_nivel_madre=$anio_mayor_nivel_madre,alfabeta_madre=upper('$alfabeta_madre'), estudios_madre=upper('$estudios_madre'), apellido_madre=upper('$apellido_madre'), nro_doc_madre='$nro_doc_madre', tipo_doc_madre=upper('$tipo_doc_madre'), estadoest_madre=upper('$estadoest_madre'),
            nombre_padre= upper('$nombre_padre'),anio_mayor_nivel_padre=$anio_mayor_nivel_padre,alfabeta_padre=upper('$alfabeta_padre'),estudios_padre=upper('$estudios_padre'), apellido_padre=upper('$apellido_padre'), nro_doc_padre='$nro_doc_padre',tipo_doc_padre=upper('$tipo_doc_padre'), estadoest_padre=upper('$estadoest_padre'),
            nombre_tutor=upper('$nombre_tutor') ,anio_mayor_nivel_tutor=$anio_mayor_nivel_tutor,alfabeta_tutor=upper('$alfabeta_tutor'), estudios_tutor=upper('$estudios_tutor'),apellido_tutor=upper('$apellido_tutor'), nro_doc_tutor='$nro_doc_tutor', tipo_doc_tutor=upper('$tipo_doc_tutor'), estadoest_tutor=upper('$estadoest_tutor'),
            menor_embarazada=upper('$menor_embarazada'),fecha_diagnostico_embarazo='$fecha_diagnostico_embarazo', semanas_embarazo='$semanas_embarazo',fecha_probable_parto='$fecha_probable_parto', 
            score_riesgo='$score_riesgo',fum='$fum',
            discv=upper('$discv'),disca=upper('$disca'),discmo=upper('$discmo'),discme=upper('$discme'),otradisc=upper('$otradisc'), obsgenerales=upper('$obsgenerales')
            WHERE id_beneficiarios=".$id_planilla;
		
  
	sql($query, "Error al Actualizar los datos") or fin_pagina();   
	$db->CompleteTrans();    
   	$accion="SUCCESS-Los datos del beneficiario han sido modificados correctamente.";
    $cambiodom = 'N';		 
} //FIN Update

// Insert de Beneficiarios
if ($_POST['guardar']=="Guardar Planilla"){
		$sql1 = "SELECT * FROM uad.beneficiarios	  
				WHERE numero_doc='$num_doc' and nombre_benef='$nombre' and tipo_documento='A'";
		$res_extra1=sql($sql1, "Error al traer el beneficiario") or fin_pagina();
		
		if ($res_extra1->recordcount()>0){
			$accion="INFO-El beneficiario ya esta registrado.";
			
			$tipo_transaccion='M';
			$id_planilla=$res_extra1->fields['id_beneficiarios'];       
		    $clave_beneficiario=$res_extra1->fields['clave_beneficiario'];
			$apellido=$res_extra1->fields['apellido_benef'];
		 	$nombre=$res_extra1->fields['nombre_benef'];
		 	$tipo_doc=$res_extra1->fields['tipo_documento'];
		 	$clase_doc=$res_extra1->fields['clase_documento_benef'];
		 	$mail=$res_extra1->fields['mail'];
			$celular=$res_extra1->fields['celular'];
			$sexo=$res_extra1->fields['sexo'];
		 	$fecha_nac=Fecha($res_extra1->fields['fecha_nacimiento_benef']);
		 	$pais_nac=$res_extra1->fields['pais_nac'];
		 	$id_categoria=$res_extra1->fields['id_categoria'];
		  	$indigena= $res_extra1->fields['indigena'];
		 	$id_tribu= $res_extra1->fields['id_tribu'];
		 	$id_lengua= $res_extra1->fields['id_lengua'];
		 	$alfabeta=$res_extra1->fields['alfabeta'];
			$estudios=$res_extra1->fields['estudios'];
			$estadoest=$res_extra1->fields['estadoest'];
			$anio_mayor_nivel=$res_extra1->fields['anio_mayor_nivel'];
		 	$calle=$res_extra1->fields['calle'];
		 	$numero_calle=$res_extra1->fields['numero_calle'];
			$piso=$res_extra1->fields['piso'];
			$dpto=$res_extra1->fields['dpto'];
			$manzana=$res_extra1->fields['manzana'];
			$entre_calle_1=$res_extra1->fields['entre_calle_1'];
			$entre_calle_2=$res_extra1->fields['entre_calle_2'];	
			$telefono=$res_extra1->fields['telefono'];
			$otrotel=$res_extra1->fields['otrotel'];
			$departamento=$res_extra1->fields['departamento'];
		   	$localidad=$res_extra1->fields['localidad'];
		   	$municipio=$res_extra1->fields['municipio'];
		   	$barrio=$res_extra1->fields['barrio'];
			$cod_pos=$res_extra1->fields['cod_pos'];
			$observaciones=$res_extra1->fields['observaciones'];
	 		
				if ($responsable=='MADRE'){
		    			$tipo_doc_madre=$res_extra1->fields['tipo_doc_madre'];
	   					$nro_doc_madre=$res_extra1->fields['nro_doc_madre'];
	   					$apellido_madre=$res_extra1->fields['apellido_madre'];
	   					$nombre_madre=$res_extra1->fields['nombre_madre'];
	   					$alfabeta_madre=$res_extra1->fields['alfabeta_madre'];
						$estudios_madre=$res_extra1->fields['estudios_madre'];
	   					$estadoest_madre=$res_extra1->fields['estadoest_madre'];
	   					$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_madre'];
				}elseif ($responsable=='PADRE'){
						$tipo_doc_madre=$res_extra1->fields['tipo_doc_padre'];
	   					$nro_doc_madre=$res_extra1->fields['nro_doc_padre'];
	   					$apellido_madre=$res_extra1->fields['apellido_padre'];
	   					$nombre_madre=$res_extra1->fields['nombre_padre'];
	   					$alfabeta_madre=$res_extra1->fields['alfabeta_padre'];
						$estudios_madre=$res_extra1->fields['estudios_padre'];
	   					$estadoest_madre=$res_extra1->fields['estadoest_padre'];
	   					$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_padre'];	
				}elseif ($responsable=='TUTOR'){
						$tipo_doc_madre=$res_extra1->fields['tipo_doc_tutor'];
			   			$nro_doc_madre=$res_extra1->fields['nro_doc_tutor'];
			   			$apellido_madre=$res_extra1->fields['apellido_tutor'];
			   			$nombre_madre=$res_extra1->fields['nombre_tutor'];	
			   			$alfabeta_madre=$res_extra1->fields['alfabeta_tutor'];
						$estudios_madre=$res_extra1->fields['estudios_tutor'];
			   			$estadoest_madre=$res_extra1->fields['estadoest_tutor'];
			   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_tutor'];	
						}
			
			
			// Menor de 9 años, no muestra la información de embarazo y muestra la información del menor_convive_con_adulto	
			if (($id_categoria=='5') && ($sexo=='F')&& ($menor_embarazada =='N')){
			$embarazada=none;
			$mva1=inline;
			$datos_resp=inline;
			$memb=none;
			$menor_convive_con_adulto=$res_extra1->fields['menor_convive_con_adulto'];
			$responsable=$res_extra1->fields['responsable'];
			}	// Menor de 10 años hasta 18 años, pregunta si la menor esta o no embarazada y la información de menor_convive_con_adulto
	 		if (($id_categoria=='5') && ($sexo=='F') && ($menor_embarazada =='N')){ 
			$embarazada=none;
			$mva1=inline;
			$datos_resp=inline;
			$memb=inline;
	 		$menor_convive_con_adulto=$res_extra1->fields['menor_convive_con_adulto'];
			$responsable=$res_extra1->fields['responsable'];
			$menor_embarazada=$res_extra1->fields['menor_embarazada'];
	 		}
			//Si esta embarazada muestra la información de embarazo.
			if ($menor_embarazada=='S'){
				$embarazada=inline;
				$fum=Fecha($res_extra1->fields['fum']);
				$fecha_diagnostico_emabrazo=Fecha($res_extra1->fields['fecha_diagnostico_embarazo']);
				$semanas_embarazo=$res_extra1->fields['semanas_embarazo'];
				$fecha_probable_parto=Fecha($res_extra1->fields['fecha_probable_parto']);		
			} // Si no esta embarazada no la muestra.
				else{
					$embarazada=none;
				}
		
			// Menor de 18 años, masculino muestra solo la información menor_convive_con_adulto
			if(($id_categoria=='5') && ($sexo=='M')) { 
				$mva1=inline;
				$datos_resp=inline;
				$embarazada=none;
				$memb=none;
				$menor_convive_con_adulto=$res_extra1->fields['menor_convive_con_adulto'];
				$responsable=$res_extra1->fields['responsable'];

			}else// Mayor de 18 años Femenino muesta la información de embarazo.
		 		if (($id_categoria=='6') && ($sexo=='F')){
					$embarazada=inline;
					$datos_resp=none;
					$mva1=none;
					$memb=none;
					$fum=Fecha($res_extra1->fields['fum']);
					$fecha_diagnostico_emabrazo=Fecha($res_extra1->fields['fecha_diagnostico_embarazo']);
					$semanas_embarazo=$res_extra1->fields['semanas_embarazo'];
					$fecha_probable_parto=Fecha($res_extra1->fields['fecha_probable_parto']);		
		 		}else // Mayor de 18 años Masuclino no muestra la información de embarazo.
		 		if ($sexo=='M') {
					$embarazada=none;
					$datos_resp=none;
					$mva1=none;
					$memb=none;
				}//FIN
	
			$discv=$res_extra1->fields['discv'];
			$disca=$res_extra1->fields['disca'];
			$discmo=$res_extra1->fields['discmo'];
			$discme=$res_extra1->fields['discme'];
			$otradisc=$res_extra1->fields['otradisc'];
			$fecha_inscripcion=Fecha($res_extra1->fields['fecha_inscripcion']);
		 	$cuie=$res_extra1->fields['cuie_ea'];
		 	$obsgenerales=$res_extra1->fields['obsgenerales'];
	
		}else { 
//-------------------comienza el insert del nuevo beneficiario--------------------
					
	   $fecha_carga= date("Y-m-d");
	   $usuario=$_ses_user['login'];
	 
	    $fecha_nac=Fecha_db($fecha_nac);
	   	$fum=Fecha_db($fum);
	    $fecha_diagnostico_embarazo=Fecha_db($fecha_diagnostico_embarazo);
	 	$fecha_probable_parto=Fecha_db($fecha_probable_parto);
	   	$fecha_inscripcion=Fecha_db($fecha_inscripcion);
		$fecha_efectiva_parto=Fecha_db($fecha_efectiva_parto);
	   $db->StartTrans();      
	
	   $sql_parametros="select * from uad.parametros ";
	   $result_parametros=sql($sql_parametros) or fin_pagina();
	   $codigo_provincia=$result_parametros->fields['codigo_provincia'];
	   $codigo_ci=$result_parametros->fields['codigo_ci'];   
	   $codigo_uad=$result_parametros->fields['codigo_uad'];   
	    
	  /*$usuario = substr($usuario,0,9);*/
    
	 $responsable=$_POST['responsable'];  
	 $sql="Select puco.documento from puco.puco where puco.documento = '$num_doc'";
	 $res_extra=sql($sql, "Error al traer el beneficiario") or fin_pagina();
	 $estado_envio='n';
    
    if($menor_embarazada=='N'){
		$fecha_diagnostico_embarazo='1899-12-30';
		$semanas_embarazo=0;
    	$fecha_probable_parto='1899-12-30';
    	$fecha_efectiva_parto='1899-12-30';	
    	$fum='1899-12-30';
    }
    else $fecha_efectiva_parto='1899-12-30';	
    
     //Responsable Padre, menor no embarazada o menor de 9 años (Insert)
    if($responsable=='PADRE'){
 
    	$estadoest_madre='';
    	$tipo_doc_padre=$tipo_doc_madre;
    	$nro_doc_padre=$nro_doc_madre;
    	$apellido_padre=$apellido_madre;
    	$nombre_padre=$nombre_madre;
    	$alfabeta_padre=$alfabeta_madre;
    	$estudios_padre=$estudios_madre;
    	$anio_mayor_nivel_padre=$anio_mayor_nivel_madre;
    	$estadoest_padre=$estadoest_madre;
    	$tipo_doc_madre='';
    	$nro_doc_madre=0;
    	$apellido_madre='';
    	$nombre_madre='';
    	$alfabeta_madre='';
    	$estudios_madre='';
    	$anio_mayor_nivel_madre=0;
    	$tipo_doc_tutor='';
    	$nro_doc_tutor=0;
    	$apellido_tutor='';
    	$nombre_tutor='';
    	$alfabeta_tutor='';
    	$estudios_tutor='';
    	$anio_mayor_nivel_tutor=0;
    	$estadoest_tutor='';
    	
    }elseif ($responsable== 'TUTOR' ){
    	
    	$tipo_doc_tutor=$tipo_doc_madre;
    	$nro_doc_tutor=$nro_doc_madre;
    	$apellido_tutor=$apellido_madre;
    	$nombre_tutor=$nombre_madre;
    	$alfabeta_tutor=$alfabeta_madre;
    	$estudios_tutor=$estudios_madre;
    	$anio_mayor_nivel_tutor=$anio_mayor_nivel_madre;
    	$estadoest_tutor=$estadoest_madre;
    	$tipo_doc_madre='';
    	$nro_doc_madre=0;
    	$apellido_madre='';
    	$nombre_madre='';
    	$alfabeta_madre='';
    	$estudios_madre='';
    	$anio_mayor_nivel_madre=0;
    	$estadoest_madre='';
    	$tipo_doc_padre='';
    	$nro_doc_padre=0;
    	$apellido_padre='';
    	$nombre_padre='';
    	$alfabeta_padre='';
    	$estudios_padre='';
    	$anio_mayor_nivel_padre=0;
    	$estadoest_padre='';

       } elseif ($responsable!= 'MADRE'){
 
    	$tipo_doc_madre='';
    	$nro_doc_madre=0;
    	$apellido_madre='';
    	$nombre_madre='';
    	$alfabeta_madre='';
    	$estudios_madre='';
    	$anio_mayor_nivel_madre=0;
    	$estadoest_madre='';
    	$tipo_doc_padre='';
    	$nro_doc_padre=0;
    	$apellido_padre='';
    	$nombre_padre='';
    	$alfabeta_padre='';
    	$estudios_padre='';
    	$anio_mayor_nivel_padre=0;
    	$estadoest_padre='';
    	$tipo_doc_tutor='';
    	$nro_doc_tutor=0;
    	$apellido_tutor='';
    	$nombre_tutor='';
    	$alfabeta_tutor='';
    	$estudios_tutor='';
    	$anio_mayor_nivel_tutor=0;
    	$estadoest_tutor='';
    }else {
 
    	$tipo_doc_padre='';
    	$nro_doc_padre=0;
    	$apellido_padre='';
    	$nombre_padre='';
    	$alfabeta_padre='';
    	$estudios_padre='';
    	$anio_mayor_nivel_padre=0;
    	$estadoest_padre='';
    	$tipo_doc_tutor='';
    	$nro_doc_tutor=0;
    	$apellido_tutor='';
    	$nombre_tutor='';
    	$alfabeta_tutor='';
    	$estudios_tutor='';
    	$anio_mayor_nivel_tutor=0;
    	$estadoest_tutor='';
    }
    //(Insert)
     $q="select nextval('uad.beneficiarios_id_beneficiarios_seq') as id_planilla";
	   $id_planilla=sql($q) or fin_pagina();
	   $id_planilla=$id_planilla->fields['id_planilla'];
	   $id_planilla_clave= str_pad($id_planilla, 6, '0', STR_PAD_LEFT);
    $clave_beneficiario=$codigo_provincia.$codigo_uad.$codigo_ci.$id_planilla_clave;
    echo $semanas_embarazo; //$clave_beneficiario;

 // Chequeamos que el campo Id_categoria No se ni NULL ni Vacio- Seba-Victor
if(($id_categoria === "") or ($id_categoria === NULL)){

	$id_categoria = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($id_categoria)){
		//aca haces lo que quieras si es numerico

	}else{

		$id_categoria = 0 ;
	}
 }
 // Chequeamos que el campo Id_Tribu No sea ni NULL ni Vacio- Seba-Victor
 if(($id_tribu === "") or ($id_tribu === NULL)){

	$id_tribu = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($id_tribu)){
		//aca haces lo que quieras si es numerico

	}else{

		$id_tribu = 0 ;
	}
  }
 // Chequeamos que el campo Id_Lengua No sea ni NULL ni Vacio- Seba-Victor
       
 if(($id_lengua === "") or ($id_lengua === NULL)){

	$id_lengua = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($id_lengua)){
		//aca haces lo que quieras si es numerico

	}else{

		$id_lengua = 0 ;
	}
}
 // Chequeamos que el campo Semana_Embarazo No sea ni NULL ni Vacio- Seba-Victor

if(($semanas_embarazo === "") or ($semanas_embarazo === NULL)){

 	$semanas_embarazo = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($semanas_embarazo)){
		//aca haces lo que quieras si es numerico

	}else{

		$semanas_embarazo = 0 ;
	}
 }
// Chequeamos que el campo Mayor Nivel No sea ni NULL ni Vacio- Seba-Victor
 
 if(($anio_mayor_nivel_madre === "") or ($anio_mayor_nivel_madre === NULL)){

 	$anio_mayor_nivel_madre = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($anio_mayor_nivel_madre)){
		//aca haces lo que quieras si es numerico

	}else{

		$anio_mayor_nivel_madre = 0 ;
	}
}
// Chequeamos que el campo Mayor Nivel Padre No sea ni NULL ni Vacio- Seba-Victor
if(($anio_mayor_nivel_padre === "") or ($anio_mayor_nivel_padre === NULL)){

 	$anio_mayor_nivel_padre = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($anio_mayor_nivel_padre)){
		//aca haces lo que quieras si es numerico

	}else{

		$anio_mayor_nivel_padre = 0 ;
	}
}
// Chequeamos que el campo Mayor Nivel Tutor No sea ni NULL ni Vacio- Seba-Victor
if(($anio_mayor_nivel_tutor === "") or ($anio_mayor_nivel_tutor === NULL)){

    $anio_mayor_nivel_tutor = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($anio_mayor_nivel_tutor)){
		//aca haces lo que quieras si es numerico

	}else{

		$anio_mayor_nivel_tutor = 0 ;
	}

}
// Chequeamos que el campo Año Mayor Nivel Tutor No sea ni NULL ni Vacio- Seba-Victor
if(($anio_mayor_nivel === "") or ($anio_mayor_nivel === NULL)){

    $anio_mayor_nivel = 0 ;

}else{
	//si no es vacio y no es NULL preguntas si es numerico

	if(is_numeric($anio_mayor_nivel)){
		//aca haces lo que quieras si es numerico

	}else{

		$anio_mayor_nivel = 0 ;
	}
}
    //hago el insert para cualquier caso de beneficiario con los datos ya modificados y campos validados anteriormente
	$dias=calculo_dias(fecha($result->fields['$fecha_inscripcion']));
   $query="insert into uad.beneficiarios 
			(id_beneficiarios, estado_envio, clave_beneficiario, tipo_transaccion, apellido_benef, nombre_benef,
			clase_documento_benef, tipo_documento, numero_doc, id_categoria, sexo, fecha_nacimiento_benef,
			provincia_nac, localidad_nac, pais_nac, indigena, id_tribu, id_lengua, alfabeta, estudios, anio_mayor_nivel,
			menor_embarazada, fecha_diagnostico_embarazo, semanas_embarazo, fecha_probable_parto, fecha_efectiva_parto,  fum, score_riesgo,
			cuie_ea, cuie_ah, menor_convive_con_adulto,
			tipo_doc_madre, nro_doc_madre, apellido_madre, nombre_madre, alfabeta_madre, estudios_madre, anio_mayor_nivel_madre, estadoest_madre,
			tipo_doc_padre, nro_doc_padre, apellido_padre, nombre_padre, alfabeta_padre, estudios_padre, anio_mayor_nivel_padre, estadoest_padre,
			tipo_doc_tutor, nro_doc_tutor, apellido_tutor, nombre_tutor, alfabeta_tutor, estudios_tutor, anio_mayor_nivel_tutor, estadoest_tutor,
			calle, numero_calle, piso, dpto, manzana, entre_calle_1, entre_calle_2,
			telefono, departamento, localidad, municipio, barrio, cod_pos, observaciones, fecha_inscripcion, fecha_carga, usuario_carga,
			activo, tipo_ficha, responsable, mail, celular, otrotel, estadoest, discv, disca, discmo, discme, otradisc, obsgenerales)
			values
			('$id_planilla','$estado_envio','$clave_beneficiario',upper('$tipo_transaccion'),upper('$apellido'),upper('$nombre'),
			upper('$clase_doc'), upper('$tipo_doc'),'$num_doc','$id_categoria',upper('$sexo'),'$fecha_nac',
			upper('$provincia_nac'),upper('$localidad_proc'),upper('$paisn'),upper('$indigena'),$id_tribu,$id_lengua,upper('$alfabeta'),upper('$estudios'),'$anio_mayor_nivel',
		 	upper('$menor_embarazada'), '$fecha_diagnostico_embarazo','$semanas_embarazo', '$fecha_probable_parto', '$fecha_efectiva_parto', '$fum', '$score_riesgo',
          	upper('$cuie'),upper('$cuie'),upper('$menor_convive_con_adulto'),
			upper('$tipo_doc_madre'),$nro_doc_madre,upper('$apellido_madre'),upper('$nombre_madre'),upper('$alfabeta_madre'),upper('$estudios_madre'), '$anio_mayor_nivel_madre', '$estadoest_madre',
            upper('$tipo_doc_padre'),$nro_doc_padre,upper('$apellido_padre'),upper('$nombre_padre'),upper('$alfabeta_padre'),upper('$estudios_padre'),'$anio_mayor_nivel_padre','$estadoest_padre',
            upper('$tipo_doc_tutor'), $nro_doc_tutor,upper('$apellido_tutor'),upper('$nombre_tutor'),upper('$alfabeta_tutor'),upper('$estudios_tutor'),'$anio_mayor_nivel_tutor','$estadoest_tutor',
        	upper('$calle'),'$numero_calle','$piso',upper('$dpto'), '$manzana',upper('$entre_calle_1'), upper('$entre_calle_2'),
			'$telefono',upper('$departamenton'),upper('$localidadn'),upper('$municipion'),upper('$barrion'), '$cod_posn',upper('$observaciones'), '$fecha_inscripcion','$fecha_carga',upper('$usuario'),
        	 '1','2', upper('$responsable'),upper('$mail'),'$celular','$otrotel',upper('$estadoest'), upper('$discv'),upper('$disca'),upper('$discmo'), upper('$discme'),upper('$otradisc'),upper('$obsgenerales'))";


			// Busca antes de hacer el insert si el beneficiario esta o no en el PUCO
    		if ($res_extra->recordcount()>0) {
    			sql($query, "Error al insertar la Planilla") or fin_pagina();
    			$accion="INFO-La inscripción ha sido realizada correctamente. El benficiario esta en el PUCO.";       	
    			}
    		if ($res_extra->recordcount()==0){
    			sql($query, "Error al insertar la Planilla") or fin_pagina();
    			$accion="SUCCESS-Los datos se registraron correctamente.";       
	     		$db->CompleteTrans();
    			}

    	}//FIN
    $db->CompleteTrans();
       
}//FIN Insert

// Borrado de Beneficiarios
if ($_POST['borrar']=="Borrar"){
	
	if ($tipo_transaccion == 'B'){
	$query="UPDATE uad.beneficiarios  SET activo='0', tipo_transaccion= 'B', estado_envio='n'  WHERE (id_beneficiarios= $id_planilla)";
	sql($query, "Error al insertar la Planilla") or fin_pagina();
	   
	$accion="SUCCESS-El beneficiario $id_planilla ha sido eliminado correctamente.";
	}
	
} //FIN Borrado Beneficiarios

// Buscar Beneficiarios por DNI
if ($_POST['b']=="b"){
		$sql1="select * from uad.beneficiarios	  
	 	where numero_doc='$num_doc'";
		$res_extra1=sql($sql1, "Error al traer el beneficiario") or fin_pagina();
		if ($res_extra1->recordcount()>0){
			$accion="SUCCESS-El Beneficiario ya esta empadronado.";
		$tipo_transaccion='M';
		$id_planilla=$res_extra1->fields['id_beneficiarios'];       
	    $clave_beneficiario=$res_extra1->fields['clave_beneficiario'];
		$apellido=$res_extra1->fields['apellido_benef'];
	 	$nombre=$res_extra1->fields['nombre_benef'];
	 	$tipo_doc=$res_extra1->fields['tipo_documento'];
	 	$clase_doc=$res_extra1->fields['clase_documento_benef'];
	 	$mail=$res_extra1->fields['mail'];
		$celular=$res_extra1->fields['celular'];
		$sexo=$res_extra1->fields['sexo'];
	 	$fecha_nac=Fecha($res_extra1->fields['fecha_nacimiento_benef']);
	 	$pais_nac=$res_extra1->fields['pais_nac'];
	 	$id_categoria=$res_extra1->fields['id_categoria'];
	  	$indigena= $res_extra1->fields['indigena'];
	 	$id_tribu= $res_extra1->fields['id_tribu'];
	 	$id_lengua= $res_extra1->fields['id_lengua'];
	 	$alfabeta=$res_extra1->fields['alfabeta'];
		$estudios=$res_extra1->fields['estudios'];
		$estadoest=$res_extra1->fields['estadoest'];
		$anio_mayor_nivel=$res_extra1->fields['anio_mayor_nivel'];
	 	$calle=$res_extra1->fields['calle'];
	 	$numero_calle=$res_extra1->fields['numero_calle'];
		$piso=$res_extra1->fields['piso'];
		$dpto=$res_extra1->fields['dpto'];
		$manzana=$res_extra1->fields['manzana'];
		$entre_calle_1=$res_extra1->fields['entre_calle_1'];
		$entre_calle_2=$res_extra1->fields['entre_calle_2'];	
		$telefono=$res_extra1->fields['telefono'];
		$otrotel=$res_extra1->fields['otrotel'];
		$departamento=$res_extra1->fields['departamento'];
	   	$localidad=$res_extra1->fields['localidad'];
	   	$municipio=$res_extra1->fields['municipio'];
	   	$barrio=$res_extra1->fields['barrio'];
		$cod_pos=$res_extra1->fields['cod_pos'];
		$observaciones=$res_extra1->fields['observaciones'];
 		// Menor de 9 años, no muestra la información de embarazo y muestra la información del menor_convive_con_adulto	
		if (($id_categoria=='5') && ($sexo=='F')&& ($menor_embarazada =='N')){
		$embarazada=none;
		$mva1=inline;
		$datos_resp=inline;
		$memb=none;
		$menor_convive_con_adulto=$res_extra1->fields['menor_convive_con_adulto'];
		$responsable=$res_extra1->fields['responsable'];
		if ($responsable=='MADRE'){
	    	$tipo_doc_madre=$res_extra1->fields['tipo_doc_madre'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_madre'];
   			$apellido_madre=$res_extra1->fields['apellido_madre'];
   			$nombre_madre=$res_extra1->fields['nombre_madre'];
   			$alfabeta_madre=$res_extra1->fields['alfabeta_madre'];
			$estudios_madre=$res_extra1->fields['estudios_madre'];
   			$estadoest_madre=$res_extra1->fields['estadoest_madre'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_madre'];
		}elseif ($responsable=='PADRE'){
			$tipo_doc_madre=$res_extra1->fields['tipo_doc_padre'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_padre'];
   			$apellido_madre=$res_extra1->fields['apellido_padre'];
   			$nombre_madre=$res_extra1->fields['nombre_padre'];
   			$alfabeta_madre=$res_extra1->fields['alfabeta_padre'];
			$estudios_madre=$res_extra1->fields['estudios_padre'];
   			$estadoest_madre=$res_extra1->fields['estadoest_padre'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_padre'];	
			}elseif ($responsable=='TUTOR'){
			$tipo_doc_madre=$res_extra1->fields['tipo_doc_tutor'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_tutor'];
   			$apellido_madre=$res_extra1->fields['apellido_tutor'];
   			$nombre_madre=$res_extra1->fields['nombre_tutor'];	
   			$alfabeta_madre=$res_extra1->fields['alfabeta_tutor'];
			$estudios_madre=$res_extra1->fields['estudios_tutor'];
   			$estadoest_madre=$res_extra1->fields['estadoest_tutor'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_tutor'];	
			}
 		} // Menor de 10 años hasta 18 años, pregunta si la menor esta o no embarazada y la información de menor_convive_con_adulto
 		if (($id_categoria=='5') && ($sexo=='F') && ($menor_embarazada =='N')){ 
		$embarazada=none;
		$mva1=inline;
		$datos_resp=inline;
		$memb=inline;
 		$menor_convive_con_adulto=$res_extra1->fields['menor_convive_con_adulto'];
		$responsable=$res_extra1->fields['responsable'];
		$menor_embarazada=$res_extra1->fields['menor_embarazada'];
		if ($responsable=='MADRE'){
	    	$tipo_doc_madre=$res_extra1->fields['tipo_doc_madre'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_madre'];
   			$apellido_madre=$res_extra1->fields['apellido_madre'];
   			$nombre_madre=$res_extra1->fields['nombre_madre'];
   			$alfabeta_madre=$res_extra1->fields['alfabeta_madre'];
			$estudios_madre=$res_extra1->fields['estudios_madre'];
   			$estadoest_madre=$res_extra1->fields['estadoest_madre'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_madre'];
		}elseif ($responsable=='PADRE'){
			$tipo_doc_madre=$res_extra1->fields['tipo_doc_padre'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_padre'];
   			$apellido_madre=$res_extra1->fields['apellido_padre'];
   			$nombre_madre=$res_extra1->fields['nombre_padre'];	
   			$alfabeta_madre=$res_extra1->fields['alfabeta_padre'];
			$estudios_madre=$res_extra1->fields['estudios_padre'];
   			$estadoest_madre=$res_extra1->fields['estadoest_padre'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_padre'];	
			}elseif ($responsable=='TUTOR'){
			$tipo_doc_madre=$res_extra1->fields['tipo_doc_tutor'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_tutor'];
   			$apellido_madre=$res_extra1->fields['apellido_tutor'];
   			$nombre_madre=$res_extra1->fields['nombre_tutor'];	
   			$alfabeta_madre=$res_extra1->fields['alfabeta_tutor'];
			$estudios_madre=$res_extra1->fields['estudios_tutor'];
   			$estadoest_madre=$res_extra1->fields['estadoest_tutor'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_tutor'];	
			}
			//Si esta embarazada muestra la información de embarazo.
			if ($menor_embarazada=='S'){
				$embarazada=inline;
				$fum=Fecha($res_extra1->fields['fum']);
				$fecha_diagnostico_emabrazo=Fecha($res_extra1->fields['fecha_diagnostico_embarazo']);
				$semanas_embarazo=$res_extra1->fields['semanas_embarazo'];
				$fecha_probable_parto=Fecha($res_extra1->fields['fecha_probable_parto']);		
			} // Si no esta embarazada no la muestra.
			else{
				$embarazada=none;
			}
		}// FIN
		// Menor de 18 años, masculino muestra solo la información menor_convive_con_adulto
		if(($id_categoria=='5') && ($sexo=='M')) { 
			$mva1=inline;
			$datos_resp=inline;
			$embarazada=none;
			$memb=none;
			$menor_convive_con_adulto=$res_extra1->fields['menor_convive_con_adulto'];
			$responsable=$res_extra1->fields['responsable'];
				if ($responsable=='MADRE'){
	    			$tipo_doc_madre=$res_extra1->fields['tipo_doc_madre'];
   					$nro_doc_madre=$res_extra1->fields['nro_doc_madre'];
   					$apellido_madre=$res_extra1->fields['apellido_madre'];
   					$nombre_madre=$res_extra1->fields['nombre_madre'];
   					$alfabeta_madre=$res_extra1->fields['alfabeta_madre'];
					$estudios_madre=$res_extra1->fields['estudios_madre'];
   					$estadoest_madre=$res_extra1->fields['estadoest_madre'];
   					$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_madre'];
				}elseif ($responsable=='PADRE'){
					$tipo_doc_madre=$res_extra1->fields['tipo_doc_padre'];
   					$nro_doc_madre=$res_extra1->fields['nro_doc_padre'];
   					$apellido_madre=$res_extra1->fields['apellido_padre'];
   					$nombre_madre=$res_extra1->fields['nombre_padre'];
   					$alfabeta_madre=$res_extra1->fields['alfabeta_padre'];
					$estudios_madre=$res_extra1->fields['estudios_padre'];
   					$estadoest_madre=$res_extra1->fields['estadoest_padre'];
   					$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_padre'];	
				}elseif ($responsable=='TUTOR'){
			$tipo_doc_madre=$res_extra1->fields['tipo_doc_tutor'];
   			$nro_doc_madre=$res_extra1->fields['nro_doc_tutor'];
   			$apellido_madre=$res_extra1->fields['apellido_tutor'];
   			$nombre_madre=$res_extra1->fields['nombre_tutor'];	
   			$alfabeta_madre=$res_extra1->fields['alfabeta_tutor'];
			$estudios_madre=$res_extra1->fields['estudios_tutor'];
   			$estadoest_madre=$res_extra1->fields['estadoest_tutor'];
   			$anio_mayor_nivel_madre=$res_extra1->fields['anio_mayor_nivel_tutor'];	
			}
		}// Mayor de 18 años Femenino muesta la información de embarazo.
 		if (($id_categoria=='6') && ($sexo=='F')){
			$embarazada=inline;
			$datos_resp=none;
			$mva1=none;
			$memb=none;
			$fum=Fecha($res_extra1->fields['fum']);
			$fecha_diagnostico_emabrazo=Fecha($res_extra1->fields['fecha_diagnostico_embarazo']);
			$semanas_embarazo=$res_extra1->fields['semanas_embarazo'];
			$fecha_probable_parto=Fecha($res_extra1->fields['fecha_probable_parto']);		
 		}// Mayor de 18 años Masuclino no muestra la información de embarazo.
 		if (($id_categoria=='6') && ($sexo=='M')) {
			$embarazada=none;
			$datos_resp=none;
			$mva1=none;
			$memb=none;
		}//FIN
	
	$discv=$res_extra1->fields['discv'];
	$disca=$res_extra1->fields['disca'];
	$discmo=$res_extra1->fields['discmo'];
	$discme=$res_extra1->fields['discme'];
	$otradisc=$res_extra1->fields['otradisc'];
	$fecha_inscripcion=Fecha($res_extra1->fields['fecha_inscripcion']);
 	$cuie=$res_extra1->fields['cuie_ea'];
 	$obsgenerales=$res_extra1->fields['obsgenerales'];
	
	}else {
			$accion2="Beneficiario no Encontrado";
		}
}//FIN Busqueda por DNI
if($id_planilla){ ;
	 $queryCategoria="SELECT beneficiarios.*, efe_conv.nombreefector, efe_conv.cuie
			FROM uad.beneficiarios
			left join nacer.efe_conv on beneficiarios.cuie_ea=efe_conv.cuie 
  	where id_beneficiarios=$id_planilla";

	$resultado=sql($queryCategoria, "Error al traer el Comprobantes") or fin_pagina();
	//Datos de la inscripcion
			$clave_beneficiario=trim($resultado->fields['clave_beneficiario']);
		   	$trans=$resultado->fields['tipo_transaccion'];
			$id_categoria=$resultado->fields['id_categoria'];
			$fecha_inscripcion=fecha($resultado->fields['fecha_inscripcion']);
			$observaciones=trim($resultado->fields['observaciones']);
			$cuie=$resultado->fields['cuie'];
	//datos personales del beneficiario
			$estadoest=$resultado->fields['estadoest'];
		  	$edad=$resultado->fields['edades'];   
			$clase_doc=$resultado->fields['clase_documento_benef']; 
			$sexo=$resultado->fields['sexo'];
			$num_doc=trim($resultado->fields['numero_doc']); 
			$apellido= trim($resultado->fields['apellido_benef']);
			$nombre=trim($resultado->fields['nombre_benef']);
			$fecha_nac=fecha($resultado->fields['fecha_nacimiento_benef']);	
			$pais_nac=$resultado->fields['pais_nac'];
			$mail=$resultado->fields['mail'];
		   	$celular=$resultado->fields['celular'];
		   	$otrotel=$resultado->fields['otrotel'];
	//lugar de domicilio
			$calle=$resultado->fields['calle'];
			$numero_calle=$resultado->fields['numero_calle'];
			$piso=$resultado->fields['piso'];
			$dpto=$resultado->fields['dpto'];
			$manzana=$resultado->fields['manzana'];
			$entre_calle_1=$resultado->fields['entre_calle_1'];
			$entre_calle_2=$resultado->fields['entre_calle_2'];
			$telefono=$resultado->fields['telefono'];
			$departamento=$resultado->fields['departamento'];
		   	$localidad=$resultado->fields['localidad'];
		   	$cod_pos=$resultado->fields['cod_pos'];	 
		   	$municipio=$resultado->fields['municipio'];
		   	$barrio=$resultado->fields['barrio'];
	//estudios del beneficiario  	
		   	$alfabeta=$resultado->fields['alfabeta'];
		   	$estudios=$resultado->fields['estudios'];	
		   	$anio_mayor_nivel=$resultado->fields['anio_mayor_nivel'];	
	//datos si es originario
		   	$indigena= $resultado->fields['indigena'];
		   	$id_tribu= $resultado->fields['id_tribu'];
		   	$id_lengua= $resultado->fields['id_lengua'];
	//datos de convivencia	
		   	$menor_convive_con_adulto=$resultado->fields['menor_convive_con_adulto'];
		// toma el responsable del niño en el caso que conviva con alguien.
		
		   	if($menor_convive_con_adulto=='S'){//verifico si convive con alguien y quien es el responsable
			 	$responsable=$resultado->fields['responsable'];	   
				if($responsable == 'PADRE'){
					$tipo_doc_madre=$resultado->fields['tipo_doc_padre'];
				    $nro_doc_madre=$resultado->fields['nro_doc_padre'];
				    $apellido_madre=$resultado->fields['apellido_padre']; 
				    $nombre_madre=$resultado->fields['nombre_padre'];
				    $alfabeta_madre=$resultado->fields['alfabeta_padre'];
				    $estudios_madre=$resultado->fields['estudios_padre'];
				    $anio_mayor_nivel_madre=$resultado->fields['anio_mayor_nivel_padre'];
				    $estadoest_madre=$resultado->fields['estadoest_padre'];
					}
					elseif ($responsable == 'MADRE'){
						$tipo_doc_madre=$resultado->fields['tipo_doc_madre'];
				    	$nro_doc_madre=$resultado->fields['nro_doc_madre'];
				    	$apellido_madre=$resultado->fields['apellido_madre']; 
				    	$nombre_madre=$resultado->fields['nombre_madre'];
				    	$alfabeta_madre=$resultado->fields['alfabeta_madre'];
				    	$estudios_madre=$resultado->fields['estudios_madre'];
				    	$anio_mayor_nivel_madre=$resultado->fields['anio_mayor_nivel_madre'];
				    	$estadoest_madre=$resultado->fields['estadoest_madre'];
					}elseif ($responsable == 'TUTOR'){
						$tipo_doc_madre=$resultado->fields['tipo_doc_tutor'];
				    	$nro_doc_madre=$resultado->fields['nro_doc_tutor'];
				    	$apellido_madre=$resultado->fields['apellido_tutor']; 
				    	$nombre_madre=$resultado->fields['nombre_tutor'];
				    	$alfabeta_madre=$resultado->fields['alfabeta_tutor'];
				    	$estudios_madre=$resultado->fields['estudios_tutor'];
				    	$anio_mayor_nivel_madre=$resultado->fields['anio_mayor_nivel_tutor'];
				    	$estadoest_madre=$resultado->fields['estadoest_tutor'];
							} 	
	   			}//fin de if($menor_convive_con_adulto=='S'){ 
		   	$menor_embarazada=$resultado->fields['menor_embarazada'];
			if ($menor_embarazada=='S'){
			
				$fum=fecha($resultado->fields['fum']);
				$fecha_diagnostico_embarazo=fecha($resultado->fields['fecha_diagnostico_embarazo']);
				$semanas_embarazo=$resultado->fields['semanas_embarazo'];
				$fecha_probable_parto=fecha($resultado->fields['fecha_probable_parto']);
				$score_riesgo=$resultado->fields['score_riesgo'];
			}
		   	$discv=$resultado->fields['discv'];
		   	$disca=$resultado->fields['disca'];
		   	$discmo=$resultado->fields['discmo'];
		   	$discme=$resultado->fields['discme'];
		   	$otradisc=$resultado->fields['otradisc'];
		   	$obsgenerales=trim($resultado->fields['obsgenerales']);	   			
   	// Marca Borrado al beneficiario.
		   	if ($trans == 'B'){
	   		$trans="Borrado";
	   	}
}


// INICIO Formulario Inicial, no se muestra la información de embarazo, o menor vive con adulto.
if(($id_categoria=='') && ($edad == '')){

	$embarazada= none; 
	$datos_resp= none;
	$mva1= none;
	$memb= none;
	$menor_embarazada=none;
} elseif (($id_categoria == '6')&& ($sexo=='F')){ //Femenino mayor de 19 años, pregunta si esta o no embarazada para mostrar la información de embarazo.
			
			$embarazada=none;
			$datos_resp=none;
			$mva1=none;
			$memb=inline;
			
			if ($menor_embarazada =='S'){
				
				$embarazada=inline;
				$semanas_embarazo=$_POST['semanas_embarazo'];
			}else {
				
				$embarazada=none;	
			}
			
		} elseif (($id_categoria == '5')&& ($sexo=='F')){
				
					$embarazada=none;
					$datos_resp=inline;
					$mva1=inline;
					$memb=inline;
					if ($menor_embarazada =='S'){
					
						$embarazada=inline;
						$semanas_embarazo=$_POST['semanas_embarazo'];
					}else {
						
							$embarazada=none;	
						}
				}elseif (($id_categoria=='5') && ($sexo=='M')) { // Masculino menor de 19 años, muestra la información de menor vive con adulto y no la de embarazo
						
						$mva1=inline;
						$datos_resp=inline;
						$embarazada=none;
						$memb=none;
							
					} elseif (($id_categoria=='6') && ($sexo=='M')) {// Masculino mayor de 19 años, no muesta la información de embarazo ni tampoco la de menor vive con adulto.
						
						$embarazada=none;
						$datos_resp=none;
						$mva1=none;
						$memb=none;
						} // FIN

	// Muestra Cambio de Domicilio al momento de hacer una modificacion solamente.
if ($tipo_transaccion != 'M'){
	$cdomi1=none;
} // FIN

// Query que muestra la informacion guardada del Beneficiario del Pais de Nacimiento
if (($id_planilla != '') && ($cambiodom != 'S')){
	$strConsulta = "select pais_nac from uad.beneficiarios where id_beneficiarios = $id_planilla ";
	$result = @pg_exec($strConsulta); 
	$fila= pg_fetch_array($result);
	$pais_nac.='<option value="'.$fila["pais_nac"].'">'.$fila["pais_nac"].'</option>';
	$paisn=$fila["pais_nac"];
	}// FIN	
	elseif (($id_planilla == '') || ($cambiodom == 'S')) { // Query para traer los paises para luego ser utilizado con AJAX para que no refresque la pagina.
	$strConsulta = "select id_pais, nombre from uad.pais order by nombre";
	$result = @pg_exec($strConsulta); 
	$pais_nac = '<option value="-1"> Seleccione Pais </option>';
		
	while( $fila = pg_fetch_array($result) )
	{
		
		$pais_nac.='<option value="'.$fila["id_pais"].'">'.$fila["nombre"].'</option>';
		
	} // FIN WHILE	
	
} // FIN ELSEIF

// Query que muestra la informacion guardada del Beneficiario del Departamento donde vive
if (($id_planilla != '') && ($cambiodom != 'S')){
	$strConsulta = "select departamento from uad.beneficiarios where id_beneficiarios = $id_planilla";
	$result = @pg_exec($strConsulta); 
	$fila= pg_fetch_array($result);
	$departamento.='<option value="'.$fila["departamento"].'">'.$fila["departamento"].'</option>';
	$departamenton=$fila["departamento"];
	}// FIN	
	elseif (($id_planilla == '') || ($cambiodom ==  'S')){// Query para traer los departamentos para luego ser utilizado con AJAX para que no refresque la pagina.
 	$strConsulta = "select id_departamento, nombre from uad.departamentos order by nombre";
	$result = @pg_exec($strConsulta); 
	$departamento = '<option value="-1"> Seleccione Departamento </option>';
	$opciones2 = '<option value="-1"> Seleccione Localidad </option>';
	$opciones3 = '<option value="-1"> Seleccione Municipio </option>';
	$opciones4 = '<option value="-1"> Seleccione Barrio </option>';
	$opciones5 = '<option value="-1"> Codigo Postal  </option>';	
	while( $fila = pg_fetch_array($result) )
	{
		
		$departamento.='<option value="'.$fila["id_departamento"].'">'.$fila["nombre"].'</option>';
		
	} // FIN WHILE
} //FIN ELSEIF

// Query que muestra la informacion guardada del Beneficiario de la Localidad donde vive
if (($id_planilla != '') && ($cambiodom != 'S')){
	$strConsulta = "select localidad from uad.beneficiarios where id_beneficiarios = $id_planilla";
	$result = @pg_exec($strConsulta); 
	$fila= pg_fetch_array($result);
	$opciones2.='<option value="'.$fila["localidad"].'">'.$fila["localidad"].'</option>';
	$localidadn=$fila["localidad"];
}// FIN

// Query que muestra la informacion guardada del Beneficiario del Municipio donde vive
if (($id_planilla != '') && ($cambiodom != 'S')){
	$strConsulta = "select cod_pos from uad.beneficiarios where id_beneficiarios = $id_planilla";
	$result = @pg_exec($strConsulta); 
	$fila= pg_fetch_array($result);
	$opciones5.='<option value="'.$fila["cod_pos"].'">'.$fila["cod_pos"].'</option>';
	$cod_posn=$fila["cod_pos"];
}// FIN

// Query que muestra la informacion guardada del Beneficiario del Municipio donde vive
if (($id_planilla != '') && ($cambiodom != 'S')){
	$strConsulta = "select municipio from uad.beneficiarios where id_beneficiarios = $id_planilla";
	$result = @pg_exec($strConsulta); 
	$fila= pg_fetch_array($result);
	$opciones3.='<option value="'.$fila["municipio"].'">'.$fila["municipio"].'</option>';
	$municipion=$fila["municipio"];
}// FIN

// Query que muestra la informacion guardada del Beneficiario del Barrio donde vive
if (($id_planilla != '') && ($cambiodom != 'S')){
	$strConsulta = "select barrio from uad.beneficiarios where id_beneficiarios = $id_planilla";
	$result = @pg_exec($strConsulta); 
	$fila= pg_fetch_array($result);
	$opciones4.='<option value="'.$fila["barrio"].'">'.$fila["barrio"].'</option>';
	$barrion=$fila["barrio"];
}// FIN

$directorio_base=trim(substr(ROOT_DIR, strrpos(ROOT_DIR,chr(92))+1, strlen(ROOT_DIR)));

?>
<?php // MAIN HTML HEADER (config.php)

echo $html_header;

?>

<script>

	// Script para el manejo de combobox de Departamento - Localidad - Codigo Postal - Municipio y Barrio
	$(document).ready(function () {
		$("#departamento").change(function () {
			$.ajax({
				url: "procesa.php",
				type: "POST",
				data: "id_departamento=" + $("#departamento").val(),
				success: function (opciones) {
					$("#localidad").html(opciones);
				}
			})
		});

		$("#localidad").change(function () {
			$.ajax({
				url: "procesa.php",
				type: "POST",
				data: "id_localidad=" + $("#localidad").val(),
				success: function (opciones) {
					$("#cod_pos").html(opciones);
				}
			})
		});

		$("#cod_pos").change(function () {
			$.ajax({
				url: "procesa.php",
				type: "POST",
				data: "id_codpos=" + $("#cod_pos").val(),
				success: function (opciones) {
					$("#municipio").html(opciones);
				}
			})
		});

		$("#municipio").change(function () {
			$.ajax({
				url: "procesa.php",
				type: "POST",
				data: "id_municipio=" + $("#municipio").val(),
				success: function (opciones) {
					$("#barrio").html(opciones);
				}
			})
		});
	});
	
	//Busqueda de beneficiarios
	$(function () {
	
		$("#seleccionar-beneficiario").click(function () {
			bid = $('input:radio[name=benef-radio]:checked').val();
			if(bid != null) {
				$.post("../../services/codificar_link.php", {
					bid: bid
				},
				function (data) {
					window.location.replace(data);
				});
			}
		});

		
		//Definicion de los dialog
		
		$("#search_button").click(function () {
			recargar_beneficiario("click");
		});
	});
	// FIN
	
	function recargar_beneficiario(c) {
		var doc = document.all.num_doc.value;
		$("#beneficiarios_list").empty();
		$.ajax({
			type: 'GET',
			url: '../../services/beneficiarios.php',
			data: "dni=" + doc,
			success: function (data) {
				if(data) {
					var rowString = "";
					var claseDocStyle = "";
					
					$.each(data, function (index, itemData) {
						if (itemData.clasedoc=="P") {
							claseDocStyle = "badge badge-info";
						} else {
							claseDocStyle = "badge";
						}
						
						rowString = "";
						rowString += "<tr>";
						rowString += "	<td><label class='radio'><input type='radio' name='benef-radio' value=" + itemData.id_beneficiarios + "></input></label></td>";
						rowString += "	<td>" + itemData.apynom + "</td>";
						rowString += "	<td>" + itemData.dni + "</td>";
						rowString += "	<td><span class='" + claseDocStyle + "'>" + itemData.clasedoc + "</span></td>";
						rowString += "	<td>" + itemData.fechanac + "</td>";
						rowString += "</tr>";
						
						$("#beneficiarios_list").append(rowString);
					});
				$("#beneficiarios_result").modal('show');	
				} else {
						$("#beneficiarios_list").append($("<tr></tr>").html("<td>No se encontraron beneficiarios. Por favor, realice la inscripción correspondiente.</td>"));
						if (c == "click") {
							$("#beneficiarios_result").modal('show');
						}
				}
			},
			dataType: "json",
			error: function (xhRequest, ErrorText, thrownError) {
				$("#beneficiarios_list").append($("<tr></tr>").html("<td>Error: " + ErrorText + "</td>"));
				$("#beneficiarios_result").modal('show');
			}
		});
	}
		
	
	
	//Guarda el nombre del Pais
	function showpais_nac() {
		var pais_nac = document.getElementById('pais_nac')[document.getElementById('pais_nac').selectedIndex].innerHTML;
		document.all.paisn.value = pais_nac;
	} // FIN
	// Guarda el nombre del Departamento
	function showdepartamento() {
		var departamento = document.getElementById('departamento')[document.getElementById('departamento').selectedIndex].innerHTML;
		document.all.departamenton.value = departamento;
	} // FIN
	//Guarda el nombre del Localidad
	function showlocalidad() {
		var localidad = document.getElementById('localidad')[document.getElementById('localidad').selectedIndex].innerHTML;
		document.all.localidadn.value = localidad;
	} // FIN
	// Guarda el Codigo Postal
	function showcodpos() {
		var cod_pos = document.getElementById('cod_pos')[document.getElementById('cod_pos').selectedIndex].innerHTML;
		document.all.cod_posn.value = cod_pos;
	} // FIN
	//Guarda el nombre del Municipio
	function showmunicipio() {
		var municipio = document.getElementById('municipio')[document.getElementById('municipio').selectedIndex].innerHTML;
		document.all.municipion.value = municipio;
	} // FIN
	//Guarda el nombre del Barrio
	function showbarrio() {
		var barrio = document.getElementById('barrio')[document.getElementById('barrio').selectedIndex].innerHTML;
		document.all.barrion.value = barrio;
	} // FIN
	//Validar Fechas
	function esFechaValida(fecha) {
		if(fecha != undefined && fecha.value != "") {
			if(!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value)) {
				alert("formato de fecha no válido (dd/mm/aaaa)");
				return false;
			}
			var dia = parseInt(fecha.value.substring(0, 2), 10);
			var mes = parseInt(fecha.value.substring(3, 5), 10);
			var anio = parseInt(fecha.value.substring(6), 10);
			switch(mes) {
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				numDias = 31;
				break;
			case 4:
			case 6:
			case 9:
			case 11:
				numDias = 30;
				break;
			case 2:
				if(comprobarSiBisisesto(anio)) {
					numDias = 29
				} else {
					numDias = 28
				};
				break;
			default:
				alert("Fecha introducida errónea");
				return false;
			}
			if(dia > numDias || dia == 0) {
				alert("Fecha introducida errónea");
				return false;
			}
			return true;
		}
	}

	function comprobarSiBisisesto(anio) {
		if((anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0))) {
			return true;
		} else {
			return false;
		}
	}
	//Funcion para verificar si una fecha es mayor a la fecha actual
	function esFechaFutura(fecha) {
		var dia = parseInt(fecha.value.substring(0, 2), 10);
		var mes = parseInt(fecha.value.substring(3, 5), 10);
		var anio = parseInt(fecha.value.substring(6), 10);
		var x = new Date();
		x.setFullYear(anio, mes - 1, dia);
		hoy = document.all.hidden_fecha_servidor.value;
		var array_hoy = hoy.split("/")
		var anoh = parseInt(array_hoy[2], 10);
		var mesh = parseInt(array_hoy[1], 10);
		var diah = parseInt(array_hoy[0], 10);
		var today = new Date();
		today.setFullYear(anoh, mesh - 1, diah);
		if(x > today) {
			return false;
		} else {
			return true;
		}
	}
	//controlan que ingresen todos los datos necesarios par el muleto
	function control_nuevos() {
		if(document.all.num_doc.value == "") {
			alert("Debe completar el campo numero de documento");
			document.all.num_doc.focus();
			return false;
		} else {
			var num_doc = document.all.num_doc.value;
			if(isNaN(num_doc)) {
				alert('El dato ingresado en numero de documento debe ser entero');
				document.all.num_doc.focus();
				return false;
			}
		}
		if(document.all.apellido.value == "") {
			alert("Debe completar el campo apellido");
			document.all.apellido.focus();
			return false;
		} else {
			var charpos = document.all.apellido.value.search("[^A-Za-z/ \s/ Ñ ñ]");
			if(charpos >= 0) {
				alert("El campo Apellido solo permite letras ");
				document.all.apellido.focus();
				return false;
			}
		}
		if(document.all.nombre.value == "") {
			alert("Debe completar el campo nombre");
			document.all.nombre.focus();
			return false;
		} else {
			var charpos = document.all.nombre.value.search("[^A-Za-z/ \s/ Ñ ñ]");
			if(charpos >= 0) {
				alert("El campo Nombre solo permite letras ");
				document.all.nombre.focus();
				return false;
			}
		}
		if(document.all.sexo.value == -1) {
			alert("Debe completar el campo sexo");
			document.all.sexo.focus();
			return false;
		}
		if(document.all.pais_nac.value == -1) {
			alert("Debe completar el campo pais");
			document.all.pais_nac.focus();
			return false;
		}
		if(document.all.calle.value == "") {
			alert("Debe completar el campo calle");
			document.all.calle.focus();
			return false;
		}
		if(document.all.numero_calle.value == "") {
			alert("Debe completar el campo numero calle");
			document.all.numero_calle.focus();
			return false;
		}
		if(document.all.departamento.value == -1) {
			alert("Debe completar el campo departamento");
			document.all.departamento.focus();
			return false;
		}
		if(document.all.localidadn.value == -1) {
			alert("Debe completar el campo Localidad");
			document.all.localidadn.focus();
			return false;
		}
		if(document.all.cod_posn.value == "") {
			alert("Debe completar el campo Codigo Postal");
			document.all.cod_posn.focus();
			return false;
		}
		if(document.all.municipion.value == "") {
			alert("Debe completar el campo Municipio");
			document.all.municipion.focus();
			return false;
		}
		//Control de Categorias
		// Validación Menores de 10 años
		if(ed <= 9) {
			if(document.all.responsable.value == -1) {
				alert("Debe completar el campo Datos del responsable");
				document.all.responsable.focus();
				return false;
			}
			if(document.all.tipo_doc_madre.value == -1) {
				alert("Debe completar el campo tipo de documento del responsable");
				document.all.apellido_madre.focus();
				return false;
			}
			if(document.all.nro_doc_madre.value == "") {
				alert("Debe completar el campo numero de documento del responsable");
				return false;
			} else {
				var num_doc_madre = document.all.nro_doc_madre.value;
				if(isNaN(num_doc_madre)) {
					alert('El dato ingresado en numero de documento del responsable debe ser entero');
					document.all.num_doc_madre.focus();
					return false;
				}
			}
			if(document.all.apellido_madre.value == "") {
				alert("Debe completar el campo apellido del responsable");
				document.all.apellido_madre.focus();
				return false;
			} else {
				var charpos = document.all.apellido_madre.value.search("[^A-Za-z/ \s/ Ñ ñ]");
				if(charpos >= 0) {
					alert("El campo apellido del responsable solo permite letras ");
					document.all.apellido_madre.focus();
					return false;
				}
			}
			if(document.all.nombre_madre.value == "") {
				alert("Debe completar el campo nombre del responsable");
				document.all.nombre_madre.focus();
				return false;
			} else {
				var charpos = document.all.nombre_madre.value.search("[^A-Za-z/ \s/ Ñ ñ]");
				if(charpos >= 0) {
					alert("El campo Nombre del responsable solo permite letras ");
					document.all.nombre_madre.focus();
					return false;
				}
			}
			if(document.all.clase_doc.value == 'P') {
				var num1 = document.all.nro_doc_madre.value;
				var num2 = document.all.num_doc.value;
				if(num1 == num2) {
					alert("Los numero de documento no pueden ser iguales");
					document.all.num_doc.focus();
					return false;
				}
			}
		} // FIN Menores de 10 años
		// Documento Ajeno y Menor de 1 año de Edad.
		if(document.all.clase_doc.value == 'A') {
			if(ed < 1) {
				var num1 = document.all.nro_doc_madre.value;
				var num2 = document.all.num_doc.value;
				if(num1 != num2) {
					alert("Los numeros de documento deben coincidir");
					document.all.num_doc.focus();
					return false;
				}
			} else {
				alert("No se puede inscribir un niño mayor de un año con DNI ajeno");
				document.all.num_doc.focus();
				return false;
			}
		} // FIN
		// Fecha de Inscripcion mayor a la fecha de creacion del plan
		if(document.all.fecha_inscripcion.value <= '01/08/2004') {
			alert("La fecha de inscripcion debe ser mayor a 01/08/2004");
			document.all.fecha_inscripcion.focus();
			return false;
		} // FIN
		//Mujer Embarazada
		if(document.all.fecha_diagnostico_embarazo.value == "") {
			alert("Debe completar el campo fecha de diagnostico de embarazo");
			return false;
		}
		if(document.all.fecha_probable_parto.value == "") {
			alert("Debe completar el campo fecha probable de parto");
			return false;
		}
		//Control de la fecha de inscripción	
		if((esFechaValida(document.all.fecha_inscripcion)) == false) {
			alert("La fecha de inscripcion no tiene el formato correcto");
			document.all.fecha_inscripcion.focus();
			return false;
		}
		if((esFechaFutura(document.all.fecha_inscripcion)) == false) {
			alert("La fecha de inscripción no puede ser mayor que la actual");
			document.all.fecha_inscripcion.focus();
			return false;
		}
		//Control de las fecha de nacimiento	
		if((esFechaValida(document.all.fecha_nac)) == false) {
			alert("La fecha de nacimiento no tiene el formato correcto");
			document.all.fecha_nac.focus();
			return false;
		}
		if((esFechaFutura(document.all.fecha_nac)) == false) {
			alert("La fecha de nacimiento no puede ser mayor que la actual");
			document.all.fecha_nac.focus();
			return false;
		}
		//Control de efector
		if(document.all.cuie.value == -1) {
			alert('Debe Seleccionar un Efector');
			document.all.cuie.focus();
			return false;
		}
	} //de function control_nuevos()
	// Funcion para verificar que el DNI no tenga espacios en blanco y alertar si no tiene 8 caracteres
	function CheckDNI(ele) {
		if(/\s/.test(ele.value)) {
			alert("No se permiten espacios en blanco");
			document.all.num_doc.focus();
		}
		if(!(ele.value.length == 8)) {
			alert('El dato ingresado en numero de documento deberia contener 8 caracteres');
		} else {
			recargar_beneficiario("tab");
		}
		
	}

	function editar_campos() {
		inputs = document.form1.getElementsByTagName('input'); //Arma un arreglo con todos los campos tipo INPUT
		for(i = 0; i < inputs.length; i++) {
			inputs[i].readOnly = false;
		}
		document.all.cancelar_editar.disabled = false;
		document.all.guardar_editar.disabled = false;
		document.all.editar.disabled = true;
		return true;
	} //de function control_nuevos()
	/**********************************************************/
	//funciones para busqueda abreviada utilizando teclas en la lista que muestra los clientes.
	var digitos = 10; //cantidad de digitos buscados
	var puntero = 0;
	var buffer = new Array(digitos); //declaración del array Buffer
	var cadena = "";

	function buscar_combo(obj) {
		var letra = String.fromCharCode(event.keyCode)
		if(puntero >= digitos) {
			cadena = "";
			puntero = 0;
		}
		//sino busco la cadena tipeada dentro del combo...
		else {
			buffer[puntero] = letra;
			//guardo en la posicion puntero la letra tipeada
			cadena = cadena + buffer[puntero]; //armo una cadena con los datos que van ingresando al array
			puntero++;
			//barro todas las opciones que contiene el combo y las comparo la cadena...
			//en el indice cero la opcion no es valida
			for(var opcombo = 1; opcombo < obj.length; opcombo++) {
				if(obj[opcombo].text.substr(0, puntero).toLowerCase() == cadena.toLowerCase()) {
					obj.selectedIndex = opcombo;
					break;
				}
			}
		} //del else de if (event.keyCode == 13)
		event.returnValue = false; //invalida la acción de pulsado de tecla para evitar busqueda del primer caracter
	} //de function buscar_op_submit(obj)
	// Función para mostra la información de embarazo y de ser menor la información del adulto.
	function cambiar_patalla() {
		// Masculino - Menor de 10 años edad no muestra la información de embarazo, muestra la información de menor vive con adulto 
		//y pide la información del adulto aunque el menor no viva con el. 
		if((document.all.sexo.value == 'M') && (document.all.id_categoria.value == '5')) {
			document.all.cat_emb.style.display = 'none';
			document.all.cat_nino.style.display = 'inline';
			document.all.mva.style.display = 'inline';
			document.all.memb.style.display = 'none';
		} //fin
		// Masculino - Mayor de edad 10 años no muestra la información de embarazo, no muestra la información de menor vive con adulto. 
		if((document.all.sexo.value == 'M') && (document.all.id_categoria.value == '6')) {
			document.all.cat_emb.style.display = 'none';
			document.all.cat_nino.style.display = 'none';
			document.all.mva.style.display = 'none';
			document.all.memb.style.display = 'none';
		} //fin
		// Femenino - Menor de 9 años no muestra la información de embarazo, muestra la información de menor vive con adulto 
		//y pide la información del adulto aunque el menor no viva con el. 
		if((document.all.sexo.value == 'F') && (document.all.edades.value <= 9)) {
			document.all.cat_emb.style.display = 'none';
			document.all.cat_nino.style.display = 'inline';
			document.all.mva.style.display = 'inline';
			document.all.memb.style.display = 'none';
		}
		// Femenino - Mayor de 10 años puede o no estar embarazada, muestra la información de menor vive con adulto
		// pide la información del adulto aunque el menor no viva con el y pregunta si esta o no embarazada.
		if((document.all.sexo.value == 'F') && (document.all.edades.value >= 10)) {
			document.all.cat_emb.style.display = 'none';
			document.all.cat_nino.style.display = 'inline';
			document.all.mva.style.display = 'inline';
			document.all.memb.style.display = 'inline';
			// Si esta embarazada muestra la información del embarazo
			if(document.all.menor_embarazada.value == 'S') {
				document.all.cat_emb.style.display = 'inline';
			} //fin
			//Si no esta embarazada muestra el combo solamente.
			if(document.all.menor_embarazada.value == 'N') {
				document.all.memb.style.display = 'inline';
			}
		} //fin
		// Femenino - Mayor de 10 años puede o no estar embarazada,pregunta si esta o no 
		// embarazada para pedir la información de embarazo, no muestra la información de menor vive con adulto. 
		if((document.all.sexo.value == 'F') && (document.all.id_categoria.value == '6')) {
			document.all.cat_emb.style.display = 'none';
			document.all.cat_nino.style.display = 'none';
			document.all.mva.style.display = 'none';
			document.all.memb.style.display = 'inline';
			// Si esta embarazada muestra la información del embarazo
			if(document.all.menor_embarazada.value == 'S') {
				document.all.cat_emb.style.display = 'inline';
			} //fin
			//Si no esta embarazada muestra el combo solamente.
			if(document.all.menor_embarazada.value == 'N') {
				document.all.memb.style.display = 'inline';
			}
		} //fin
	} // FIN Cambiar_Patalla()
	// Calculo de días para fecha de Nacimiento Mayor a Fecha Actual
	function fechaNacAct() {
		var d1 = $('#fecha_nac').val().split("/");
		var dat1 = new Date(d1[2], parseFloat(d1[1]) - 1, parseFloat(d1[0]));
		var d2 = $('#fecha_inscripcion').val().split("/");
		var dat2 = new Date(d2[2], parseFloat(d2[1]) - 1, parseFloat(d2[0]));
		var fin = dat2.getTime() - dat1.getTime();
		var dias = Math.floor(fin / (1000 * 60 * 60 * 24))
		return dias;
	} // FIN
	function verificaFPP() {
		var d1 = $('#fecha_probable_parto').val().split("/");
		var dat1 = new Date(d1[2], parseFloat(d1[1]) - 1, parseFloat(d1[0]));
		var d2 = $('#fecha_inscripcion').val().split("/");
		var dat2 = new Date(d2[2], parseFloat(d2[1]) - 1, parseFloat(d2[0]));
		var fin = dat2.getTime() - dat1.getTime();
		var dias = Math.floor(fin / (1000 * 60 * 60 * 24))
		return dias;
	} // FIN
	// Valida que la Fecha Probable de Parto no supere los 45 días después del Parto
	function mostrarDias() {
		if(verificaFPP() >= '46') {
			alert("No se puede Inscribir porque supero los 45 días después del Parto");
			document.all.fecha_probable_parto.focus();
			return false;
		}
	} // FIN
	// Fecha Diagnostico de Embarazo no puede ser superior a la Fecha de Inscripción
	function validaFDE() {
		var d1 = $('#fecha_diagnostico_embarazo').val().split("/");
		var dat1 = new Date(d1[2], parseFloat(d1[1]) - 1, parseFloat(d1[0]));
		var d2 = $('#fecha_inscripcion').val().split("/");
		var dat2 = new Date(d2[2], parseFloat(d2[1]) - 1, parseFloat(d2[0]));
		var fin = dat2.getTime() - dat1.getTime();
		var dias = Math.floor(fin / (1000 * 60 * 60 * 24))
		return dias;
	} // FIN
	//calcular la edad de una persona 
	//recibe la fecha como un string en formato español 
	//devuelve un entero con la edad. Devuelve false en caso de que la fecha sea incorrecta o mayor que el dia actual 
	function calcular_edad(fecha) {
		//calculo la fecha de hoy 
		//La descompongo en un array 
		hoy = document.all.hidden_fecha_servidor.value;
		var array_hoy = hoy.split("/")
		//compruebo que los ano, mes, dia son correctos 
		var anohoy
		anohoy = parseInt(array_hoy[2], 10);
		var meshoy
		meshoy = parseInt(array_hoy[1], 10);
		var diahoy
		diahoy = parseInt(array_hoy[0], 10);
		//calculo la fecha que recibo 
		//La descompongo en un array 
		var array_fecha = fecha.split("/")
		//si el array no tiene tres partes, la fecha es incorrecta 
		if(array_fecha.length != 3) return false
		//compruebo que los ano, mes, dia son correctos 
		var ano
		ano = parseInt(array_fecha[2]);
		if(isNaN(ano)) return false
		var mes
		mes = parseInt(array_fecha[1], 10);
		if(isNaN(mes)) return false
		var dia
		dia = parseInt(array_fecha[0], 10);
		if(isNaN(dia)) return false
		//si el año de la fecha que recibo solo tiene 2 cifras hay que cambiarlo a 4 
		if(ano <= 99) {
			ano += 1900;
		}
		//resto los años de las dos fechas 
		anos = anohoy - ano;
		//si resto los meses y me da menor que 0 entonces no ha cumplido años. Si da mayor si ha cumplido 
		if(meshoy < mes) {
			anos = anos - 1;
		}
		//entonces es que eran iguales. miro los dias 
		//si resto los dias y me da menor que 0 entonces no ha cumplido años. Si da mayor o igual si ha cumplido 
		if((meshoy = mes) && (diahoy < dia)) {
			anos = anos - 1;
		}
		return anos;
	}
	// calcula la edad y da el valor de la categoria
	function edad(Fecha) {
		ed = calcular_edad(Fecha);
		if(fechaNacAct() <= '-1') {
			alert("La Fecha de Nacimiento no puede ser mayor al día de hoy");
			document.all.fecha_nac.focus();
			return false;
		}
		//si es mayor de 10 años categoria 6
		if(ed >= 10) {
			document.getElementById('id_categoria').value = '6';
			document.getElementById("edades").value = ed;
			if(document.all.clase_doc.value == 'A') {
				alert("Mayor de 1 año, el documento debe ser Propio")
				document.all.clase_doc.focus();
			}
		}
		//si es menor de 10 años categoria 5
		if(ed <= 9) {
			document.getElementById('id_categoria').value = '5';
			document.getElementById("edades").value = ed;
			if((ed >= 1) && (document.all.clase_doc.value == 'A')) {
				alert("Niño mayor de 1 año, el documento debe ser Propio")
				document.all.clase_doc.focus();
			}
		}
	} //FIN calculo de edad y categoría
	//Desarma la fecha para calcular la FPP
	var aFinMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	function finMes(nMes, nAno) {
		return aFinMes[nMes - 1] + (((nMes == 2) && (nAno % 4) == 0) ? 1 : 0);
	}

	function padNmb(nStr, nLen, sChr) {
		var sRes = String(nStr);
		for(var i = 0; i < nLen - String(nStr).length; i++)
		sRes = sChr + sRes;
		return sRes;
	}

	function makeDateFormat(nDay, nMonth, nYear) {
		var sRes;
		sRes = padNmb(nDay, 2, "0") + "/" + padNmb(nMonth, 2, "0") + "/" + padNmb(nYear, 4, "0");
		return sRes;
	}

	function incDate(sFec0) {
		var nDia = parseInt(sFec0.substr(0, 2), 10);
		var nMes = parseInt(sFec0.substr(3, 2), 10);
		var nAno = parseInt(sFec0.substr(6, 4), 10);
		nDia += 1;
		if(nDia > finMes(nMes, nAno)) {
			nDia = 1;
			nMes += 1;
			if(nMes == 13) {
				nMes = 1;
				nAno += 1;
			}
		}
		return makeDateFormat(nDia, nMes, nAno);
	}

	function decDate(sFec0) {
		var nDia = Number(sFec0.substr(0, 2));
		var nMes = Number(sFec0.substr(3, 2));
		var nAno = Number(sFec0.substr(6, 4));
		nDia -= 1;
		if(nDia == 0) {
			nMes -= 1;
			if(nMes == 0) {
				nMes = 12;
				nAno -= 1;
			}
			nDia = finMes(nMes, nAno);
		}
		return makeDateFormat(nDia, nMes, nAno);
	}

	function addToDate(sFec0, sInc) {
		var nInc = Math.abs(parseInt(sInc));
		var sRes = sFec0;
		if(parseInt(sInc) >= 0) for(var i = 0; i < nInc; i++) sRes = incDate(sRes);
		else for(var i = 0; i < nInc; i++) sRes = decDate(sRes);
		return sRes;
	} //FIN Fecha para calculo de  FPP
	// Calcula la FPP
	function recalcF1() {
		with(document.form1) {
			fecha_probable_parto.value = addToDate(fecha_diagnostico_embarazo.value, 280 - (semanas_embarazo.value * 7));
		}
	} // FIN FPP
	// Funcion para que cuando presionas Enter en los campos no haga nada.
	function pulsar(e) {
		tecla = (document.all) ? e.keyCode : e.which;
		return(tecla != 13);
	} // FIN

</script>

<div class="newstyle-full-container">
<form name='form1' action='ins_admin.php' accept-charset="utf-8" method='POST'>
	
	<input type="hidden" value="<?=$id_planilla?>" name="id_planilla">
	
	<?
		if($accion!="") {
		$msj = explode(",", $accion);
		if ($msj[0]=="SUCCESS")
			$msj_class = "alert-success";
		else
			$msj_class = "alert-info";
	?>
	
	<div class="alert <?=$msj_class?>">
		<button class="close" data-dismiss="alert" type="button">×</button>
		<?=$msj[1]?>
	</div>
	
	<?
		}
	?>
	
	<select name="id_categoria" id="id_categoria" style="display:none;" onKeypress="buscar_combo(document);" onblur="borrar_buffer();"
		onchange="borrar_buffer(); cambiar_patalla(); document.forms[0].submit();" 
		<?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled" ?>>

		<?
		$sql= "select * from uad.categorias order by id_categoria";
		$res_efectores=sql($sql) or fin_pagina();
		?>
	   
		<option value='-1' selected>Seleccione</option>
		<?
			while (!$res_efectores->EOF) { 
				$id_categorial=$res_efectores->fields['id_categoria'];
				$tipo_ficha=$res_efectores->fields['tipo_ficha'];
				$categoria=$res_efectores->fields['categoria'];
		?>
		<option value='<?=$id_categorial?>'<?if ($id_categoria==$id_categorial) echo "selected";?> <?echo $tipo_ficha."-".$categoria;?>></option>
		<?
				$res_efectores->movenext();
			}
		?>
	</select>
	
	<input type="hidden" value="<?=$usuario1?>" name="usuario1">
	<input type="hidden" value="<?=date("j/n/Y")?>" id="hidden_fecha_servidor">

	<legend><?= ($id_planilla)? "Editar Beneficiario <small>#" . $clave_beneficiario . "</small>" : "Nuevo Beneficiario" ?></legend>

	<div class="alert">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>Atención!</strong> Al ingresar valores numéricos no utilize separadores de miles. Utilize "." (punto) como separador DECIMAL.
	</div>
	
	<div class="row-fluid">
		<div class="span12">
			<label>Tipo de Transaccion:</label>
			<select name="tipo_transaccion" onKeypress="buscar_combo(this);" onblur="borrar_buffer();" 
				onchange="borrar_buffer();document.forms[0].submit()" 
				<? if ($trans == 'Borrado')echo "disabled"?>>
				<option value='A' <?if ($tipo_transaccion=='A') echo "selected"?>>Inscripción</option>
				<option value='M'<?if ($tipo_transaccion=='M') echo "selected"?>>Modificación</option>
			</select>	
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span3">
			<label>Número de Documento:</label>
			<input type="text" value="<?=$num_doc?>" name="num_doc" onblur="CheckDNI(this);" <? 
				if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				
			<input class="btn btn-primary" type="button" value="Buscar" id="search_button" name="search_button">
		</div>
		
		<div class="span3">
			<label>El Documento es:</label>
			<select name="clase_doc" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<option value="P" <?if ($clase_doc=='P') echo "selected"?>>Propio</option>
				<option value="A" <?if ($clase_doc=='A') echo "selected"?>>Ajeno</option>
			</select>
		</div>
		
		<div class="span3">
			<label>Tipo de Documento:</label>
				<select name=tipo_doc <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<option value=DNI <?if ($tipo_doc=='DNI') echo "selected"?>>Documento Nacional de Identidad</option>
				<option value=LE <?if ($tipo_doc=='LE') echo "selected"?>>Libreta de Enrolamiento</option>
				<option value=LC <?if ($tipo_doc=='LC') echo "selected"?>>Libreta Civica</option>
				<option value=PA <?if ($tipo_doc=='PA') echo "selected"?>>Pasaporte Argentino</option>
				<option value=CM <?if ($tipo_doc=='CM') echo "selected"?>>Certificado Migratorio</option>
			</select>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<label>Apellidos:</label>
			<input type="text" size="30" value="<?=$apellido?>" name="apellido" 
				onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
		
		<div class="span3">
			<label>Nombres:</label>
			<input type="text" size="30" value="<?=$nombre?>" name="nombre" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<label>Correo Electrónico:</label>
			<input type="text" size="35" name="mail" value="<?=$mail?>" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
		
		<div class="span3">
			<label>Teléfono Celular:</label>
			<input type="text" size="30" name="celular" value="<?=$celular?>" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
	</div>
	
	<legend class="form-title">
		Datos de Nacimiento, Sexo, Origen y Estudios
	</legend>
	
	<div class="row-fluid">
		<div class="span3">
			<label>Sexo:</label>
			<select name=sexo <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> >
				<option value='-1'>Seleccione</option>
				<option value=F <?if ($sexo=='F') echo "selected"?>>Femenino</option>
				<option value=M <?if ($sexo=='M') echo "selected"?>>Masculino</option>
			</select>
		</div>
		
		<div class="span3">
			<label>Fecha de Nacimiento:</label>
			<input type="hidden" name="edades" id=edades value="<?=$edad?>">
			<input type=text name=fecha_nac id=fecha_nac onchange="esFechaValida(this);" onblur="edad(this.value); cambiar_patalla(); " value='<?=$fecha_nac;?>' size=15 onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			<?=link_calendario('fecha_nac'); echo $edades;?> 
		</div>
		
		<div class="span3">
			<label>Extranjero/Pais:</label>
			<input type="hidden" name="paisn" value="<?=$paisn?>"> 
			<select id="pais_nac" name="pais_nac" onchange="showpais_nac();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>><?php echo $pais_nac;?></select>
		</div>
	</div>
	
	<legend class="form-title">
		Pueblos Originarios
	</legend>
	
	<div class="row-fluid">
		<div class="span3">
			<label>¿Pertenece a algún Pueblo Originario?</label>
			<label class="radio">
				<input type="radio" name="indigena" value="N" 
					<?php if (($id_planilla) and ($tipo_transaccion != "M")) echo "disabled" ?> 
					<?php if(($indigena == "N") or ($indigena==""))echo "checked" ; ?> 
					onclick="document.all.id_tribu.value='0';document.all.id_lengua.value='0';">
					NO
			</label>
			<label class="radio">
				<input type="radio" name="indigena" value="S" 
					<?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> 
					<?php if($indigena == "S") echo "checked" ;?> 
					onclick="document.all.id_tribu.disabled=false;document.all.id_lengua.disabled=false;">
					SI
			</label>
			
		</div>
	
		<div class="span3">
			<label>Pueblo Indigena:</label>
			<select name=id_tribu onKeypress="buscar_combo(this);" onblur="borrar_buffer();" onchange="borrar_buffer();" 
				<?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<option value='-1'>Seleccione</option>
				<?
				$sql= "select * from uad.tribus";
				$res_efectores=sql($sql) or fin_pagina();
				
				while (!$res_efectores->EOF){ 
					$id=$res_efectores->fields['id_tribu'];
					$nombre=$res_efectores->fields['nombre'];
				?>
					<option value='<?=$id?>' <?if ($id_tribu==$id) echo "selected"?> ><?=$nombre?></option>
				<?
					$res_efectores->movenext();
				}
				?>
			</select>
		</div>
	
		<div class="span3">
			<label>Idioma O Lengua:</label>
			<select name=id_lengua onKeypress="buscar_combo(this);"	onblur="borrar_buffer();" onchange="borrar_buffer();" 
				<?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<option value='-1'>Seleccione</option>
				<?
				$sql= "select * from uad.lenguas";
				$res_efectores=sql($sql) or fin_pagina();
				
				while (!$res_efectores->EOF){ 
					$id=$res_efectores->fields['id_lengua'];
					$nombre=$res_efectores->fields['nombre'];
				?>
					<option value='<?=$id?>' <?if ($id_lengua==$id) echo "selected"?> ><?=$nombre?></option>

				<?
					$res_efectores->movenext();
				}
				?>
			</select>
		</div>
	</div>
	
	<legend class="form-title">
		Estudios
	</legend>
	
	<div class="row-fluid">
		<div class="span3">
			<label>Alfabetizado:</label>
			<label class="radio">
				<input type="radio" name="alfabeta" value="S" onclick="document.all.estudios[1].checked=true" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if(($alfabeta == "S") or ($alfabeta==""))echo "checked" ;?>> SI
			</label>
			<label class="radio">
				<input type="radio" name="alfabeta" value="N" onclick="document.all.estudios[0].checked=false;document.all.estudios[1].checked=false;document.all.estudios[2].checked=false;document.all.anio_mayor_nivel.value='0';" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if($alfabeta == "N") echo "checked" ;?>> NO
			</label>
		</div>
		
		<div class="span3">
			<label>Estado:</label>
			<select name=estadoest <?php if (($id_planilla) and ($tipo_transaccion != "M")) echo "disabled"?>>
				<option value=C <?if ($estadoest=='C') echo "selected"?>>Completo</option>
				<option value=I <?if ($estadoest=='I') echo "selected"?>>Incompleto</option>
			</select>
		</div>
	
		<div class="span3">
			<label>Estudios:</label>
			<label class="radio">
				<input type="radio" name="estudios" value="Inicial" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if(($estudios == "INICIAL") or ($estudios=="Inicial"))echo "checked" ;?>>Inicial
			</label>
			<label class="radio">
				<input type="radio" name="estudios" value="Primario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if(($estudios == "PRIMARIO") or ($estudios=="Primario"))echo "checked" ;?>>Primario
			</label>
			<label class="radio">
				<input type="radio" name="estudios" value="Secundario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if (($estudios == "SECUNDARIO")or ($estudios=="Secundario"))echo "checked" ;?>>Secundario
			</label>
			<label class="radio">
				<input type="radio" name="estudios" value="Terciario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if (($estudios == "TERCIARIO")or ($estudios=="Terciario"))echo "checked" ;?>>Terciario
			</label>
			<label class="radio">
				<input type="radio" name="estudios" value="Universitario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if (($estudios == "UNIVERSITARIO")or ($estudios=="Universitario"))echo "checked" ;?>>Universitario
			</label>
		</div>
		
		<div class="span3">
			<label>Años Mayor Nivel:</label>
			<input type="text" size="30" value='<?= $anio_mayor_nivel;?>' name="anio_mayor_nivel" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
	</div>
	
	<legend class="form-title">
		Datos del Domicilio
	</legend>
	
	<div class="row-fluid">
		<div class="span3" id="cdomi" style="display:<?=$cdomi1?>">
			<label>Cambio de Domicilio:</label>
				<select name=cambiodom onchange="document.forms[0].submit()" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> >
				<option value='-1'>Seleccione</option>
				<option value=S <?if ($cambiodom=='S') echo "selected"?>>SI</option>
				<option value=N <?if ($cambiodom=='N') echo "selected"?>>NO</option>
			</select>
		</div>
		
		<div class="span3" id="mva" style="display:<?=$mva1?>">
			<label>Menor convive con adulto:</label>
			<select name=menor_convive_con_adulto id=menor_convive_con_adulto <?php if (($id_planilla) and ($tipo_transaccion != "M")) echo "disabled"?>>
				<option value='' >Seleccione</option>
				<option value=S <?if ($menor_convive_con_adulto=='S') echo "selected"?>>SI</option>
				<option value=N <?if ($menor_convive_con_adulto=='N') echo "selected"?>>NO</option>
			</select>
		</div>
	</div>  
	
	<div class="row-fluid">
		<div class="span3">
			<label>Calle:</label>
			<input type="text" size="30" value="<?=$calle?>" name="calle" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
		
		<div class="span3">
			<label>N° de Puerta:</label>
			<input type="text" size="15" value="<?=$numero_calle?>" name="numero_calle" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span3">
			<label>Piso:</label>
			<input type="text" size="15" value="<?=$piso?>" name="piso" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>         	
		</div>
		
		<div class="span3">
			<label>Depto:</label>
			<input type="text" size="10" value="<?=$dpto?>" name="dpto" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
		
		<div class="span3">
			<label>Mz:</label>
			<input type="text" size="10" value="<?=$manzana?>" name="manzana" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>         	
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span3">
			<label>Entre Calle 1:</label>
			<input type="text" size="15" value="<?=$entre_calle_1?>" name="entre_calle_1" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
		
		<div class="span3">
			<label>Entre Calle 2:</label>
			<input type="text" size="15" value="<?=$entre_calle_2?>" name="entre_calle_2" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>         	
		</div>
		
		<div class="span3">
			<label>Telefono:</label>
			<input type="text" size="30" value="<?=$telefono?>" name="telefono" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>         	
		</div>
		
		<div class="span3">
			<label>Otro (ej: vecino)</label>
			<input type="text" size="30" name="otrotel" value="<?=$otrotel?>" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span3">
			<label>Departamento:</label>
			<input type="hidden" name="departamenton" value="<?=$departamenton?>"> 
			<select id="departamento" name="departamento" onchange="showdepartamento();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>><?php echo $departamento;?></select>
		</div>
		<div class="span3">
			<label>Localidad:</label>
			<input type="hidden" name="localidadn" value="<?=$localidadn?>">
			<select id="localidad" name="localidad" onchange="showlocalidad();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>><?php echo $opciones2;?></select>
		</div>
		<div class="span3">
			<label>Codigo Postal:</label>
			<input type="hidden" name="cod_posn" value="<?=$cod_posn?>"> 
			<select id="cod_pos" name="cod_pos" onchange="showcodpos();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>><?php echo $opciones5; ?></select>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span3">
			<label>Municipio:</label>
			<input type="hidden" name="municipion" value="<?=$municipion?>">
			<select id="municipio" name="municipio" onchange="showmunicipio();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>><?php echo $opciones3; ?></select>
		</div>
		
		<div class="span3">
			<label>Barrio:</label>
			<input type="hidden" name="barrion" value="<?=$barrion?>">
			<select id="barrio" name="barrio" onchange="showbarrio();" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>><?php echo $opciones4; ?></select>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span6">
			<label>Observaciones:</label>
			<textarea class="input-xxlarge" name='observaciones' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<?=$observaciones;?> 
			</textarea>
		</div>
	</div>
	
	<div id="cat_nino" style="display:<?= $datos_resp ?>;">
	<? 
		if ($id_categoria!='6'){ 
	?>
	
		<legend class="form-title">
			Datos del Responsable
		</legend>
		
		<div class="row-fluid">
			<div class="span3">
				<label>Responsable:</label>
				<select name=responsable Style="width=200px" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> >
					<option value='-1' <? if ($responsable=='-1') echo "selected"?>>Seleccione</option> 
					<option value=MADRE <?if ($responsable=='MADRE') echo "selected"?>>Madre</option>
					<option value=PADRE <?if ($responsable=='PADRE') echo "selected"?>>Padre</option>
					<option value=TUTOR <?if ($responsable=='TUTOR') echo "selected"?>>Tutor</option>
				</select>
			</div>
			
			<div class="span3">
				<label>Tipo de Documento:</label>
					<select name=tipo_doc_madre Style="width=200px" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
					<option value=DNI <?if ($tipo_doc_madre=='DNI') echo "selected"?>>Documento Nacional de Identidad</option>
					<option value=LE <?if ($tipo_doc_madre=='LE') echo "selected"?>>Libreta de Enrolamiento</option>
					<option value=LC <?if ($tipo_doc_madre=='LC') echo "selected"?>>Libreta Civica</option>
					<option value=PA <?if ($tipo_doc_madre=='PA') echo "selected"?>>Pasaporte Argentino</option>
					<option value=CM <?if ($tipo_doc_madre=='CM') echo "selected"?>>Certificado Migratorio</option>
				</select>
			</div>
			
			<div class="span3">
				<label>Documento:</label>
				<input type="text" size="30" value="<?=$nro_doc_madre?>" name="nro_doc_madre" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span3">
				<label>Apellidos:</label>
				<input type="text" size="30" value="<?=$apellido_madre?>" name="apellido_madre" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			</div>
			
			<div class="span3">
				<label>Nombres:</label>
				<input type="text" size="30" value="<?=$nombre_madre?>" name="nombre_madre" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			</div>
		</div>
		
		<legend class="form-title">
			Alfabetización
		</legend>
		
		<div class="row-fluid">
			<div class="span3">
				<label>Alfabetizado/a:</label>
				<label class="radio">
					<input type="radio" name="alfabeta_madre" value="S" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> onclick="document.all.estudios_madre[1].checked=true" checked> SI
				</label>
				<label class="radio">
					<input type="radio" name="alfabeta_madre" value="N" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> onclick="document.all.estudios_madre[0].checked=false;document.all.estudios_madre[1].checked=false;document.all.estudios_madre[2].checked=false;document.all.anio_mayor_nivel_madre.value='0';"> NO
				</label>
			</div>
			
			<div class="span3">
				<label>Estado:</label>
				<select name=estadoest_madre Style="width=200px" <?php if (($id_planilla) and ($tipo_transaccion != "M")) echo "disabled"?>>
					<option value=C <?if ($estadoest_madre=='C') echo "selected"?>>Completo</option>
					<option value=I <?if ($estadoest_madre=='I') echo "selected"?>>Incompleto</option>
				</select>
			</div>
			
			<div class="span3">
				<label>Estudios:</label>
				<label class="radio">
					<input type="radio" name="estudios_madre" value="Inicial" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if(($estudios_madre == "INICIAL") or ($estudios_madre=="Inicial"))echo "checked" ;?>>Inicial
				</label>
				<label class="radio">
					<input type="radio" name="estudios_madre" value="Primario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if(($estudios_madre == "PRIMARIO") or ($estudios_madre=="Primario"))echo "checked" ;?>>Primario
				</label>
				<label class="radio">
					<input type="radio" name="estudios_madre" value="Secundario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if (($estudios_madre == "SECUNDARIO")or ($estudios_madre=="Secundario"))echo "checked" ;?>>Secundario
				</label>
				<label class="radio">
					<input type="radio" name="estudios_madre" value="Terciario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if (($estudios_madre == "TERCIARIO")or ($estudios_madre=="Terciario"))echo "checked" ;?>>Terciario
				</label>
				<label class="radio">
					<input type="radio" name="estudios_madre" value="Universitario" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if (($estudios_madre == "UNIVERSITARIO")or ($estudios_madre=="Universitario"))echo "checked" ;?>>Universitario
				</label>
			</div>
			
			<div class="span3">
				<label>Años en el Mayor Nivel:</label>
				<input type="text" size="30" value='<?=$anio_mayor_nivel_madre?>' name="anio_mayor_nivel_madre" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			</div>
		</div>

	<?
		}
	?>
	</div>
	
	<div id="memb" style="display:<?=$memb?>;">
		<div class="">
			<label>Embarazada:</label>
			<select name=menor_embarazada id=menor_embarazada Style="width=200px" onchange="cambiar_patalla();" <?php if (($id_planilla) and ($tipo_transaccion != "M")) echo "disabled"?>>
				<option value=N <?if ($menor_embarazada=='N') echo "selected"?>>NO</option>
				<option value=S <?if ($menor_embarazada=='S') echo "selected"?>>SI</option>
			</select>
		</div>
	</div>
	
	<div id="cat_emb" style="display:<?= $embarazada ?>;">
	<? 
		if ($sexo!='m') { 
	?>
		<legend class="form-title">
			Datos del Embarazo
		</legend>
		
		<div class="row-fluid">
			<div class="span3">
				<label>F.U.M.:</label>
				<?$fecha_comprobante=date("d/m/Y");?>
				<input type=text name=fum id=fum size=15 onblur="esFechaValida(this);" value='<?=$fum;?>' onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<?=link_calendario("fum");?>	
			</div>
			
			<div class="span3">
				<label>Fecha de Diag. de Embarazo:</label>
				<input type=text name=fecha_diagnostico_embarazo id=fecha_diagnostico_embarazo onblur="esFechaValida(this);mostrarFDE()" value='<?=$fecha_diagnostico_embarazo;?>' onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> size=15>
				 <?=link_calendario("fecha_diagnostico_embarazo");?>	
			</div>
			
			<div class="span3">
				<label>Semana de Embarazo:</label>
				<input type="text" name="semanas_embarazo" id=semanas_embarazo  value='<?=$semanas_embarazo;?>' onblur="recalcF1();"  size="30" onkeypress="return pulsar(event)"  <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			</div>
			
			<div class="span3">
				<label>Fecha Probable de Parto:</label>
				<input type=text name=fecha_probable_parto id=fecha_probable_parto onblur="esFechaValida(this);mostrarDias();" value='<?=$fecha_probable_parto;?>' size=15 onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
				<?=link_calendario("fecha_probable_parto");?>	
			</div>
		</div>
		
		<legend class="form-title">
			Riesgo Cardiovascular
		</legend>
		
		<div class="row-fluid">
			<div class="span3">
				<label>Score de riesgo:</label>
				<input type="text" size="10" value='<?=$score_riesgo?>' name="score_riesgo" onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			</div>
		</div>
		
	<?
		}
	?>
	</div>
	
	<legend class="form-title">
		Discapacidad
	</legend>
	
	<div class="row-fluid">
		<div class="span12">
			<label>Tipo de Discapacidad</label>
			<label class="checkbox">
				<input type=checkbox name=discv value='Visual' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if($discv == "VISUAL") echo "checked" ;?> > Visual
			</label>
			<label class="checkbox">
				<input type=checkbox name=disca value='Auditiva' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if($disca == "AUDITIVA") echo "checked" ;?> > Auditiva
			</label>
			<label class="checkbox">
				<input type=checkbox name=discmo value='Motriz' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if($discmo == "MOTRIZ") echo "checked" ;?> > Motriz
			</label>
			<label class="checkbox">
				<input type=checkbox name=discme value='Mental' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if($discme == "MENTAL") echo "checked" ;?> > Mental
			</label>
			<label class="checkbox">
				<input type=checkbox name=otradisc value='Otra Discapacidad' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> <?php if($otradisc == "OTRA DISCAPACIDAD") echo "checked" ;?> > Otra discapacidad 
			</label>
		</div> 
	</div>
	
	<legend class="form-title">
		Fecha de Inscripcion
	</legend>

	<div class="">
		<label>Fecha de Inscripcion:</label>
		<input type=text onblur="esFechaValida(this);" name=fecha_inscripcion id=fecha_inscripcion value='<?=$fecha_inscripcion;?>' size=15 onkeypress="return pulsar(event)" <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
		<?=link_calendario("fecha_inscripcion");?>		
	</div>
	
	<legend class="form-title">
		Efector Habitual
	</legend>

	<div class="">
		<label>Efector Habitual</label>
		<select name=cuie onKeypress="buscar_combo(this);" onblur="borrar_buffer();" onchange="borrar_buffer();"
			<?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?>>
			<?
				$user_login1=substr($_ses_user['login'],0,6);
				if (es_cuie($_ses_user['login']))
					$sql= "select cuie, nombreefector, com_gestion from nacer.efe_conv where cuie='$user_login1' order by nombreefector";	
				else{
					echo"<option value=-1>Seleccione</option>";
					$sql= "select cuie, nombreefector, com_gestion from nacer.efe_conv order by nombreefector";
				}

				$res_efectores=sql($sql) or fin_pagina();
				while (!$res_efectores->EOF) { 
					$cuiel=$res_efectores->fields['cuie'];
					$nombre_efector=$res_efectores->fields['nombreefector'];
			?>
				<option value='<?=$cuiel?>' <?if ($cuie==$cuiel) echo "selected"?> ><?=$cuiel." - ".$nombre_efector?></option>
			<?
					$res_efectores->movenext();
				}
			?>
		</select>
	</div>
	
	<legend class="form-title">
		Observaciones
	</legend>
	
	<div class="row-fluid">
		<div class="row6">
			<label>Observaciones Generales</label>
			<textarea class="input-xxlarge" name='obsgenerales' <?php if (($id_planilla) and ($tipo_transaccion != "M"))echo "disabled"?> > <?=$obsgenerales;?></textarea>
		</div>
	</div>
 
	<?
		if ((!($id_planilla)) and ($clave_beneficiario=='')) {
	?>
		<div class="form-actions">
			<h4>Guardar Planilla:</h4>
			<div class="alert alert-error">
				<strong>Atención:</strong> Por favor, verifique todos los datos antes de guardar.
			</div>
			<input class="btn btn-primary btn-large" type='submit' name='guardar' value='Guardar Planilla' onclick="return control_nuevos()" title="Guardar datos de la Planilla" >
			
			<button class="btn btn-info btn-large pull-right" type='button' name="volver" value="Volver" 
				onclick="document.location='ins_listado.php'"title="Volver al Listado">
				<i class="icon-chevron-left icon-white"></i>
				Volver
			</button>
		</div>
	<?
		}
		if ($clave_beneficiario != '') {
	?>
		<div class="form-actions">
			<h4>Editar Datos:</h4>
			<div class="alert alert-error">
				<strong>Atención:</strong> Por favor, verifique todos los datos antes de guardar.
			</div>
			
			<input class="btn btn-primary btn-large" type="submit" name="guardar_editar" value="Guardar" title="Guardar" <?php if ($tipo_transaccion != "M") echo "disabled"?> onclick="return control_nuevos()">
		    <input class="btn btn-large" type="button" name="cancelar_editar" value="Cancelar" title="Cancelar Edicion" style="width=130px" <?php if ($tipo_transaccion != "M") echo "disabled"?> onclick="history.back(-1);">		      
		    <? if (permisos_check("inicio","permiso_borrar")) 
				$permiso="";
			  else 
				$permiso="disabled";
			?>
			<input class="btn btn-danger btn-large" type="submit" name="borrar" value="Borrar" style="width=130px" <?=$permiso?> <?php if ($tipo_transaccion != "B") echo "disabled"?>>
			
			<button class="btn btn-info btn-large pull-right" type='button' name="volver" value="Volver" 
				onclick="document.location='ins_listado.php'"title="Volver al Listado">
				<i class="icon-chevron-left icon-white"></i>
				Volver
			</button>
		</div>
	 <?
		}
	 ?>
 
 </form>
 </div>
 
<!--Hidden Elements-->

<!-- Modal -->
<div class="modal hide" id="beneficiarios_result" tabindex="-1" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4>Se encontraron beneficiarios con el DNI ingresado</h4>
		<p>Seleccione uno para modificarlo o haga click en cancelar para inscribir uno nuevo</p>
	</div>
	<div class="modal-body">
		<table class="table table-condensed table-hover" id="beneficiarios_list">
		</table>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
		<button id="seleccionar-beneficiario" class="btn btn-primary">Seleccionar</button>
	</div>
</div>

</body>
</html>
 <? // fin_pagina(); ?>
