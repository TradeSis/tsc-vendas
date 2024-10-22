<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 16022023 15:34 - alterado para somente receber o pdf
// gabriel 16022023 14:17


include_once '../header.php';


$PDF = $_GET['PDF'];


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm">
                        <h3 class="col">Certificado</h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
            <div class="ExternalFiles full-width">
                <iframe class="container-fluid full-width" id="myIframe" src="<?php echo $PDF ?>" frameborder="0" scrolling="yes" height="550"></iframe>
            </div>
        </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>