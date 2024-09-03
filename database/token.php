<?php
// gabriel 21072023

include_once('../conexao.php');

function buscaToken($idUsuario = null)
{

        $usuario = array();
        $apiEntrada =
        array(
                "dadosEntrada" => array(
                        array(
                                'idToken' => $idUsuario
                        )
                )
        );
        $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'GET');

        // helio 020823 - compatibilidade progress
        return $usuario["usuarios"];
}
function verificaToken($idToken, $vtoken)
{

        $usuario = array();
        $apiEntrada =
        array(
                "dadosEntrada" => array(
                        array(
                                'idToken' => $idToken,
                                'token' => $vtoken
                        )
                )
        );
      
        $usuario = chamaAPI(null, '/vendas/token/verifica', json_encode($apiEntrada), 'POST');

        if (isset($usuario['usuario'])) {
                return $usuario["usuario"][0];
        } else {
                return $usuario;
        }
}


if (isset($_GET['operacao'])) {

        $operacao = $_GET['operacao'];

        if ($operacao == "inserir") {
                $apiEntrada =
                array(
                        "token" => array(
                                array(
                                        'idToken' => $_POST['idToken']
                                )
                        )
                );
                $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'PUT');
        }

        if ($operacao == "excluir") {
                $apiEntrada =
                array(
                        "token" => array(
                                array(
                                        'id_recid' => $_POST['id_recid']
                                )
                        )
                );
                $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'DELETE');
        }
        if ($operacao == "ativar") {
                $apiEntrada =
                array(
                        "token" => array(
                                array(
                                        'idToken' => $_POST['idToken'],
                                        'secret' => $_POST['secret']
                                )
                        )
                );
                $usuario = chamaAPI(null, '/vendas/token/ativar', json_encode($apiEntrada), 'POST');
        }
        if ($operacao == "buscar") {

                $idToken = isset($_POST["idToken"]) && $_POST["idToken"] !== "null"  ? $_POST["idToken"]  : null;

                $apiEntrada =
                array(
                        "dadosEntrada" => array(
                                array(
                                        'idToken' => $idToken
                                )
                        )
                );
                $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'GET');

                echo json_encode($usuario);
                return $usuario;
        }

        header('Location: ../apoio/token.php');
}
