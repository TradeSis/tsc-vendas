<?php
// lucas 14082024 

include_once __DIR__ . "/../conexao.php";

function buscaCluster($fcccod = null)
{
	
	$promocoes = array();
	$apiEntrada = array(
		'fcccod' => $fcccod
	);

	$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'GET');
	return $promocoes;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {

		$apiEntrada = array(
			'fcccod' => $_POST['fcccod'],
            'fccnom' => $_POST['fccnom'],
		);
	
		$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'PUT');
		

	}

	if ($operacao == "alterar") {

		$apiEntrada = array(
			'fcccod' => $_POST['fcccod'],
            'fccnom' => $_POST['fccnom'],
		);
	
		$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'POST');
	}



	if ($operacao == "buscar") {

        $fcccod = isset($_POST["fcccod"]) && $_POST["fcccod"] !== "null"  ? $_POST["fcccod"]  : null;

		$apiEntrada = array(
			'fcccod' => $fcccod
		);
		$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'GET');

		echo json_encode($promocoes);
		return $promocoes;
	}

}