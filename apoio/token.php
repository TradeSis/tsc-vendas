<?php
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 21072023

include_once('../header.php');
include_once('../database/token.php');

$usuarios = buscaToken();

?>

<!doctype html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body class="bg-transparent">
    <div class="container" style="margin-top:10px;margin-bottom:100px;">
        <div class="row mt-4">
            <div class="col-sm">
                <p class="tituloTabela">Usuarios Token</p>
            </div>
            <div class="col-sm-2" style="text-align:right">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#testarModal">Testar</button>
            </div>
            <div class="col-sm-2" style="text-align:right">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirTokenModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
        </div>
        <div class="card shadow">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Usuario</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <?php
                foreach ($usuarios as $usuario) {
                    ?>
                    <tr>
                        <td class="text-center">
                            <?php echo $usuario['idUsuario'] ?>
                        </td>
                        <td class="text-center">
                            <a class="btn btn-success btn-sm"
                                href="token_ativar.php?idUsuario=<?php echo $usuario['idUsuario'] ?>" role="button">Gerar
                                QR-CODE</a>
                            <button type="button" class="btn btn-danger btn-sm"  data-bs-toggle="modal" data-bs-target="#excluirTokenModal" data-idUsuario=<?php echo $usuario['idUsuario'] ?>>Excluir</button>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <!--------- INSERIR --------->
    <div class="modal fade" id="inserirTokenModal" tabindex="-1" aria-labelledby="inserirTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inserir Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form method="post" id="form-inserirToken">
                        <div class="row">
                            <div class="col-md">
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">Nome Usuário</label>
                                        <input type="text" class="form-control ts-input" name="idUsuario">
                                    </div>
                                </div><!--fim row 1-->
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btn-formInserir">Cadastrar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!--------- EXCLUIR --------->
    <div class="modal fade" id="excluirTokenModal" tabindex="-1" aria-labelledby="excluirTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Excluir Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form method="post" id="form-excluirToken">
                        <div class="row">
                            <div class="col-md">
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <label class="form-label ts-label">Nome Usuário</label>
                                        <input type="text" class="form-control ts-input" name="idUsuario"  id="idUsuario">
                                    </div>
                                </div><!--fim row 1-->
                            </div>
                        </div>
                </div><!--body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning" id="btn-formInserir">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>


    <!--------- VERIFICA TOKEN --------->
    <div class="modal fade" id="testarModal" tabindex="-1" aria-labelledby="testarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verificar Token</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container">
                    <div class="row justify-content-center">
                        <div>
                            <div class="card-body px-lg-4 py-lg-6">
                                <?php
                                if (isset($_GET['mensagem'])) {
                                    ?>
                                <div class="alert alert-dark" role="alert">
                                    <?php echo $_GET['mensagem'] ?>
                                </div>
                                <?php } ?>
                                <?php
                                if (isset($_GET['autenticado'])) {
                                    ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $_GET['autenticado'] ?>
                                </div>
                                <?php } ?>
                                <form role="form" action="verifica_token.php" method="post">
                                    <div class="form-group mb-3">

                                        <div class="input-group input-group-alternative">
                                            <input class="form-control" placeholder="Usuário" type="text" name="user"
                                                autocomplete="off" autofocus="on">
                                        </div>
                                        <div class="input-group input-group-alternative mt-2">
                                            <input class="form-control" placeholder="Token" type="text" name="token"
                                                autocomplete="off" autofocus="on">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary my-4">Verificar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).on('click', 'button[data-bs-target="#excluirTokenModal"]', function () {
            var idUsuario = $(this).attr("data-idUsuario");
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/vendas/database/token.php?operacao=buscar',
                data: {
                    idUsuario: idUsuario
                },
                success: function (data) {
                    $('#idUsuario').val(data.idUsuario);
                    $('#excluirTokenModal').modal('show');
                }
            });
        });


        function checkURLParams() {
        const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('mensagem') || urlParams.has('autenticado')) {
                $('#testarModal').modal('show');
            }
        }
        window.onload = checkURLParams;
        

        $(document).ready(function() {
            $("#form-inserirToken").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/token.php?operacao=inserir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            $("#form-excluirToken").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/token.php?operacao=excluir",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

            function refreshPage() {
                window.location.reload();
            }

        });
    </script>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>