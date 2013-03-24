<?php

require_once '../../lib/jquery/jq-config.php';
// include the jqGrid Class
require_once "../../lib/jquery/php/jqGrid.php";
// include the PDO driver class
require_once "../../lib/jquery/php/jqGridPdo.php";
// Connection to the server
$conn = new PDO(DB_DSN,DB_USER,DB_PASSWORD);
// Tell the db that we use utf-8
$conn->query("SET NAMES utf8");



// Create the jqGrid instance
$grid = new jqGridRender($conn);
// Write the SQL Query
$grid->SelectCommand = 'SELECT top 100 idWEB, CUIE, Clavebeneficiario, [Codigo NU] CNU, [Mes Corresp] Fecha FROM practicastempweb';
// set the ouput format to json
$grid->dataType = 'json';
// Let the grid create the model
$grid->setColModel();
// Set the url from where we obtain the data
$grid->setUrl('myfirstgrid.php');
// Set grid caption using the option caption
$grid->setGridOptions(array(
    "caption"=>"Listado de Practicas Facturadas",
    "rowNum"=>-1,
    "sortname"=>"idWEB",
    "hoverrows"=>true,
    "rowList"=>array(10,20,50),
	"height"=>500
    ));
	

// Change some property of the field(s)
$grid->setColProperty("idWEB", array("label"=>"ID", "width"=>60));
$grid->setColProperty("Fecha", array(
    "formatter"=>"date",
    "formatoptions"=>array("srcformat"=>"Y-m-d H:i:s","newformat"=>"m/d/Y")
    )
);
// Enjoy
$grid->renderGrid('#grid','#pager',true, null, null, true,true);
$conn = null;
?>
