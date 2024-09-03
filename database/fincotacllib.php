<?php
// lucas 15082024 

include_once __DIR__ . "/../conexao.php";

function buscaFiliais($fcccod = null, $Etbcod = null, $DtIVig = null)
{
	
	$filiais = array();
	$apiEntrada = 
	array(
		"dadosEntrada" => array(
			array(
				'fcccod' => $fcccod,
				'Etbcod' => $Etbcod,
				'DtIVig' => $DtIVig
			)
		)
	);

	$filiais = chamaAPI(null, '/vendas/fincotacllib', json_encode($apiEntrada), 'GET');
	return $filiais;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
	
		$DtFVig = isset($_POST["DtFVig"]) && $_POST["DtFVig"] !== ""  ? $_POST["DtFVig"]  : null;

		$apiEntrada = 
		array(
			"fincotacllib" => array(
				array(
					'Etbcod' => $_POST['Etbcod'],
					'fcccod' => $_POST['fcccod'],
					'DtFVig' => $DtFVig,
					'CotasLib' => $_POST['CotasLib'],
					'DtIVig' => $_POST['DtIVig']
				)
			)
		);
	
		$filiais = chamaAPI(null, '/vendas/fincotacllib', json_encode($apiEntrada), 'PUT');
		echo json_encode($filiais);
		return $filiais;

	}

	if ($operacao == "alterar") {
		
		$DtFVig = isset($_POST["DtFVig"]) && $_POST["DtFVig"] !== ""  ? $_POST["DtFVig"]  : null;

		$apiEntrada = 
		array(
			"fincotacllib" => array(
				array(
					'Etbcod' => $_POST['Etbcod'],
					'fcccod' => $_POST['fcccod'],
					'DtFVig' => $DtFVig,
					'CotasLib' => $_POST['CotasLib'],
					'DtIVig' => $_POST['DtIVig']
				)
			)
		);
	
		$filiais = chamaAPI(null, '/vendas/fincotacllib', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "excluir") {

		$apiEntrada = 
		array(
			"fincotacllib" => array(
				array(
					'id_recid' => $_POST['id_recid']
				)
			)
		);
	
		$filiais = chamaAPI(null, '/vendas/fincotacllib', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {

        $fcccod = isset($_POST["fcccod"]) && $_POST["fcccod"] !== "null"  ? $_POST["fcccod"]  : null;
		$Etbcod = isset($_POST["Etbcod"]) && $_POST["Etbcod"] !== "null"  ? $_POST["Etbcod"]  : null;
		$DtIVig = isset($_POST["DtIVig"]) && $_POST["DtIVig"] !== "null"  ? $_POST["DtIVig"]  : null;

		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'fcccod' => $fcccod,
					'Etbcod' => $Etbcod,
					'DtIVig' => $DtIVig
				)
			)
		);
		$filiais = chamaAPI(null, '/vendas/fincotacllib', json_encode($apiEntrada), 'GET');

		echo json_encode($filiais);
		return $filiais;
	}

}