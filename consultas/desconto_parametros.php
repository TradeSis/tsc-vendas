<?php
// lucas 120320204 id884 bootstrap local - alterado head
// helio 17022023 (L 22) retirei do form os campos data inicial e final
// helio 17022023 (L 22) retirei do form action o parametro parametros
// gabriel 17022023 15:13

include_once '../header.php';


?>
<!doctype html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body>
    <div class="container-fluid">
        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 card p-0">
                <div class="card-header">
                <h3>Parametros Descontos</h3>
            </div>

            <div class="container">
                <form action="descontos.php" method="POST">
                    <div class="form-group">
                        <label>Filial</label>
                        <input type="number" class="form-control" name="codigoLoja">
                    </div>
                    <div class="card-footer bg-transparent" style="text-align:right">
                        <button type="submit" class="btn btn-sm btn-success">Consultar Descontos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>