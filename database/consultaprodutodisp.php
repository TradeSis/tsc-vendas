<?php
// gabriel 300323 11:24


include_once('../conexao.php');

function buscaProduDisp($procod)
{
	
	$produtos = array();
	$apiEntrada = array(
		'procod' => $procod
	);

	$produtos = chamaAPI(null, '/vendas/consultaprodutodisp', json_encode($apiEntrada), 'GET');
	

	if (isset($produtos["produtos"])) {
        $produtos = $produtos["produtos"]; // TRATAMENTO DO RETORNO
	}
	return $produtos;
}



?>



<?php


