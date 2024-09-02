<?php
// lucas 20082024 

include_once __DIR__ . "/../conexao.php";

function buscaSupervisores($supcod = null)
{
	
	$supervisor = array();

	$apiEntrada = array(
		'supcod' => $supcod
	);

	$supervisor = chamaAPI(null, '/vendas/supervisor', json_encode($apiEntrada), 'GET');
	return $supervisor;
} 

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {
	
		$apiEntrada = array(
			'supcod' => $_POST['supcod'],
            'supnom' => $_POST['supnom']
		);
	
		$supervisor = chamaAPI(null, '/vendas/supervisor', json_encode($apiEntrada), 'PUT');

	}

	if ($operacao == "alterar") {

		$idToken = isset($_POST["idToken"]) && $_POST["idToken"] !== "null"  ? $_POST["idToken"]  : null;

		$apiEntrada = array(
			'supcod' => $_POST['supcod'],
            'supnom' => $_POST['supnom'],
			'idToken' => $idToken
		);
	
		$supervisor = chamaAPI(null, '/vendas/supervisor', json_encode($apiEntrada), 'POST');
	}

	if ($operacao == "excluir") {

		$apiEntrada = array(
			'id_recid' => $_POST['id_recid']
		);
	
		$supervisor = chamaAPI(null, '/vendas/supervisor', json_encode($apiEntrada), 'DELETE');
	}

	if ($operacao == "buscar") {

        $supcod = isset($_POST["supcod"]) && $_POST["supcod"] !== "null"  ? $_POST["supcod"]  : null;
		$pagina = isset($_POST["pagina"])  && $_POST["pagina"] !== "" && $_POST["pagina"] !== "null" ? $_POST["pagina"]  : 0;
		
		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'supcod' => $supcod,
					'pagina' => $pagina
				)
			)
		);
		$supervisor = chamaAPI(null, '/vendas/supervisor', json_encode($apiEntrada), 'GET');

		echo json_encode($supervisor);
		return $supervisor;
	}

	if ($operacao == "buscarFilial") {

        $supcod = isset($_POST["supcod"]) && $_POST["supcod"] !== "null"  ? $_POST["supcod"]  : null;
		
		$apiEntrada = array(
            'supcod' => $supcod
		);
		$filiais = chamaAPI(null, '/vendas/filialsupervisor', json_encode($apiEntrada), 'GET');

		echo json_encode($filiais);
		return $filiais;
	}

	if ($operacao == "zoomsupervisor") {

		$supcod = isset($_POST["supcod"])  && $_POST["supcod"] !== "" && $_POST["supcod"] !== "null" ? $_POST["supcod"]  : null;
		$pagina = isset($_POST["pagina"])  && $_POST["pagina"] !== "" && $_POST["pagina"] !== "null" ? $_POST["pagina"]  : 0;
		
		$apiEntrada = 
		array(
			"dadosEntrada" => array(
				array(
					'supcod' => $supcod,
					'pagina' => $pagina,
					'tempaginacao' => true
				)
			)
		);

		$supervisor = chamaAPI(null, '/vendas/supervisor', json_encode($apiEntrada), 'GET');

		echo json_encode($supervisor);
		return $supervisor;
	}

}