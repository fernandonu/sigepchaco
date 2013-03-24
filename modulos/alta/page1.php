<?php

session_start();

echo 'Welcome to page #1';

$_SESSION['favcolor'] = 'green';
$_SESSION['animal']   = 'cat';
$_SESSION['time']     = time();

// Works if session cookie was accepted
echo '<br /><a href="page2.php">page 2</a>';

// Or maybe pass along the session id, if needed
echo '<br /><a href="page2.php?' . SID . '">page 2</a>';


$sql= " select nacer.efe_conv.nombreefector, nacer.efe_conv.cuie from nacer.efe_conv join sistema.usu_efec on (nacer.efe_conv.cuie = sistema.usu_efec.cuie) 
			        join sistema.usuarios on (sistema.usu_efec.id_usuario = sistema.usuarios.id_usuario) 
			        where sistema.usuarios.id_usuario = '$usuario1' order by nombre";
		$res_efectores=sql($sql);
		$cuiel=$res_efectores->fields['cuie'];
$_SESSION['cuieee'] = $cuiel;


?>




