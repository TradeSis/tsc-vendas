<?php
// gabriel 300323 11:24


include_once('../conexao.php');

function buscaBonusCliente($codigoCliente)
{
	$bonuscliente = array();
	$retorno = array();
	$apiEntrada = 
		array("cliente" => array(
			array('codigoCliente' => $codigoCliente)
		));
	

	$retorno = chamaAPI(null, '/vendas/consultabonuscliente', json_encode($apiEntrada), 'GET');

   
	if (isset($retorno["conteudoSaida"])) {
		if (isset($retorno["conteudoSaida"]["cliente"])) {
        	$bonuscliente = $retorno["conteudoSaida"]; // TRATAMENTO DO RETORNO
		}
	}

	return $bonuscliente;
}



?>

