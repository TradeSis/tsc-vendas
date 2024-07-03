<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 290323 14:44

include_once '../header.php';
include_once '../database/cupomcashbackcliente.php';

if (isset($_GET['parametros'])) {
    $codigoCliente = $_POST['codigoCliente'];
    $cpfCNPJ = $_POST['cpfCNPJ'];
    $situacao = $_POST['situacao'];
}

if (empty($cpfCNPJ)) {
    $cpfCNPJ = null;
} // Se estiver vazio, coloca null


$cupomcashback = buscaCashback($codigoCliente, $cpfCNPJ, $situacao);
$cliente = $cupomcashback["cliente"][0];
$cupons = $cupomcashback["cupom"];

?>

<!doctype html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-header border-1">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="col">Cupons Cashback cliente</h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>


            <div class="container-fluid">
                <h4>Dados Cliente:</h4>
                <div class="row">
                    <div class="col">
                        <label>Código Cliente</label>
                        <input type="text" class="form-control"
                            value=" <?php echo $cliente['codigoCliente'] ?> - <?php echo $cliente['nomeCliente'] ?>"
                            readonly>

                    </div>
                    <div class="col">
                        <label>CPF/CNPJ</label>
                        <input type="text" class="form-control" value="<?php echo $cliente['cpfCNPJ'] ?>" readonly>
                    </div>
                </div>
                <hr>
                <h4>Cupons:</h4>
                <div class="table table-responsive">
                    <table class="table table-sm table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Cliente</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">Dt Geração</th>
                                <th class="text-center">Dt Validade</th>
                                <th class="text-center">Valor</th>
                                <th class="text-center">Porcentagem</th>
                                <th class="text-center">Dt Utilização</th>
                            </tr>
                        </thead>
                        <?php foreach ($cupons as $cupom) { ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $cupom['codigoCliente'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $cupom['idCupom'] ?>
                                </td>
                                <td class="text-center">
                                    <?php echo date('d/m/Y', strtotime($cupom['dataGeracao'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php echo date('d/m/Y', strtotime($cupom['dataValidade'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                    echo number_format($cupom['valorCupom'], 2, ',', '.')
                                        ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $cupom['percCupom'] ?>%
                                </td>
                                <td class="text-center">
                                    <?php if ($cupom['dataUtilizacao'] !== null) {
                                        echo date('d/m/Y', strtotime($cupom['dataUtilizacao']));
                                    } ?>
                                </td>
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