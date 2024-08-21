<?php
//Lucas 20082024 criado
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
            <div class="col-10">
                <h2 class="ts-tituloPrincipal">Supervisor</h2>
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
        </div>

        <!--------- INSERIR --------->
        <div class="modal" id="inserirModal" tabindex="-1" aria-labelledby="inserirModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Inserir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form method="post" id="inserirForm">
                            <div class="row">
                                <div class="col-2">
                                    <label class="form-label ts-label">Cod</label>
                                    <input type="text" class="form-control ts-input" name="supcod" required>
                                </div>
                                <div class="col">
                                    <label class="form-label ts-label">Nome</label>
                                    <input type="text" class="form-control ts-input" name="supnom" required>
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!--------- EXCLUIR --------->
        <div class="modal" id="excluirModal" tabindex="-1" aria-labelledby="excluirModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Excluir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form method="post" id="excluirForm">
                            <div class="row">
                                <div class="col-2">
                                    <label class="form-label ts-label">Cod</label>
                                    <input type="text" class="form-control ts-input" name="supcod" id="exc_supcod" readonly>
                                    <input type="hidden" class="form-control ts-input" name="id_recid" id="exc_id_recid" readonly>
                                </div>
                                <div class="col">
                                    <label class="form-label ts-label">Nome</label>
                                    <input type="text" class="form-control ts-input" name="supnom" id="exc_supnom" readonly>
                                </div>
                            </div>

                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>Cod</th>
                        <th>Nome</th>
                        <th></th>
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
                url: '../database/supervisor.php?operacao=buscar',
                data: {},
                success: function(msg) {
                    //alert(msg)
                    var json = JSON.parse(msg);
                    //alert(JSON.stringify(json));
                    var contadorItem = 0;
                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        contadorItem += 1;
                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.supcod + "</td>";
                        linha = linha + "<td>" + object.supnom + "</td>";

                        linha = linha + "<td class='text-end pe-2'><a class=' btn btn-warning btn-sm me-1' href='supervisor_alterar.php?supcod=" + object.supcod + "' role='button'><i class='bi bi-pencil-square'></i></a>";
                        linha = linha + "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#excluirModal'";
                        linha = linha + " data-id_recid='" + object.id_recid + "' ";
                        linha = linha + " data-supcod='" + object.supcod + "' ";
                        linha = linha + " data-supnom='" + object.supnom + "' ";
                        linha = linha + " '><i class='bi bi-trash'></i></button></td>";

                        linha = linha + "</tr>";

                    }

                    $("#dados").html(linha);

                    var texto = $("#textocontador");
                    texto.html('Total: ' + contadorItem);

                }
            });


        }

        // MODAL EXCLUIR
        $(document).on('click', 'button[data-bs-target="#excluirModal"]', function() {
            var id_recid = $(this).attr("data-id_recid");
            var supcod = $(this).attr("data-supcod");
            var supnom = $(this).attr("data-supnom");

            $('#exc_id_recid').val(id_recid);
            $('#exc_supcod').val(supcod);
            $('#exc_supnom').val(supnom);

            $('#excluirModal').modal('show');

        });


        $("#inserirForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/supervisor.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage
            });
        });

        $("#excluirForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/supervisor.php?operacao=excluir",
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