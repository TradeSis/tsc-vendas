<?php
include_once('../database/token.php');

$user = $_POST['user'];
$token = $_POST['token'];

if ($user === "") {
    $user = null;
}
if ($token === "") {
    $token = null;
}
                       
$usuarios = verificaToken($user, $token);

if (isset($usuarios["usuarios"][1]['senhaCorreta'])) {
    if ($usuarios["usuarios"][1]['senhaCorreta'] == false) {
        
        $mensagem = "Token não cadastrado ou incorreto!";
        header('Location: ../apoio/token.php?&mensagem=' . urlencode($mensagem));
    } else {
        $mensagem = "Usuário verificado com sucesso!";
        header('Location: ../apoio/token.php?&autenticado=' . urlencode($mensagem));
    }
} else {
    $mensagem = $usuarios['retorno'];
    header('Location: ../apoio/token.php?&mensagem=' . urlencode($mensagem));
}

?>
