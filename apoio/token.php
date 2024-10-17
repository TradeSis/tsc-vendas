<?php
// lucas 22082024 - passagem para progress
// lucas 120320204 id884 bootstrap local - alterado head
// gabriel 21072023
include_once(__DIR__ . '/../header.php');

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

    <div class="container-fluid">

        <div class="row ">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!--<BR> BOTOES AUXILIARES -->
        </div>

        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">
            <div class="col-8">
                <h2 class="ts-tituloPrincipal"></h2>
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#testarModal">Testar</button>
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirTokenModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
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
                                    <div class="row mt-2">
                                        <div class="col-md">
                                            <label class="form-label ts-label">Nome Usuário</label>
                                            <input type="text" class="form-control ts-input" name="idToken">
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
                                    <div class="row mt-2">
                                        <div class="col-md">
                                            <label class="form-label ts-label">Nome Usuário</label>
                                            <input type="text" class="form-control ts-input" name="idToken" id="exc_idToken">
                                            <input type="hidden" class="form-control ts-input" name="id_recid" id="exc_id_recid">
                                        </div>
                                    </div><!--fim row 1-->
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="btn-formInserir">Excluir</button>
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
                                                    autocomplete="off" autofocus="on" required>
                                            </div>
                                            <div class="input-group input-group-alternative mt-2">
                                                <input class="form-control" placeholder="Token" type="text" name="token"
                                                    autocomplete="off" autofocus="on" required>
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

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>Usuário</th>
                        <th>Ação</th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>

        <h6 class="fixed-bottom" id="textocontador" style="color: #13216A;"></h6>


    </div><!--container-fluid-->

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        $(document).ready(function() {
            var texto = $("#textocontador");
            texto.html('total: ' + 0);
        });
        buscar()

        function buscar() {

            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/token.php?operacao=buscar',
                data: {},
                success: function(msg) {

                    var json = JSON.parse(msg);
                    //alert(JSON.stringify(json));
                    var contadorItem = 0;
                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        contadorItem += 1;
                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.idToken + "</td>";

                        linha = linha + "<td>";
                        linha = linha + "<a class='btn btn-success btn-sm me-2' href='token_ativar.php?idToken=" + object.idToken + "' role='button'>Gerar QR-CODE</a>";
                        linha = linha + "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#excluirTokenModal' data-idToken='" + object.idToken + "' data-id_recid='" + object.id_recid + "'><i class='bi bi-trash'></i></button> ";
                        linha = linha + "</td>";

                        linha = linha + "</tr>";

                    }

                    $("#dados").html(linha);

                    var texto = $("#textocontador");
                    texto.html('Total: ' + contadorItem);

                }
            });


        }

        $(document).on('click', 'button[data-bs-target="#excluirTokenModal"]', function() {
            var idToken = $(this).attr("data-idToken");
            var id_recid = $(this).attr("data-id_recid");

            $('#exc_idToken').val(idToken);
            $('#exc_id_recid').val(id_recid);
            $('#excluirTokenModal').modal('show');

        });

        function checkURLParams() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('mensagem') || urlParams.has('autenticado')) {
                $('#testarModal').modal('show');
            }
        }
        window.onload = checkURLParams;

        $("#form-inserirToken").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/token.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage
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
                success: refreshPage
            });
        });

        function refreshPage() {
            window.location.reload();
        }
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>
