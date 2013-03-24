<?php
// page2.php

session_start();

echo 'Welcome to page #2<br />';

echo $_SESSION['favcolor']; // green
echo $_SESSION['animal'];   // cat
echo date('Y m d H:i:s', $_SESSION['time']);
echo $_ses_user['login'];
echo $_SESSION['usrname']; 
echo $_SESSION['cuieee'];   // cuieee

// You may want to use SID here, like we did in page1.php
echo '<br /><a href="alta_listado.php">page 1</a>';
?>
