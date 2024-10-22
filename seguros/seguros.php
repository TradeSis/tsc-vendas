<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 15022023 13:55 adicionado codigocliente e filial null, visualizar dados cliente, removido modal
// gabriel 09022023 15:35

include_once '../header.php';
include_once '../database/seguros.php';

if (isset($_GET['parametros'])) {
    $nomeTipoSeguro = $_POST['nomeTipoSeguro'];
    $codigoCliente = $_POST['codigoCliente'];
    $codigoFilial = $_POST['codigoFilial'];
}


if (empty($codigoCliente)) {
    $codigoCliente = null;
} // Se estiver vazio, coloca null
if (empty($codigoFilial)) {
    $codigoFilial = null;
} // Se estiver vazio, coloca null
$recID = null;

$seguros = buscaSeguros($codigoCliente, $codigoFilial, $recID);


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
                        <h3 class="col">Seguros (Progress)</h3>
                    </div>
                    <div class="col-sm" style="text-align:right">
                        <a href="#" onclick="history.back()" role="button" class="btn btn-primary btn-sm">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="table table-responsive">
            <table class="table table-sm table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center">Cliente</th>
                        <th class="text-center">Nome</th>
                        <th class="text-center">Tipo</th>
                        <th class="text-center">Seguro</th>
                        <th class="text-center">Filial</th>
                        <th class="text-center">Certificado</th>
                        <th class="text-center">Valor</th>
                        <th class="text-center">Transação</th>
                        <th class="text-center">Vigência Início</th>
                        <th class="text-center">Vigência Fim</th>
                        <th class="text-center">Dados</th>
                    </tr>
                </thead>

                <?php
                foreach ($seguros as $seguro) {
                ?>
                    <tr>
                        <td class="text-center"><?php echo $seguro['codigoCliente'] ?></td>
                        <td class="text-center"><?php echo $seguro['nomeCliente'] ?></td>
                        <td class="text-center"><?php echo $seguro['codigoTipoSeguro'] ?></td>
                        <td class="text-center"><?php echo $seguro['nomeTipoSeguro'] ?></td>
                        <td class="text-center"><?php echo $seguro['codigoFilial'] ?></td>
                        <td class="text-center"><?php echo $seguro['numeroCertificado'] ?></td>
                        <td class="text-center"><?php echo number_format($seguro['valorSeguro'], 2, ',', '.') ?></td>
                        <td class="text-center"><?php echo date('d/m/Y', strtotime($seguro['dataTransacao'])) ?></td>
                        <td class="text-center"><?php echo date('d/m/Y', strtotime($seguro['dataInicioVigencia'])) ?></td>
                        <td class="text-center"><?php echo date('d/m/Y', strtotime($seguro['dataFinalVigencia'])) ?></td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-sm" href="seguros_visualizar.php?recID=<?php echo $seguro['recID'] ?>" role="button">Visualizar</a>
                        </td>

                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>