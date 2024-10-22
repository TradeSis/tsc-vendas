<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 15022023 14:54 adicionado ?parametros na ação
// gabriel 10022023 16:23

include_once '../header.php';

?>
<!DOCTYPE html>
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
            <div class="col-md-6 card  p-0">
                <div class="card-header">
                    <h3>Parametros Seguro</h3>
                </div>
                <div class="container">
                    <form action="seguros.php?parametros" method="post">
                        <div class="form-group">
                            <label>Seguro</label>
                            <select class="form-control" name="nomeTipoSeguro">
                                <option value=""></option>
                                <option value="Seguro Prestamista">Seguro Prestamista</option>
                                <option value="Garantia Estendida">Garantia Estendida</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Filial</label>
                            <input type="number" class="form-control" name="codigoFilial">
                        </div>
                        <div class="form-group">
                            <label>Cliente</label>
                            <input type="number" class="form-control" name="codigoCliente">
                        </div>
                        <div class="card-footer bg-transparent" style="text-align:right">
                            <button type="submit" class="btn btn-sm btn-success">Verificar Seguros</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>