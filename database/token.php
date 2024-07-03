<?php
// gabriel 21072023

include_once('../conexao.php');

function buscaToken($idUsuario = null)
{

        $usuario = array();
        $apiEntrada = array(
                'idUsuario' => $idUsuario
        );
        $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'GET');

        // helio 020823 - compatibilidade progress
        return $usuario["usuarios"];
}
function verificaToken($idUsuario, $vtoken)
{

        $usuario = array();
        $token = array(
                'idUsuario' => $idUsuario,
                'token' => $vtoken
        );
        $apiEntrada = array(
                'token' => array($token)
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
                $apiEntrada = array(
                        'idUsuario' => $_POST['idUsuario']
                );
                $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'PUT');
        }

        if ($operacao == "excluir") {
                $apiEntrada = array(
                        'idUsuario' => $_POST['idUsuario']
                );
                $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'DELETE');
        }
        if ($operacao == "ativar") {
                $apiEntrada = array(
                        'idUsuario' => $_POST['idUsuario'],
                        'secret' => $_POST['secret']
                );
                $usuario = chamaAPI(null, '/vendas/token/ativar', json_encode($apiEntrada), 'POST');
        }
        if ($operacao == "buscar") {
                $idUsuario = $_POST['idUsuario'];
                $apiEntrada = array(
                    'idUsuario' => $idUsuario
                );
                $usuario = chamaAPI(null, '/vendas/token', json_encode($apiEntrada), 'GET');
                $response = array('idUsuario' => $usuario['usuarios']['idUsuario']);
                echo json_encode($response);
                return $response;
        }

        header('Location: ../apoio/?tab=apoio&stab=token');




}

?>