<?
	if (ereg("/login.php",$_SERVER["SCRIPT_NAME"])) {
		$tmp=explode("/login.php",$_SERVER["SCRIPT_NAME"]);
		$html_root = $tmp[0];
	}
?>

<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=8,chrome=1">
	<title>Bienvenido a SIGEP - Sistema de Gestión del Programa SUMAR - Plan Nacer. Chaco</title>
	
	<link rel='stylesheet' type='text/css' href='newstyle/bootstrap/css/custom-bootstrap.css'>
	<link rel='stylesheet' type='text/css' href='newstyle/css/main.css'>
	<link rel='stylesheet' type='text/css' href='newstyle/css/nivo-slider.css'>
	
	<style type="text/css">
		center {
			display: none;
		}
	</style>
</head>

<body>
	<div class="container login-main-container">
		<hr/>
		<div class="row">
			<div class="span7">
				<div class="pull-right slider-wrapper">
					<div id="slider" class="nivoSlider">
						<img src="newstyle/images/embarazadas.jpg" alt="" />
						<img src="newstyle/images/mujeres.jpg" alt="" />
						<img src="newstyle/images/ninos.jpg" alt="" />
					</div>
				</div>
			</div>
			
			<div class="span5">
				<div class="login-form-container">
					<div id="login-error" class="alert alert-error" style="display: none;">
					</div>
					
					<form action='index.php' method='post' name='frm'>
						<input type="hidden" id="resolucion_ancho" value="">
						<input type="hidden" id="resolucion_largo" value="">

						<label>Usuario:</label>
						<input type="text" name="username">
						
						<label>Contrase&ntilde;a:</label>
						<input type="password" name="password">
						
						<div>
							<input class="btn btn-primary" type="submit" value="Iniciar Sesi&oacute;n" name="loginform">
						</div>
					</form>
					
					<div class="login-logos">
						<a href="http://www.msal.gov.ar/sumar/"><img src="newstyle/images/sumar-logo.png" alt="Programa SUMAR" /></a>
						<a href="http://www.plannacer.msal.gov.ar/"><img src="newstyle/images/nacer-logo.png" alt="Plan Nacer" /></a>
						<a href="http://www.msal.gov.ar/"><img src="newstyle/images/msal-logo.png" alt="Ministerio de Salud de la Nación" /></a> 
					</div>
				</div>
			</div>
		</div>
		<div class="login-footer">2012 - Programa SUMAR - Plan Nacer. Provincia del Chaco.</div>
	</div>
	
	<script src='newstyle/js/jquery-1.8.0.min.js'></script>
	<script src='newstyle/js/jquery-nivo-slider.pack.js'></script>
	
	<script>
		$(document).ready(function(){
			$("#resolucion_ancho").val(screen.width);
			$("#resolucion_largo").val(screen.height);
			var loginError = "";
			
			loginError = $("center font").text();
			$("center").remove();
			if (loginError != ""){
				$("#login-error").text(loginError);
				$("#login-error").show();
			}

		});
		
		$(window).load(function() {
			$('#slider').nivoSlider({
				effect: 'boxRandom',
				directionNav: false,
				controlNav: false,
				pauseOnHover: false,
				randomStart: true
			});
		});
	</script>
</body>
</html>
