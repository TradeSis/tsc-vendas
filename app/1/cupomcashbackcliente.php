<?php
// Inicio
$log_datahora_ini = date("dmYHis");
$acao="cupomcashbackcliente"; 
$arqlog = defineCaminhoLog()."apilebes_clientes_".date("dmY").".log";
$arquivo = fopen($arqlog,"a");
fwrite($arquivo,$log_datahora_ini."$acao"."-ENTRADA->".json_encode($jsonEntrada)."\n");   

$conteudoEntrada = json_encode($jsonEntrada);

    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("vendas/app/1/cupomcashbackcliente",$conteudoEntrada);
    fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");

    $jsonSaida = json_decode($retorno,true);
    
    fwrite($arquivo,$identificacao."-SAIDA->".json_encode($jsonSaida)."\n");

fclose($arquivo);
    
?>
