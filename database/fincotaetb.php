<?php
// lucas 15082024 

include_once __DIR__ . "/../conexao.php";

function buscaUtilizacao($Etbcod = null)
{
	
	$utilizacao = array();
	$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'Etbcod' => $Etbcod
				)
			)
		);

	$utilizacao = chamaAPI(null, '/vendas/fincotaetb', json_encode($apiEntrada), 'GET');
	return $utilizacao;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];


	if ($operacao == "alterar") {

		$apiEntrada = 
		array(
			"fincotaetb" => array(
				array(
					'Etbcod' => $_POST['Etbcod'],
					'fincod' => $_POST['fincod'],
					'DtIVig' => $_POST['DtIVig'],
					'Ativo' => $_POST['Ativo']
				)
			)
		);
		
	
		$utilizacao = chamaAPI(null, '/vendas/fincotaetb', json_encode($apiEntrada), 'POST');
	}


	if ($operacao == "buscar") {

		$id_recid = isset($_POST["id_recid"]) && $_POST["id_recid"] !== "null"  ? $_POST["id_recid"]  : null;

		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'id_recid' => $id_recid
				)
			)
		);
		$utilizacao = chamaAPI(null, '/vendas/fincotaetb', json_encode($apiEntrada), 'GET');

		echo json_encode($utilizacao);
		return $utilizacao;
	}

}