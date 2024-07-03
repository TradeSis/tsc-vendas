<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 300323 11:24

include_once '../header.php';
include_once('../database/consultaprodutodisp.php');

if (isset($_GET['parametros'])) {
    $procod = $_POST['procod'];
    $produto = buscaProduDisp($procod);
}

?>

<!doctype html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body class="ts-noScroll">
    <div class="container-fluid">
        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 card p-0">
                <div class="card-header">
                    <h3>Consulta Disponibilidade Produto</h3>
                </div>
                <div class="container">
                    <form action="produtodisp.php?parametros" method="POST">
                        <div class="row">
                            <div class="col-sm-8 mt-3">
                                <input type="number" placeholder="Inserir Cod. do Produto" class="form-control ts-input" name="procod">
                            </div>
                            <div class="col-sm-4 mt-2 mb-2" style="text-align:right">
                                <button type="submit" class="btn btn btn-success">Buscar Produto</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                if (isset($_GET['parametros'])) {
                    ?>
                    <div class="container-fluid mb-2">
                        <div class="row">
                            <div class="col">
                                <label>Código</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $produto['procod'] ?>" readonly>
                                <label>Estoque Filial</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $produto['estoqueFilial'] ?>"
                                    readonly>
                                <label>Pedidos de compra</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $produto['pedCompra'] ?>" readonly>
                            </div>
                            <div class="col">
                                <label>Nome</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $produto['pronom'] ?>" readonly>
                                <label>Disponível Dep Seguro</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $produto['disponivelDep'] ?>"
                                    readonly>
                                <label>Previsão</label>
                                <input type="text" class="form-control ts-input" value="<?php echo $produto['previsao'] ?>" readonly>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>