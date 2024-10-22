<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 16022023 15:34 adicionado botão de certificado  
// gabriel 15022023 14:00


include_once '../header.php';
include_once '../database/seguros.php';

$recID = $_GET['recID'];

if (empty($codigoCliente)) {
    $codigoCliente = null;
} // Se estiver vazio, coloca null
if (empty($codigoFilial)) {
    $codigoFilial = null;
} // Se estiver vazio, coloca null

$seguros = buscaSeguros($codigoCliente, $codigoFilial, $recID);
$seguros = $seguros[0];
$produtos = $seguros["produtos"];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="col">Cod.
                            <?php echo $recID ?> - <?php echo $seguros['nomeCliente'] ?>
                        </h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>


            <div class="container-fluid">
                <h5>Dados Cliente</h5>
                <div class="row">
                    <div class="col">
                        <label>Cliente</label>
                        <input type="text" class="form-control" value="<?php echo $seguros['codigoCliente'] ?>" readonly>
                        <label>Código</label>
                        <input type="text" class="form-control" value="<?php echo $seguros['codigoTipoSeguro'] ?>" readonly>
                        <label>Filial</label>
                        <input type="text" class="form-control" value="<?php echo $seguros['codigoFilial'] ?>" readonly>
                        <label>Valor</label>
                        <input type="text" class="form-control" value="<?php echo number_format($seguros['valorSeguro'], 2, ',', '.') ?>" readonly>
                        <label>Vigência Início</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($seguros['dataInicioVigencia'])) ?>" readonly>
                    </div>
                    <div class="col">
                        <label>Nome</label>
                        <input type="text" class="form-control" value="<?php echo $seguros['nomeCliente'] ?>" readonly>
                        <label>Tipo Seguro</label>
                        <input type="text" class="form-control" value="<?php echo $seguros['nomeTipoSeguro'] ?>" readonly>
                        <label>Certificado</label>
                        <input type="text" class="form-control" value="<?php echo $seguros['numeroCertificado'] ?>" readonly>
                        <label>Transação</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($seguros['dataTransacao'])) ?>" readonly>
                        <label>Vigência Fim</label>
                        <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($seguros['dataFinalVigencia'])) ?>" readonly>
                    </div>
                </div>
                <div class="mt-2" style="text-align:right">
                    <a href="seguros_pdf.php?PDF=<?php echo $seguros['PDF'] ?>" role="button" class="btn btn-primary btn-sm">Certificado</a>
                </div>
                <hr>
                <h5>Produtos</h5>
                <div class="table table-responsive">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Código</th>
                                <th class="text-center">Nome</th>
                                <th class="text-center">Preço</th>
                                <th class="text-center">Quantidade</th>
                                <th class="text-center">Valor Total</th>
                            </tr>
                        </thead>
                        <?php foreach ($produtos as $produto) { ?>
                            <tr>
                                <td class="text-center"><?php echo $produto['codigoProduto'] ?></td>
                                <td class="text-center"><?php echo $produto['nomeProduto'] ?></td>
                                <td class="text-center"><?php echo number_format($produto['precoVenda'], 2, ',', '.') ?></td>
                                <td class="text-center"><?php echo $produto['quantidade'] ?></td>
                                <td class="text-center"><?php echo number_format($produto['valorTotal'], 2, ',', '.') ?></td>
                            </tr>
                        <?php } ?>

                    </table>
                </div>
            </div>
        </div>
    </div>


<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>