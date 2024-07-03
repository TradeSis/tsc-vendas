<?php
// helio 17022023 - Criação - é fake
$log_datahora_ini = date("dmYHis");
$acao="consultaMargemDesconto"; 
$arqlog = defineCaminhoLog()."apilebes_".$acao."_".date("dmY").".log";
$arquivo = fopen($arqlog,"a");
fwrite($arquivo,$log_datahora_ini."$acao"."-ENTRADA->".json_encode($jsonEntrada)."\n");   

$codigoFilial = $jsonEntrada["consultaMargemDescontoEntrada"] [0] ["codigoLoja"];

$fake = '{
    "consultaMargemDescontoSaida": {
        "statusRetorno": [
            {
                "status": "200",
                "descricao": "OK"
            }
        ],
        "margemDesconto": [
            {
                "codigoLoja": "'.$codigoFilial.'",
                "linha": "MOVEIS",
                "totalVenda": "612480.37",
                "totalVendaComAcrescimo": "790324.14",
                "margem": "5.00",
                "percDescontoProdutoMax": "5.00",
                "valorDescontoDisponivel": "26412.73",
                "valorDescontoUtilizado": "13103.48",
                "periodoVendaInicial": "2023-01-18",
                "periodoVendaFinal": "2023-02-17"
            },
            {
                "codigoLoja": "'.$codigoFilial.'",
                "linha": "MODA",
                "totalVenda": "302646.18",
                "totalVendaComAcrescimo": "359299.64",
                "margem": "10.00",
                "percDescontoProdutoMax": "20.00",
                "valorDescontoDisponivel": "27912.7",
                "valorDescontoUtilizado": "8017.26",
                "periodoVendaInicial": "2023-01-18",
                "periodoVendaFinal": "2023-02-17"
            }
        ]
    }
}';

$jsonSaida = json_decode($fake,true);

?>

