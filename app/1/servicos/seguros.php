<?php
// helio 03022023 - criação, array criado manualmente, teste de codigo de cliente no fonte
/*
exemplo request:
{
    "codigoCliente" : 500306143
}
ou null para todos

*/    
$log_datahora_ini = date("dmYHis");
$acao="seguros"; 
$arqlog = defineCaminhoLog()."apilebes_".$acao."_".date("dmY").".log";
$arquivo = fopen($arqlog,"a");
fwrite($arquivo,$log_datahora_ini."$acao"."-ENTRADA->".json_encode($jsonEntrada)."\n");   

    $seguros = array();
    $produtos = array();

    $produto = array("codigoProduto" => 829328, 
                      "nomeProduto" => "JG LENCOL SANTIS BRO",
                      "precoVenda"  =>  179.90,
                      "quantidade"  =>  1,
                      "valorTotal"  =>  179.90
                    );
    array_push($produtos,$produto);
    $produto = array("codigoProduto" => 825061007, 
                      "nomeProduto" => "CALCA FOR FREE 40873",
                      "precoVenda"  =>  109.90,
                      "quantidade"  =>  1,
                      "valorTotal"  =>  109.90
                    );
    array_push($produtos,$produto);
  
    $seguro = array(
        "recID" => 190658895,
        "codigoTipoSeguro" => 1,
        "nomeTipoSeguro" => "Seguro Prestamista",
        "codigoFilial" => 188,
        "codigoCliente" => 500306143,			
        "nomeCliente" => "PEDRO PEREIRA",
        "numeroCertificado" => "0000018820002116",
        "valorSeguro" => 240.12,
        "dataTransacao" => "2023-01-13",
        "dataInicioVigencia" => "2023-02-13",
        "dataFinalVigencia" => "2024-02-12",
        "PDF" => "/lojas/relat-pdf/72307196072_001_16022023_0000000120022789.pdf",
        "produtos" => $produtos
    );
    $recID = false;
    if (isset($jsonEntrada["recID"])) {
        if ($jsonEntrada["recID"] == 190658895) {
           $recID = true;
        }
    } else {
        $recID = true;
    }

    $filial = false;
    if (isset($jsonEntrada["codigoFilial"])) {
        if ($jsonEntrada["codigoFilial"] == 188) {
           $filial = true;
        }
    } else {
        $filial = true;
    }
    if ($filial==true&&$recID==true) {

        if (isset($jsonEntrada["codigoCliente"])) {
            if ($jsonEntrada["codigoCliente"] == 500306143) {
                array_push($seguros,$seguro);
            }
        } else {
            array_push($seguros,$seguro);
        }
    }

    $seguro = array(
        "recID" => 194453225,
        "codigoTipoSeguro" => 1,
        "nomeTipoSeguro" => "Seguro Prestamista",
        "codigoFilial" => 188,
        "codigoCliente" => 300466543,			
        "nomeCliente" => "ANDREA CONCEICAO SANTOS SOARES",
        "numeroCertificado" => "0000000120022771",
        "valorSeguro" => 240.12,
        "dataTransacao" => "2023-01-13",
        "dataInicioVigencia" => "2023-02-13",
        "dataFinalVigencia" => "2024-02-12",
        "PDF" => "/lojas/relat-pdf/01812213069_001_10022023_0000000120022771.pdf",
        "produtos" => $produtos
    );
    $recID = false;
    if (isset($jsonEntrada["recID"])) {
        if ($jsonEntrada["recID"] == 194453225) {
           $recID = true;
        }
    } else {
        $recID = true;
    }

    $filial = false;
    if (isset($jsonEntrada["codigoFilial"])) {
        if ($jsonEntrada["codigoFilial"] == 188) {
           $filial = true;
        }
    } else {
        $filial = true;
    }
    if ($filial==true&&$recID==true) {

        if (isset($jsonEntrada["codigoCliente"])) {
            if ($jsonEntrada["codigoCliente"] == 300466543) {
                array_push($seguros,$seguro);
            }
        } else {
            array_push($seguros,$seguro);
        }
    }

    $produtos = array();
    $produto = array("codigoProduto" => 814449, 
                      "nomeProduto" => "BATERIA MOURA 60AH D",
                      "precoVenda"  =>  529.90,
                      "quantidade"  =>  1,
                      "valorTotal"  =>  529.90
                    );
    array_push($produtos,$produto);

    $seguro = array(
        "recID" => 190690959,
        "codigoTipoSeguro" => 5,
        "nomeTipoSeguro" => "Garantia Estendida",
        "codigoFilial" => 1,
        "codigoCliente" => 15517543,			
        "nomeCliente" => "DONA MANUELA",
        "numeroCertificado" => "781000001841640",
        "valorSeguro" => 2345.22,
        "dataTransacao" => "2023-01-13",
        "dataInicioVigencia" => "2023-02-13",
        "dataFinalVigencia" => "2024-02-12",
        "PDF" => "/lojas/relat-pdf/01812213069_001_10022023_0000000120022771.pdf",
        "produtos" => $produtos

    );
    $recID = false;
    if (isset($jsonEntrada["recID"])) {
        if ($jsonEntrada["recID"] == 190690959) {
           $recID = true;
        }
    } else {
        $recID = true;
    }

    $filial = false;
    if (isset($jsonEntrada["codigoFilial"])) {
        if ($jsonEntrada["codigoFilial"] == 1) {
           $filial = true;
        }
    } else {
        $filial = true;
    }
   
    if ($filial==true&&$recID==true) {

        if (isset($jsonEntrada["codigoCliente"])) {
            if ($jsonEntrada["codigoCliente"] == 15517543) {
                array_push($seguros,$seguro);
            }
        } else {
            array_push($seguros,$seguro);
        }
    }


    $jsonSaida = array("seguros" => $seguros);
/*
    fwrite($arquivo,$identificacao."-SAIDA->".json_encode($jsonSaida)."\n");

fclose($arquivo);
    
*/
?>