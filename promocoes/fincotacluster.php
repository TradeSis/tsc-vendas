<?php
//Lucas 14082024 criado
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
                <h2 class="ts-tituloPrincipal">Clusters de Planos</h2>
            </div>

            <div class="col-2">
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    <input type="file" id="arquivo" class="custom-file-upload" name="file[]" style="color:#567381; display:none" accept="text/csv" multiple>
                    <label for="arquivo">
                        <a class="btn btn-primary">
                            <i class="bi bi-file-earmark-arrow-down-fill" style="color:#fff"></i>&#32;<h7 style="color: #fff;">Carga Filial</h7>
                        </a>
                    </label>
                </form>
            </div>

            <div class="col-2 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>

        </div>

        <!--------- CARGA FILIAL --------->
        <div class="modal" id="cargaFilialModal" tabindex="-1" aria-labelledby="cargaFilialModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="refreshPage()"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="row mt-2">
                            <div class="col text-center">
                                <div class="alert alertMesg" role="alert" id="textomensagem"></div>
                            </div>
                        </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="refreshPage()">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--------- INSERIR --------->
        <div class="modal" id="inserirModal" tabindex="-1" aria-labelledby="inserirModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Inserir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form method="post" id="inserirForm">
                            <div class="row mt-2">
                                <div class="col-2">
                                    <label class="form-label ts-label">CLS</label>
                                    <input type="text" class="form-control ts-input" name="fcccod" required>
                                </div>
                                <div class="col">
                                    <label class="form-label ts-label">Cluster Planos</label>
                                    <input type="text" class="form-control ts-input" name="fccnom" required>
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

        <!--------- ALTERAR --------->
        <div class="modal" id="alterarModal" tabindex="-1" aria-labelledby="alterarModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Alterar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form method="post" id="alterarForm">
                            <div class="row mt-2">
                                <div class="col-2">
                                    <label class="form-label ts-label">CLS</label>
                                    <input type="text" class="form-control ts-input" name="fcccod" id="fcccod" required readonly>
                                </div>
                                <div class="col">
                                    <label class="form-label ts-label">Cluster Planos</label>
                                    <input type="text" class="form-control ts-input" name="fccnom" id="fccnom" required>
                                </div>
                            </div>
                    </div><!--body-->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela ts-tableFiltros text-center">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>CLS</th>
                        <th>Clusters de Planos</th>
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
            //alert(FiltroPortador)
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/fincotacluster.php?operacao=buscar',
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

                        linha = linha + "<td>" + object.fcccod + "</td>";
                        linha = linha + "<td>" + object.fccnom + "</td>";
                        linha = linha + "<td class='text-end pe-2'><a class=' btn btn-warning btn-sm' href='fincotacluster_alterar.php?fcccod=" + object.fcccod + "' role='button'><i class='bi bi-pencil-square'></i></a></td> ";

                        linha = linha + "</tr>";
                    }

                    $("#dados").html(linha);

                    var texto = $("#textocontador");
                    texto.html('Total: ' + contadorItem);

                }
            });


        }

        // MODAL ALTERAR
        $(document).on('click', 'button[data-bs-target="#alterarModal"]', function() {
            var fcccod = $(this).attr("data-fcccod");
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/fincotacluster.php?operacao=buscar',
                data: {
                    fcccod: fcccod
                },
                success: function(data) {
                    $('#fcccod').val(data.fcccod);
                    $('#fccnom').val(data.fccnom);

                    $('#alterarModal').modal('show');
                }
            });
        });

        $("#inserirForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotacluster.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage
            });
        });

        $("#alterarForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotacluster.php?operacao=alterar",
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

        /* MODAL DE CARGA */
        $(document).ready(function() {
            $('#arquivo').on('change', function() {
                var formData = new FormData(document.getElementById('uploadForm'));

                $.ajax({
                    type: 'POST',
                    url: "../database/fincotacluster.php?operacao=cargaFilial",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(msg) {
                     
                        var json = JSON.parse(msg);
                        if (json['status'] == 200) {
                            var texto = $("#textomensagem");
                            texto.html(json['descricaoStatus']);
                            $('.alertMesg').addClass('alert-success');
                            $('.alertMesg').removeClass('alert-danger');
                            $('#cargaFilialModal').modal('show');
                        }

                        if (json['status'] == 400) {
                            var texto = $("#textomensagem");
                            texto.html(json['descricaoStatus']);
                            $('.alertMesg').addClass('alert-danger');
                            $('.alertMesg').removeClass('alert-success');
                            $('#cargaFilialModal').modal('show');
                        }
                        
                    },
                    error: function(xhr, status, error) {
                        alert("ERRO=" + JSON.stringify(error));
                    }
                });

            });
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>