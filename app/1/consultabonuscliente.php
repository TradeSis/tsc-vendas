<?php
// Inicio
$log_datahora_ini = date("dmYHis");
$acao="consultabonuscliente"; 
$arqlog = defineCaminhoLog()."apilebes_".$acao."_".date("dmY").".log";
$arquivo = fopen($arqlog,"a");
fwrite($arquivo,$log_datahora_ini."$acao"."-ENTRADA->".json_encode($jsonEntrada)."\n");   

$JSON = '{
    "conteudoSaida": {
        "cliente": [
            {
                "codigoCliente": 1513,
                "cpfCNPJ": "51555330150",
                "nomeCliente": "CLIENTE TESTE TI LEBES"
            }
        ],
        "bonus": [
            {
                "bonusUtil": "16.0",
                "bonusDisp": "0",
                "aniversario": "nao"
            }
        ]
    }
}';

$jsonSaida = json_decode($JSON,TRUE);




?>