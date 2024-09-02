<?php
// PROGRESS
// ALTERAR E INSERIR


//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "fincotacllib_excluir";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "vendas_excluir_" . date("dmY") . ".log", "a");
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


try {

    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("vendas/app/1/fincotacllib_excluir", json_encode($jsonEntrada));
    fwrite($arquivo, $identificacao . "-RETORNO->" . $retorno . "\n");
    $conteudoSaida = json_decode($retorno, true);
    if (isset($conteudoSaida["conteudoSaida"][0])) { // Conteudo Saida - Caso de erro
        $jsonSaida = $conteudoSaida["conteudoSaida"][0];
    }
} catch (Exception $e) {
    $jsonSaida = array(
        "status" => 500,
        "retorno" => $e->getMessage()
    );
    if ($LOG_NIVEL >= 1) {
        fwrite($arquivo, $identificacao . "-ERRO->" . $e->getMessage() . "\n");
    }
} finally {
    // ACAO EM CASO DE ERRO (CATCH), que mesmo assim precise
}
//TRY-CATCH



//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG



fclose($arquivo);
