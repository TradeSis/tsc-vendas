<?php
$log_datahora_ini = date("dmYHis");
$acao="consultaprodutodisp"; 
$arqlog = defineCaminhoLog()."apilebes_".$acao."_".date("dmY").".log";
$arquivo = fopen($arqlog,"a");
fwrite($arquivo,$log_datahora_ini."$acao"."-PARAMETRO->".$parametro."\n");   

  $codigoProduto = htmlspecialchars($parametro);
  $regs = 0;
  $produtos = array();

  $produto = array(     "procod" => 211,
                        "pronom" => "FOGAO LENHA 1",
                        "estoqueFilial" => "0",
                        "disponivelDep" => "104",
                        "pedCompra" => "0",
                        "previsao" => null);

  if (!empty($codigoProduto)) {
    if ($codigoProduto == 210) {
        array_push($produtos,$produto);
        $regs .= 1;        
    }
  } else {
      array_push($produtos,$produto);
      $regs .= 1;      
  }

if ($regs==0) {
  $jsonSaida = array(
    "status" => 400,
    "retorno" => "produto não encontrado"
  );

} else { 
  if ($regs==1) {
    $jsonSaida = array("produtos" => $produtos[0]);
  } else {
    $jsonSaida = array("produtos" => $produtos);
  }
}

?>

