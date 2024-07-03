<?php
// gabriel 21072023
$log_datahora_ini = date("dmYHis");
$acao = "verificatoken";
$arqlog = defineCaminhoLog() . "apilebes_" . $acao . "_" . date("dmY") . ".log";
$arquivo = fopen($arqlog, "a");
$identificacao = $log_datahora_ini . $acao;
fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n"); 
require_once ROOT . "/vendor/autoload.php";
$google2fa = new \PragmaRX\Google2FA\Google2FA();
$conexao = conectaMysql();
$usuarios = array();

if (!isset($jsonEntrada["token"][0]["idUsuario"]) || !isset($jsonEntrada["token"][0]["token"])) {
  $jsonSaida = json_decode(json_encode(array("status" => 400, "retorno" => "Dados nao informados")), true);

} else {
  $idUsuario = $jsonEntrada["token"][0]["idUsuario"];
  $sql = "SELECT * FROM token WHERE token.idUsuario = '" . $idUsuario . "'";
  $buscar = mysqli_query($conexao, $sql);

  if (mysqli_num_rows($buscar) === 0) {
    $jsonSaida = json_decode(json_encode(array("status" => 401, "retorno" => "Usuario nao Cadastrado")), true);
  } else {
    $rows = 0; 
    while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
      if ($row['secret'] !== null) {
        $token = $jsonEntrada["token"][0]["token"];

        if ($google2fa->verifyKey($row['secret'], $token)) {
          $row['senhaCorreta'] = true;
        } else {
          $row['senhaCorreta'] = false;
        }
      }
      unset($row['secret']);
      array_push($usuarios, $row);
      $rows++; 
    }

//    if (isset($jsonEntrada["token"][0]["idUsuario"]) && $rows == 1) {
//      $usuarios = $usuarios[0];
//    }

    $jsonSaida = array("usuario" => $usuarios);
  }
}
fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n"); 
fclose($arquivo);
?>