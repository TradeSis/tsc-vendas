<?php
// gabriel 22022023 16:00

include_once('../conexao.php');


//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
	$identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "cliente";
	$arquivo = fopen(defineCaminhoLog() . "cliente_" . date("dmY") . ".log", "a");
}
//LOG

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "cadastrar") {

		$apiEntrada = 
		array("dadosEntrada" => array(
			array('codigoFilial' => $_POST['codigoFilial'],
			'cpfCnpj' => $_POST['cpfCnpj'],
			'nomeCliente' => $_POST['nomeCliente'],
			'dataNascimento' => $_POST['dataNascimento'],
			'telefone' => $_POST['telefone'])
		));

        setcookie('codigoFilial', $_POST['codigoFilial'], strtotime("+1 year"), "/", "", false, true );

		//LOG
		fwrite($arquivo, $identificacao . "_cadastro-ENTRADA->" . json_encode($apiEntrada) . "\n");
		//LOG

        $cliente = chamaAPI("http://localhost/bsweb/api", '/lojas/cliente', json_encode($apiEntrada), 'PUT');

		//LOG
		fwrite($arquivo, $identificacao . "_cadastro-SAIDA->" . json_encode($cliente) . "\n");
		//LOG

		if (isset($cliente["cliente"])) {
			$codigoCliente = $cliente["cliente"][0]["codigoCliente"];
			$response = array(
				"status" => 200,
				"retorno" => "Cliente " . $_POST['cpfCnpj'] . " " . $_POST['nomeCliente'] . " cadastrado no codigo " . $codigoCliente,
				"clien" => $codigoCliente
			);
            header('Location: ../prevenda/pedido.php?clien=' . $response['clien']);
		} else {
			if($cliente['status'] == 500){
				$response = array(
					"status" => 500,
					"retorno" => "Dados Invalidos"
				);
			} else {
				$response = $cliente;
			}
            header('Location: ../prevenda/cliente_retorno.php?retorno=' . $response['retorno']);
		} 

	}
	
	if ($operacao == "busca") {
		
		$apiEntrada = 
		array("dadosEntrada" => array(
			array('codigoFilial' => $_POST['codigoFilial'],
			'codigoCliente' => null,
			'cpfCnpj' => $_POST['cpfCnpj'],)
		));
		
		setcookie('codigoFilial', $_POST['codigoFilial'], strtotime("+1 year"), "/", "", false, true );

		//LOG
		fwrite($arquivo, $identificacao . "_cadastro-ENTRADA->" . json_encode($apiEntrada) . "\n");
		//LOG
		
		$cliente = chamaAPI("http://localhost/bsweb/api", '/lojas/cliente', json_encode($apiEntrada), 'GET');

		//LOG
		fwrite($arquivo, $identificacao . "_cadastro-SAIDA->" . json_encode($cliente) . "\n");
		//LOG

		if (isset($cliente["cliente"])) {
			$codigoCliente = $cliente["cliente"][0]["codigoCliente"];
			$cpfCnpj = $cliente["cliente"][0]["cpfCnpj"];
			$response = array(
				"status" => 200,
				"retorno" => "Cliente CPF " . $cpfCnpj . " possui cadastro, codigo: " . $codigoCliente,
				"clien" => $codigoCliente
			);
		} else {
			$response = $cliente;
		}
		
		echo json_encode($response);
		return $response;

	}
}




?>