<?php


##
# Conexion con una base de datos de Microsoft SQL Server.
#
# En GNU/Debian, es necesario instalar el paquete php4-sybase para
# tener conexión con SQL Server.
#   apt-get install php4-sybase
##

## conexion a sql server...
$link=mssql_connect("nacer-chaco.chaco.gov.ar\UGSP","sa","");
## seleccionamos la base de datos
mssql_select_db("Facturacion",$link);



?>


