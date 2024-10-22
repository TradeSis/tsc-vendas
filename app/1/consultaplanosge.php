<?php
// lucas 21102024 criado
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "consultaplanosge";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "vendas_consultaplanosge_" . date("dmY") . ".log", "a");
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



if(isset($parametro) && $parametro != ""){
    $conteudoEntrada = json_encode(array(
        "dadosEntrada" => array(array(
                "procod" =>  $parametro
            ))));
}elseif(isset($jsonEntrada) && $jsonEntrada != ""){
    $conteudoEntrada = json_encode($jsonEntrada);
}else{
    $conteudoEntrada = json_encode(array(
        "dadosEntrada" => array(array(
                "procod" =>  null
            ))));
}

$dados = array();

$progr = new chamaprogress();
$retorno = $progr->executarprogress("vendas/app/1/consultaplanosge", $conteudoEntrada);
fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
$dados = json_decode($retorno,true);
  if (isset($dados["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
      $dados = $dados["conteudoSaida"][0];
  } else {
    $dados = $dados["JSON"];
  }


$jsonSaida = $dados;


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

?>