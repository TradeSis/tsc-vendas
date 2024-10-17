<?php
// lucas 14082024 

include_once __DIR__ . "/../conexao.php";

function buscaCluster($fcccod = null)
{
	
	$promocoes = array();
	$apiEntrada = 
	array(
		"dadosEntrada" => array(
			array(
				'fcccod' => $fcccod
			)
		)
	);

	$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'GET');
	return $promocoes;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {

		$apiEntrada = 
		array(
			"fincotacluster" => array(
				array(
					'fcccod' => $_POST['fcccod'],
            		'fccnom' => $_POST['fccnom'],
				)
			)
		);
	
		$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'PUT');
	
	}

	if ($operacao == "alterar") {

		$apiEntrada = 
		array(
			"fincotacluster" => array(
				array(
					'fcccod' => $_POST['fcccod'],
            		'fccnom' => $_POST['fccnom'],
				)
			)
		);
	
		$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "buscar") {

        $fcccod = isset($_POST["fcccod"]) && $_POST["fcccod"] !== "null"  ? $_POST["fcccod"]  : null;

		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'fcccod' => $fcccod
				)
			)
		);
		
		$promocoes = chamaAPI(null, '/vendas/fincotacluster', json_encode($apiEntrada), 'GET');

		echo json_encode($promocoes);
		return $promocoes;
	}

	if ($operacao == "cargaFilial") {
	
		$arryArquivo = $_FILES['file']['name'];
		$varquivo = implode(" ", $arryArquivo);
		
		$apiEntrada = 
		array(
			"carga" => array(
				array(
					'varquivo' => $varquivo
				)
			)
		);

		$carga = chamaAPI(null, '/vendas/fincotaclcarga', json_encode($apiEntrada), 'PUT');

		echo json_encode($carga);
		return $carga;
	}

	if ($operacao == "relatorio") {
		
		$pfiltraperiodo = isset($_POST["pfiltraperiodo"]) && $_POST["pfiltraperiodo"] == "Sim"  ? true  : false;
		$pdtini = isset($_POST["pdtini"]) && $_POST["pdtini"] !== ""  ? $_POST["pdtini"]  : null;
		$pdtfim = isset($_POST["pdtfim"]) && $_POST["pdtfim"] !== ""  ? $_POST["pdtfim"]  : null;

		$apiEntrada = 
		array(
			"fincotacluster" => array(
				array(
					'petbcod' => $_POST["petbcod"],
					'pfiltraperiodo' => $pfiltraperiodo,
					'pdtini' => $pdtini,
					'pdtfim' => $pdtfim
				)
			)
		);

		$relatorio = chamaAPI(null, '/vendas/fincotarel', json_encode($apiEntrada), 'PUT');

		echo json_encode($relatorio);
		return $relatorio;
	}

}