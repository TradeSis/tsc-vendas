<?php
// Lucas 21102024
include_once('../header.php');
include_once('../database/servicos/geaparam.php');

$procod = buscaGEAvulsa($_GET['procod']);

$contrassin = "Nao";
?>

<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body>

    <div class="container-fluid">

        <div class="row">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <!--<BR> MENSAGENS/ALERTAS -->
        </div>
        <div class="row mt-2"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-7 d-flex">
                <!-- TITULO -->
                <a href="garantiaestendidaavulsa.php" style="text-decoration: none;">
                    <h6 class="ts-tituloSecundaria">Garantia Estendida Avulsa</h6>
                </a> &nbsp; / &nbsp;
                <h2 class="ts-tituloPrincipal"><?php echo $procod['procod'] ?> &nbsp; <?php echo $procod['pronom'] ?></h2>

            </div>
            <div class="col-3">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="#" onclick="history.back()" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>



    </div>
    <!-- botão de modais que ficam escondidos -->
    <button type="button" class="btn btn-success d-none" data-bs-toggle="modal" data-bs-target="#zoomPlanosModal" id="abrePlanosModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>

    <!--------- INSERIR PLANOS --------->
    <div class="modal" id="inserirPlanosModal" tabindex="-1" aria-labelledby="inserirPlanosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Planos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="inserirPlanosForm">
                        <div class="row mt-2">
                            <label class="form-label ts-label" style="margin-bottom: -17px;">Plano</label>
                            <div class="col input-group mb-3 mt-3" style="margin-top: 50px;">
                                <input type="text" class="form-control ts-inputcomBtn mt-1" name="fincod" id="insPlan_fincod">
                                <input type="hidden" class="form-control ts-input" name="procod" value="<?php echo $procod['procod'] ?>">
                                <button class="btn btn-outline-secondary ts-acionaZoomPlanos" type="button" title="Pesquisar Plano"><i class="bi bi-search"></i></button>
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Descricao</label>
                                <input type="text" class="form-control ts-input" name="finnom" id="insPlan_finnom" disabled>
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

    <!--------- EXCLUIR PLANOS --------->
    <div class="modal" id="excluirPlanosModal" tabindex="-1" aria-labelledby="excluirPlanosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Excluir Planos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="excluirPlanosForm">
                        <div class="row mt-2">
                            <div class="col-2">
                                <label class="form-label ts-label">Plano</label>
                                <input type="text" class="form-control ts-input" name="fincod" id="excPlan_fincod" readonly>
                                <input type="hidden" class="form-control ts-input" name="id_recid" id="excPlan_id_recid">
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Descrição</label>
                                <input type="text" class="form-control ts-input" name="fincod" id="excPlan_finnom" readonly>
                            </div>
                        </div>

                </div><!--body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-3">
        <div id="ts-tabs">
            <div class="tab whiteborder" id="tab-planos">Planos</div>
            <div class="line"></div>

            <div class="tabContent">
                <div class="table mt-2 ts-divTabela">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#inserirPlanosModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    </div>
                    <table class="table table-sm table-hover">
                        <thead class="ts-headertabelafixo">
                            <tr class="ts-headerTabelaLinhaCima">
                                <th>Plano</th>
                                <th>Descricao</th>
                                <th>N.Prest.</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id='dadosPlanos' class="fonteCorpo">

                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>

    <!-- MODAIS DE ZOOM -->
    <?php include ROOT . '/crediario/zoom/finan.php'; ?>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <script>
        // TABELA PLANOS
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../database/servicos/geafinan.php?operacao=buscar',
            beforeSend: function() {
                $("#dadosPlanos").html("Carregando...");
            },
            data: {
              procod: '<?php echo $procod['procod'] ?>'
            },
            success: function(msg) {
                var json = JSON.parse(msg);

                var linha = "";
                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];

                    linha = linha + "<tr>";

                    linha = linha + "<td>" + object.fincod + "</td>";
                    linha = linha + "<td>" + object.finnom + "</td>";
                    linha = linha + "<td>" + object.finnpc + "</td>";

                    linha = linha + "<td class='text-end pe-2'><button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#excluirPlanosModal'";
                    linha = linha + " data-fincod='" + object.fincod + "' ";
                    linha = linha + " data-finnom='" + object.finnom + "' ";
                    linha = linha + " data-id_recid='" + object.id_recid + "' ";
                    linha = linha + "><i class='bi bi-trash'></i></button></td>";

                    linha = linha + "</tr>";
                }
                $("#dadosPlanos").html(linha);
            }
        });

        // AÇÂO DE CLICK MODAL PLANO
        $(document).on('click', '.ts-acionaZoomPlanos', function() {
            const elemento = document.getElementById('abrePlanosModal');
            elemento.click()
        });

        $(document).on('click', '.ts-clickPlanos', function() {
            var fincod = $(this).attr("data-fincod");
            var finnom = $(this).attr("data-finnom");

            $('#insPlan_fincod').val(fincod);
            $('#insPlan_finnom').val(finnom);

            $('#zoomPlanosModal').modal('hide');
        });

       // MODAL PLANOS EXCLUIR
       $(document).on('click', 'button[data-bs-target="#excluirPlanosModal"]', function() {
            var fincod = $(this).attr("data-fincod");
            var finnom = $(this).attr("data-finnom");
            var id_recid = $(this).attr("data-id_recid");

            $('#excPlan_fincod').val(fincod);
            $('#excPlan_finnom').val(finnom);
            $('#excPlan_id_recid').val(id_recid);

            $('#excluirPlanosModal').modal('show');

        });

        $("#inserirPlanosForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/servicos/geafinan.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    var json = JSON.parse(data);
                    if (json['status'] == 400) {
                        alert(json['descricaoStatus'])
                    } else {
                        refreshPage()
                    }
                }
            });
        });

        $("#excluirPlanosForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/servicos/geafinan.php?operacao=excluir",
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

        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');

            if (id === 'planos') {
                showTabsContent(1);
            }
        }

        document.getElementById('ts-tabs').onclick = function(event) {
            var target = event.target;
            if (target.className.includes('tab')) {
                for (var i = 0; i < tab.length; i++) {
                    if (target == tab[i]) {
                        showTabsContent(i);
                        break;
                    }
                }
            }
        }

        function hideTabsContent(startIndex) {
            for (var i = startIndex; i < tabContent.length; i++) {
                tabContent[i].classList.remove('show');
                tabContent[i].classList.add("hide");
                tab[i].classList.remove('whiteborder');
            }
        }

        function showTabsContent(index) {
            if (tabContent[index].classList.contains('hide')) {
                hideTabsContent(0);
                tab[index].classList.add('whiteborder');
                tabContent[index].classList.remove('hide');
                tabContent[index].classList.add('show');
            }
        }
    </script>


    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>