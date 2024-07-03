<?php
// gabriel 21072023


$conexao = conectaMysql();

if (isset($jsonEntrada['idUsuario'])) {
    $idUsuario = $jsonEntrada['idUsuario'];
    $secret = $jsonEntrada['secret'];
    $sql = "UPDATE token SET secret='$secret' WHERE idUsuario = '$idUsuario'";
    if ($atualizar = mysqli_query($conexao, $sql)) {
        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok"
        );
    } else {
        $jsonSaida = array(
            "status" => 500,
            "retorno" => "erro no mysql"
        );
    }
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );

}

?>