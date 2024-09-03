<?php
// lucas 22082024 - id 1241 passado programa para progress 
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "verificatoken";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "vendas_VERIFICA" . date("dmY") . ".log", "a");
    }
  }
}
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG
require_once ROOT . "/vendor/autoload.php";
$google2fa = new \PragmaRX\Google2FA\Google2FA();
$dados = array();


$progr = new chamaprogress();
$retorno = $progr->executarprogress("vendas/app/1/token_verifica", json_encode($jsonEntrada));
$dadosretorno = json_decode($retorno, true);
$rowretorno['idToken'] = $dadosretorno["token"][0]["idToken"];
$retornoFormat = array("token" => $rowretorno);
fwrite($arquivo, $identificacao . "-RETORNO->" . json_encode($retornoFormat) . "\n");


$dados = json_decode($retorno, true);
if (isset($dados["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
  $jsonSaida = json_decode(json_encode(array("status" => 401, "retorno" => "Usuario nao Cadastrado")), true);
} else {
  $rows = 0;
  $secret = $dados["token"][0]["secret"];
  $dados = $dados["token"];
  $token = $jsonEntrada['dadosEntrada'][0]["token"];


  if ($google2fa->verifyKey($secret, $token)) {
    $row['senhaCorreta'] = true;
  } else {
    $row['senhaCorreta'] = false;
  }

  //array_push($dados, $row);
  //fwrite($arquivo, $identificacao . "-RETORNO DADOS->" . $dados[1] . "\n");
  $jsonSaida = array("usuarios" => $row);
}


//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG

fclose($arquivo);
