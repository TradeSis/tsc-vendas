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

if (isset($usuarios['senhaCorreta'])) {
    if ($usuarios['senhaCorreta'] == false) {
        $mensagem = "Token não cadastrado ou incorreto!";
        header('Location: ../apoio/?tab=apoio&stab=token&mensagem=' . urlencode($mensagem));
    } else {
        $mensagem = "Usuário verificado com sucesso!";
        header('Location: ../apoio/?tab=apoio&stab=token&autenticado=' . urlencode($mensagem));
    }
} else {
    $mensagem = $usuarios['retorno'];
    header('Location: ../apoio/?tab=apoio&stab=token&mensagem=' . urlencode($mensagem));
}

?>
