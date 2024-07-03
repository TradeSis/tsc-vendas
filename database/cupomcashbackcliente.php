<?php
// gabriel 290323 14:44


include_once('../conexao.php');

function buscaCashback($codigoCliente,$cpfCNPJ,$situacao)
{
	$cupomcashback = array();
	$retorno = array();
	$apiEntrada = 
		array("cliente" => array(
			array('codigoCliente' => $codigoCliente,
				  'cpfCNPJ' => $cpfCNPJ,
				  'situacao' => $situacao)
		));
	

	$retorno = chamaAPI(null, '/vendas/cupomcashback', json_encode($apiEntrada), 'GET');

   
	if (isset($retorno["conteudoSaida"])) {
		if (isset($retorno["conteudoSaida"]["cliente"])) {
        	$cupomcashback = $retorno["conteudoSaida"]; // TRATAMENTO DO RETORNO
		}
	}

	return $cupomcashback;
}



?>

