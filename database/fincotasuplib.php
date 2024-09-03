<?php
// lucas 15082024 

include_once __DIR__ . "/../conexao.php";

function buscaSupervisor($fcccod = null, $supcod = null, $DtIVig = null)
{
	
	$supervisor = array();
	$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'fcccod' => $fcccod,
					'supcod' => $supcod,
					'DtIVig' => $DtIVig
				)
			)
		);

	$supervisor = chamaAPI(null, '/vendas/fincotasuplib', json_encode($apiEntrada), 'GET');
	return $supervisor;
} 

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
	
		$DtFVig = isset($_POST["DtFVig"]) && $_POST["DtFVig"] !== ""  ? $_POST["DtFVig"]  : null;

		$apiEntrada = 
		array(
			"fincotasuplib" => array(
				array(
					'supcod' => $_POST['supcod'],
					'fcccod' => $_POST['fcccod'],
					'DtFVig' => $DtFVig,
					'CotasLib' => $_POST['CotasLib'],
					'DtIVig' => $_POST['DtIVig']
				)
			)
		);
	
		$supervisor = chamaAPI(null, '/vendas/fincotasuplib', json_encode($apiEntrada), 'PUT');
		echo json_encode($supervisor);
		return;

	}

	if ($operacao == "alterar") {

		$DtFVig = isset($_POST["DtFVig"]) && $_POST["DtFVig"] !== ""  ? $_POST["DtFVig"]  : null;

		$apiEntrada = 
		array(
			"fincotasuplib" => array(
				array(
					'supcod' => $_POST['supcod'],
					'fcccod' => $_POST['fcccod'],
					'DtFVig' => $DtFVig,
					'CotasLib' => $_POST['CotasLib'],
					'DtIVig' => $_POST['DtIVig']
				)
			)
		);
	
		$supervisor = chamaAPI(null, '/vendas/fincotasuplib', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "excluir") {

		$apiEntrada = 
		array(
			"fincotasuplib" => array(
				array(
					'id_recid' => $_POST['id_recid']
				)
			)
		);
	
		$supervisor = chamaAPI(null, '/vendas/fincotasuplib', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {

        $fcccod = isset($_POST["fcccod"]) && $_POST["fcccod"] !== "null"  ? $_POST["fcccod"]  : null;
		$supcod = isset($_POST["supcod"]) && $_POST["supcod"] !== "null"  ? $_POST["supcod"]  : null;
		$DtIVig = isset($_POST["DtIVig"]) && $_POST["DtIVig"] !== "null"  ? $_POST["DtIVig"]  : null;

		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'fcccod' => $fcccod,
					'supcod' => $supcod,
					'DtIVig' => $DtIVig
				)
			)
		);
		$supervisor = chamaAPI(null, '/vendas/fincotasuplib', json_encode($apiEntrada), 'GET');

		echo json_encode($supervisor);
		return $supervisor;
	}

}