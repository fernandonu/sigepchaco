<?
	require_once "config.php";
	require_once("./modulos/permisos/permisos.class.php");
	session_start();			

	if ($parametros['mode'] == "logout") {
		phpss_logout();
		$mode = "";
		include_once(ROOT_DIR."/login.php");
		exit;
	}

	if ($parametros['mode'] == "debug") {
	   if (permisos_check("inicio","debug")) {
			$_ses_user["debug"] = $parametros["debug_status"];
			phpss_svars_set("_ses_user", $_ses_user);
		}
	}

	$res_width=$_ses_user['res_width'];
	$res_height=$_ses_user['res_height'];

	if ($res_width >= 1024) {
		$size=2;
		$tam=230;
		$letra="12px";
	}
	else {
		$size=1;    
		$tam=180;
		$letra="10px";
	}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1">
	<title>SIGEP - Sistema de Gestión del Programa SUMAR - Plan Nacer. Chaco</title>
	
	<link rel='stylesheet' type='text/css' href='<?=$html_root?>/newstyle/bootstrap/css/custom-bootstrap.css'>
	<link rel='stylesheet' type='text/css' href='<?=$html_root?>/newstyle/css/header.css'>
	<link rel='stylesheet' type='text/css' href='<?=$html_root?>/newstyle/css/menu.css'>
	
	<script src='<?=$html_root?>/newstyle/js/jquery-1.8.0.min.js'></script>
	
	<script src='<?=$html_root?>/lib/dhtmlXCommon.js'></script>
	<script src='<?=$html_root?>/lib/dhtmlXProtobar.js'></script>
	<script src='<?=$html_root?>/lib/dhtmlXMenuBar.js'></script>
	<script src='<?=$html_root?>/lib/dhtmlXTree.js'></script>
</head>

<?

	/*****************************************************************************************/
	//Cuando use menu_xml va esta linea
	$usuario = new user($_ses_user['login']);
	$usrname = $_ses_user['login'];

	$sql= " select nacer.efe_conv.nombreefector, nacer.efe_conv.cuie from nacer.efe_conv join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
			join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
			where sistema.usuarios.login = '$usrname' order by nombre";
	$res_efectores=sql($sql);

	$_SESSION['cuie'] = $res_efectores->fields['cuie'];
	$_SESSION['efector'] = $res_efectores->fields['nombreefector'];
	$_SESSION['usrname'] = $usrname;
	/*****************************************************************************************/

	$onclick=encode_link("index.php",array("mode" => "logout")); 
	$root="$html_root/imagenes/logo_coradir.jpg"; 
	$accesos=$usuario->get_Accesos();
	
	$newstyles_path = $html_root . "/newstyle";

?>

<body style="min-width: 940px; height:100%; overflow:hidden;">
	
	<!-- Main Container -->
	<div>
		<!-- Header -->
		<div class="main-header">
			<div class="logos">
				<img src="<?echo $newstyles_path?>/images/sumar-logo.png" alt="Programa SUMAR" /> 
				<img src="<?echo $newstyles_path?>/images/nacer-logo.png" alt="Plan Nacer" /> 
				<img src="<?echo $newstyles_path?>/images/msal-logo.png" alt="Ministerio de Salud de la Nación" /> 
			</div>
			
			<div class="title">
				<h1>SIGEP</h1>
				<p>Sistema de Gestión. Programa SUMAR.</p>
				<p>Plan Nacer. Provincia del Chaco</p>
			</div>
			
			<div class="user-info">
				<?
					list($dia,$mes,$anio,$dia_s) = split("-", date("j-n-Y-w",mktime()));
				?>
				<p><i class="icon-calendar"></i> <?=$dia_semana[$dia_s]." ".$dia." de ".$meses[$mes]." de ".$anio?></p>
				<input id="input_fecha_servidor" type="hidden" value="<?=date("d/m/Y")?>" />
				<p><i class="icon-user"></i> <?=$_ses_user["name"]?></p>
				<p><a href="<?=$onclick?>"><i class="icon-off"></i> Cerrar Sesión</a></p>
			</div>
		</div>

		<!-- Main Menu Element -->

		<div id="xpstyle"></div>
		

		<!-- Content IFrame Element -->
		<?
			if ($parametros['mode'] == "debug") {  
				//es porque seleccionó el debugger y tiene que mantener la pagina
				//echo "GET= ".$_GET['menu'];
				$div=ereg_replace("^$html_root","",$_GET['menu']);
				//echo " DIV despues de reemplazar= ".$div;
				$div=explode("/",$div,4);
				//echo "DIV despues del explode= ";
				//print_r($div);  
				$pagina_inicio=ereg_replace("\.php","",$div[3]);
			}
			else {
				$pagina_inicio=$usuario->get_pagina_inicio();
			}
			$modulo_inicio=$usuario->permisos->getmoduloByName($pagina_inicio);
			$descripcion_inicio=$usuario->permisos->getpathByName($pagina_inicio).$usuario->permisos->getdescByName($pagina_inicio);

			if (strpos($pagina_inicio,"?") === false) 
				$pagina_inicio=$pagina_inicio.".php";
			else 
				$pagina_inicio=ereg_replace("\?",".php?",$pagina_inicio);

			$src=$html_root."/modulos/$modulo_inicio/$pagina_inicio";
		?>

		<!-- Main Iframe -->
		<div id="iframe-container">
			<iframe id="frame2" name="frame2" src="<?=$src?>" width="100%" scrolling="auto" frameborder="0">
			</iframe>
		</div>
	</div>
	
	<script>
	
		$(document).ready(function(){
			fix_size();
		});

		var resizeTimer = null;
		$(window).bind('resize', function() {
			if (resizeTimer) clearTimeout(resizeTimer);
			resizeTimer = setTimeout(fix_size, 50);
		});
		
		function onButtonClick(itemId,itemValue) {
			//document.all.frame2.src='';
		};

		menu = new dhtmlXMenuBarObject(document.getElementById('xpstyle'),'100%',20,"",0);
		menu.setOnClickHandler(onButtonClick);
		menu.setGfxPath("imagenes/menu/");
		menu.loadXML("<?=$html_root?>/menu_xml.php");  
		menu.showBar();
			
		function fix_size() {
			var headerHeight = 131;
			var iframeHeight = $(window).height() - headerHeight;
			$("#frame2").height(iframeHeight);
		}
	 
		var html_root='<? echo $html_root;?>';

	</script>
</body>
</html>
