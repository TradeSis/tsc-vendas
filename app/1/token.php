<?php
// gabriel 21072023


$conexao = conectaMysql();
$usuarios = array();

$sql = "SELECT * FROM token ";
if (isset($jsonEntrada["idUsuario"])) {
  $sql = $sql . " where token.idUsuario = '" . $jsonEntrada["idUsuario"] . "'";
}
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  unset($row['secret']);

  array_push($usuarios, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idUsuario"]) && $rows==1) {
  $usuarios = $usuarios[0];
}

$jsonSaida = array("usuarios" => $usuarios);



?>