<?php
//lucas 26082024

/* include_once('../conexao.php'); */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../conexao.php";


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "buscar") {

		$supcod = isset($_POST["supcod"])  && $_POST["supcod"] !== "" && $_POST["supcod"] !== "null" ? $_POST["supcod"]  : null;
		$pagina = isset($_POST["pagina"])  && $_POST["pagina"] !== "" && $_POST["pagina"] !== "null" ? $_POST["pagina"]  : 0;
		
		$apiEntrada = 
		array(
			'supcod' => $supcod,
			'pagina' => $pagina
		);
		$supervisor = chamaAPI(null, '/vendas/zoomsupervisor', json_encode($apiEntrada), 'GET');

		echo json_encode($supervisor);
		return $supervisor;
	}

}