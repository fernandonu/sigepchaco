jQuery(document).ready(function() {
	
	var cuie = get_url_param('cuie');
	

    jQuery("#gridpra").jqGrid({
        url: 'practicas.php?cuie=' + cuie,
        datatype: 'xml',
        mtype: 'GET',
        colNames: ['clavebeneficiario', 'dni', 'nombre', 'cuie'],
        colModel: [
            { name: 'clavebeneficiario', index: 'clavebeneficiario', width: 50, sortable: false },
            { name: 'dni', index: 'dni', width: 288, sortable: false },
            { name: 'nombre', index: 'nombre', width: 100, sortable: false},
            { name: 'cuie', index: 'cuie', width: 100, sortable: false }],
        rowNum: 50,
        rowList: [15, 30, 50],
        viewrecords: true,
        sortname: 'clavebeneficiario',
        height: 400,
        caption: 'Grilla de Practicas',
        loadui: 'block'
        });
    });

	function get_url_param(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null) return "";
    else return results[1];
	}