<?php
//Lucas 21102024 criado

include_once __DIR__ . "/../../conexao.php";

function buscaGEAvulsa($procod = null)
{
	
	$garantia = array();
	$apiEntrada = 
	array(
		"dadosEntrada" => array(
			array(
				'procod' => $procod
			)
		)
	);

	$garantia = chamaAPI(null, '/vendas/geaparam', json_encode($apiEntrada), 'GET');
	return $garantia;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

    if ($operacao == "inserir") {

		$apiEntrada = 
		array(
			"geaparam" => array(
				array(
					'procod' => $_POST['procod']
				)
			)
		);
	
		$garantia = chamaAPI(null, '/vendas/geaparam', json_encode($apiEntrada), 'PUT');
		echo json_encode($garantia);
		return;
	}

    if ($operacao == "excluir") {

		$apiEntrada = 
		array(
			"geaparam" => array(
				array(
					'id_recid' => $_POST['id_recid']
				)
			)
		);
	
		$garantia = chamaAPI(null, '/vendas/geaparam', json_encode($apiEntrada), 'DELETE');
	
	}

	if ($operacao == "buscar") {

        $procod = isset($_POST["procod"]) && $_POST["procod"] !== "null"  ? $_POST["procod"]  : null;
		
		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'procod' => $procod
				)
			)
		);
		$garantia = chamaAPI(null, '/vendas/geaparam', json_encode($apiEntrada), 'GET');

		echo json_encode($garantia);
		return $garantia;
	}


}