<?php
// lucas 220820204 id1241 idUsuario virou idToken 
// lucas 120320204 id884 bootstrap local - alterado head

include_once('../header.php');
include_once('../database/token.php');
require_once ROOT . "/vendor/autoload.php";

use PragmaRX\Google2FA\Google2FA;

$idToken = $_GET['idToken'];

$google2fa = new Google2FA();

$secret = $google2fa->generateSecretKey(); /* gera secret */
$nomeToken = "Token/tslebes";
if (URLROOT == "/tslebes" && $_SERVER['SERVER_ADDR'] == "10.145.0.60") {
    $nomeToken = "Token(HML)/tslebes";
}
if (URLROOT == "/tslebes-dev" && $_SERVER['SERVER_ADDR'] == "10.145.0.60") {
    $nomeToken = "Token/tslebes-dev";
} 

$text = $google2fa->getQRCodeUrl(
    $nomeToken,
    $idToken,
    $secret
);

$image_url = 'https://quickchart.io/qr?size=300x300&text=' . $text;


?>

<!doctype html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container bg-white" style="margin-top:50px;width:600px;">

        <div class="col-sm">
            <spam class="col titulo">Registre a autenticação em 2 fatores</spam>
        </div>
        <div class="container-sm">
            <form action="../database/token.php?operacao=ativar" method="post">
                <p style="text-align:center">
                    <?php echo '<img src="' . $image_url . '" />'; ?>
                </p>
                <input type="text" class="form-control" name="idToken" value="<?php echo $idToken ?>" hidden>
                <input type="text" class="form-control" name="secret" value="<?php echo $secret ?>" hidden>

                <h6 style="text-align:center;color:red;">Atenção: Não saia sem salvar!</h6>
                <div class="card-footer" style="text-align:right">
                    <button type="submit" id="botao" class="btn btn-success"><i
                            class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
                </div>
            </form>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>