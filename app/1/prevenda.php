<?php
// gabriel 30042024 criado
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "prevenda";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "vendas_" . date("dmY") . ".log", "a");
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

$prevenda = array();


$progr = new chamaprogress();
$retorno = $progr->executarprogress("vendas/app/1/prevenda", json_encode($jsonEntrada));
fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
$prevenda = json_decode($retorno, true);
if (isset($prevenda["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
    $prevenda = $prevenda["conteudoSaida"][0];
} else {

    if (!isset($prevenda["prevenda"][1]) && ($jsonEntrada['dadosEntrada'][0]['precod'] != null)) {  // Verifica se tem mais de 1 registro
        $prevenda = $prevenda["prevenda"][0]; // Retorno sem array
    } else {
        $prevenda = $prevenda["prevenda"];
    }

}


$jsonSaida = $prevenda;


//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG

fclose($arquivo);

?>