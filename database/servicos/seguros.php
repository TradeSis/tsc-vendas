<?php
// helio 14022023 - tratamento do retorno da api
// helio 01022023 alterado para include_once
// helio 31012023 - eliminado funcao buscaCliente, ficou apenas buscaClientes,
//					o parametro mudou para o idCliente, e não mais string(where)
//					colocado chamada chamaAPI					
// helio 26012023 - function buscasClientes - Retirado mysql e Colocado CURL (API)
// helio 26012023 16:16

include_once('../conexao.php');

function buscaSeguros($codigoCliente,$codigoFilial,$recID)
{
	
	$seguros = array();
	$apiEntrada = array(
		'codigoCliente' => $codigoCliente,
		'codigoFilial' => $codigoFilial,
		'recID' => $recID
	);

	$seguros = chamaAPI(null, '/vendas/seguros', json_encode($apiEntrada), 'GET');
	

	if (isset($seguros["seguros"])) {
        $seguros = $seguros["seguros"]; // TRATAMENTO DO RETORNO
	}
	return $seguros;
}



?>

