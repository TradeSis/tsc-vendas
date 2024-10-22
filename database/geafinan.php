<?php
//Lucas 21102024 criado

include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

    if ($operacao == "inserir") {

		$apiEntrada = 
		array(
			"geafinan" => array(
				array(
					'procod' => $_POST['procod'],
                    'fincod' => $_POST['fincod']
				)
			)
		);
	
		$garantia = chamaAPI(null, '/vendas/geafinan', json_encode($apiEntrada), 'PUT');
        echo json_encode($garantia);
		return;
	}

    if ($operacao == "excluir") {

		$apiEntrada = 
		array(
			"geafinan" => array(
				array(
					'id_recid' => $_POST['id_recid']
				)
			)
		);
	
		$garantia = chamaAPI(null, '/vendas/geafinan', json_encode($apiEntrada), 'DELETE');
	
	}


	if ($operacao == "buscar") {

        $procod = isset($_POST["procod"]) && $_POST["procod"] !== "null"  ? $_POST["procod"]  : null;
        $fincod = isset($_POST["fincod"]) && $_POST["fincod"] !== "null"  ? $_POST["fincod"]  : null;
		
		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'procod' => $procod,
                    'fincod' => $fincod
				)
			)
		);
		$garantia = chamaAPI(null, '/vendas/geafinan', json_encode($apiEntrada), 'GET');

		echo json_encode($garantia);
		return $garantia;
	}


}