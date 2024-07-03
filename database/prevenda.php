<?php
//Lucas 26122023 criado
include_once __DIR__ . "/../conexao.php";

function buscarProduto($procod=null,$buscaProduto=null,$etbcod="188")
{
	$produtos = array();
	$apiEntrada = 
	array("dadosEntrada" => array(
		array('procod' => $procod,
			  'buscaProduto' => $buscaProduto,
			  'etbcod' => $etbcod)
	));
	$produtos = chamaAPI(null, '/vendas/produtos', json_encode($apiEntrada), 'GET');
	return $produtos;
}

function buscarPrevenda($precod=null,$etbcod="188")
{
	$produtos = array();
	$apiEntrada = 
	array("dadosEntrada" => array(
		array('precod' => $precod,
			  'etbcod' => $etbcod)
	));
	$produtos = chamaAPI(null, '/vendas/prevenda', json_encode($apiEntrada), 'GET');
	return $produtos;
}
   
function buscarPrevenprod($precod=null,$etbcod="188")
{
	$produtos = array();
	$apiEntrada = 
	array("dadosEntrada" => array(
		array('precod' => $precod,
			  'etbcod' => $etbcod)
	));
	$produtos = chamaAPI(null, '/vendas/prevenprod', json_encode($apiEntrada), 'GET');
	return $produtos;
}
   



if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "buscarProduto") {

		$procod = isset($_POST["procod"])  && $_POST["procod"] !== "" && $_POST["procod"] !== "null" ? $_POST["procod"]  : null;
		$buscaProduto = isset($_POST["buscaProduto"])  && $_POST["buscaProduto"] !== "" && $_POST["buscaProduto"] !== "null" ? $_POST["buscaProduto"]  : null;
		$pagina = isset($_POST["pagina"])  && $_POST["pagina"] !== "" && $_POST["pagina"] !== "null" ? $_POST["pagina"]  : 0;
		$etbcod = isset($_POST["etbcod"])  && $_POST["etbcod"] !== "" && $_POST["etbcod"] !== "null" ? $_POST["etbcod"]  : null;


		$apiEntrada = 
		array("dadosEntrada" => array(
			array('procod' => $procod,
				  'buscaProduto' => $buscaProduto,
				  'etbcod' => $etbcod,
				  'pagina' => $pagina)
		));

		$produtos = chamaAPI(null, '/vendas/produtos', json_encode($apiEntrada), 'GET');

		echo json_encode($produtos);
		return $produtos;
	}

	if ($operacao == "buscarPrevenda") {

		$precod = isset($_POST["precod"])  && $_POST["precod"] !== "" && $_POST["precod"] !== "null" ? $_POST["precod"]  : null;
		$etbcod = isset($_POST["etbcod"])  && $_POST["etbcod"] !== "" && $_POST["etbcod"] !== "null" ? $_POST["etbcod"]  : null;
		$pagina = isset($_POST["pagina"])  && $_POST["pagina"] !== "" && $_POST["pagina"] !== "null" ? $_POST["pagina"]  : 0;

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('precod' => $precod,
				  'etbcod' => $etbcod,
				  'pagina' => $pagina)
		));

		$prevenda = chamaAPI(null, '/vendas/prevenda', json_encode($apiEntrada), 'GET');

		echo json_encode($prevenda);
		return $prevenda;
	}

	if ($operacao == "inserir") {

		$clicod = $_POST["clicod"];
		$etbcod = $_POST["codigoFilial"];
		$vencod = $_POST["vencod"];

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('clicod' => $clicod,
				  'etbcod' => $etbcod,
				  'vencod' => $vencod)
		));

		$prevenda = chamaAPI(null, '/vendas/prevenda', json_encode($apiEntrada), 'PUT');

		header('Location: ../prevenda/pedido.php?precod=' . $prevenda['precod']);
	}

	if ($operacao == "salvar") {

		$precod = $_POST["precod"];
		$etbcod = $_POST["etbcod"];
		$procod = isset($_POST["procod"])  && $_POST["procod"] !== "" && $_POST["procod"] !== "null" ? $_POST["procod"]  : null;
		$movpc = isset($_POST["movpc"])  && $_POST["movpc"] !== "" && $_POST["movpc"] !== "null" ? $_POST["movpc"]  : null;
		$movqtm = isset($_POST["movqtm"])  && $_POST["movqtm"] !== "" && $_POST["movqtm"] !== "null" ? $_POST["movqtm"]  : null;
		$movtot = isset($_POST["movtot"])  && $_POST["movtot"] !== "" && $_POST["movtot"] !== "null" ? $_POST["movtot"]  : null;
		$precoori = isset($_POST["precoori"])  && $_POST["precoori"] !== "" && $_POST["precoori"] !== "null" ? $_POST["precoori"]  : null;
		$vencod = $_POST["vencod"];

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('precod' => $precod,
				  'etbcod' => $etbcod,
				  'procod' => $procod,
				  'movpc'  => $movpc,
				  'movqtm' => $movqtm,
				  'movtot' => $movtot,
				  'precoori' => $precoori,
				  'vencod' => $vencod)
		));

		$prevenda = chamaAPI(null, '/vendas/prevenprod', json_encode($apiEntrada), 'PUT');

		echo json_encode($prevenda);
		return $prevenda;
	}

	if ($operacao == "finaliza") {

		$precod = $_POST["precod"];
		$etbcod = $_POST["etbcod"];

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('precod' => $precod,
				  'etbcod' => $etbcod)
		));

		$prevenda = chamaAPI(null, '/vendas/prevenda', json_encode($apiEntrada), 'POST');

		echo json_encode($prevenda);
		return $prevenda;
	}


	//header('Location: ../geral/cidades.php');
}
