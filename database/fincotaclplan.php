<?php
// lucas 15082024 

include_once __DIR__ . "/../conexao.php";

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
		
		$apiEntrada = array(
			'fincod' => $_POST['fincod'],
            'fcccod' => $_POST['fcccod']
		);
	
		$planos = chamaAPI(null, '/vendas/fincotaclplan', json_encode($apiEntrada), 'PUT');
		echo json_encode($planos);
		return;

	}

	if ($operacao == "excluir") {

		$apiEntrada = array(
			'id_recid' => $_POST['id_recid']
		);
	
		$planos = chamaAPI(null, '/vendas/fincotaclplan', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {

		$fincod = isset($_POST["fincod"]) && $_POST["fincod"] !== "null"  ? $_POST["fincod"]  : null;
        $fcccod = isset($_POST["fcccod"]) && $_POST["fcccod"] !== "null"  ? $_POST["fcccod"]  : null;

		$apiEntrada = array(
            'fincod' => $fincod,
            'fcccod' => $fcccod
		);
		$planos = chamaAPI(null, '/vendas/fincotaclplan', json_encode($apiEntrada), 'GET');

		echo json_encode($planos);
		return $planos;
	}

}