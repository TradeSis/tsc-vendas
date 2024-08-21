<?php
// Lucas 15082024
include_once('../header.php');
include_once('../database/fincotacluster.php');

$cluster = buscaCluster($_GET['fcccod']);
$fcccod = $cluster['fcccod'];

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
                <a href="fincotacluster.php" style="text-decoration: none;"><h6 class="ts-tituloSecundaria">Cluster de Planos</h6></a> &nbsp; / &nbsp;
                <h2 class="ts-tituloPrincipal">Alterar Cluster</h2>
                
            </div>
            <div class="col-3">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="#" onclick="history.back()" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>


        <form method="post" id="alterarClusterForm">
            <div class="row">
                <div class="col-3">
                    <label class="form-label ts-label">CLS</label>
                    <input type="text" class="form-control ts-input" name="fcccod" value="<?php echo $cluster['fcccod'] ?>" required readonly>
                </div>
                <div class="col">
                    <label class="form-label ts-label">Cluster Planos</label>
                    <input type="text" class="form-control ts-input" name="fccnom" value="<?php echo $cluster['fccnom'] ?>" required>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
            </div>
        </form>


    </div>

    <!--------- INSERIR FILIAIS --------->
    <div class="modal" id="inserirFiliaisModal" tabindex="-1" aria-labelledby="inserirFiliaisModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Filial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="inserirFiliaisForm">
                        <div class="row mt-2">
                            <label class="form-label ts-label" style="margin-bottom: -17px;">Filial</label>
                            <div class="col input-group mb-3 mt-3" style="margin-top: 50px;">
                                <input type="text" class="form-control ts-inputcomBtn mt-1" name="Etbcod" id="inserir_Etbcod">
                                <button class="btn btn-outline-secondary ts-acionaZoomEstab" type="button" title="Pesquisar Filial"><i class="bi bi-search"></i></button>
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Nome Filial</label>
                                <input type="text" class="form-control ts-input" id="inserir_munic" disabled>
                                <input type="hidden" class="form-control ts-input" name="fcccod" value="<?php echo $cluster['fcccod'] ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label class="form-label ts-label">Inicio</label>
                                <input type="date" class="form-control ts-input" name="DtIVig" id="inserir_DtIVig" required>
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Final</label>
                                <input type="date" class="form-control ts-input" name="DtFVig">
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Cotas Liberadas</label>
                                <input type="text" class="form-control ts-input" name="CotasLib" required>
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

    <!-- botão de modais que ficam escondidos -->
    <button type="button" class="btn btn-success d-none" data-bs-toggle="modal" data-bs-target="#zoomEstabModal" id="abreEstabModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
    <button type="button" class="btn btn-success d-none" data-bs-toggle="modal" data-bs-target="#zoomPlanosModal" id="abrePlanosModal"><i class="bi bi-plus-square"></i>&nbsp NovoX</button>

  
    <!--------- EXCLUIR FILIAIS --------->
    <div class="modal" id="excluirFiliaisModal" tabindex="-1" aria-labelledby="excluirFiliaisModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Excluir Filial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="excluirFiliaisForm">
                        <div class="row mt-2">
                            <div class="col-2">
                                <label class="form-label ts-label">Filial</label>
                                <input type="text" class="form-control ts-input" name="Etbcod" id="exc_Etbcod" readonly>
                                <input type="hidden" class="form-control ts-input" name="id_recid" id="exc_id_recid">
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Municipio</label>
                                <input type="text" class="form-control ts-input" id="exc_munic" readonly>
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
                                <input type="hidden" class="form-control ts-input" name="fcccod" value="<?php echo $cluster['fcccod'] ?>">
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
                            <div class="col">
                                <label class="form-label ts-label">Plano</label>
                                <input type="text" class="form-control ts-input" name="fincod" id="excPlan_fincod" readonly>
                                <input type="hidden" class="form-control ts-input" name="id_recid" id="excPlan_id_recid">
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

    <!--------- INSERIR SUPERVISOR --------->
    <div class="modal" id="inserirSupervisorModal" tabindex="-1" aria-labelledby="inserirSupervisorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inserir Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="inserirSupervisorForm">
                        <div class="row mt-2">
                            <div class="col">
                                <label class="form-label ts-label">Cod</label>
                                <input type="text" class="form-control ts-input" name="supcod" required>
                                <input type="hidden" class="form-control ts-input" name="fcccod" value="<?php echo $cluster['fcccod'] ?>">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label class="form-label ts-label">Inicio</label>
                                <input type="date" class="form-control ts-input" name="DtIVig" id="inserir_DtIVig_Supervisor" required>
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Final</label>
                                <input type="date" class="form-control ts-input" name="DtFVig">
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Cotas Liberadas</label>
                                <input type="text" class="form-control ts-input" name="CotasLib" required>
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

    <!--------- EXCLUIR SUPERVISOR --------->
    <div class="modal" id="excluirSupervisorModal" tabindex="-1" aria-labelledby="excluirSupervisorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Excluir Supervisor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="excluirSupervisorForm">
                        <div class="row mt-2">
                            <div class="col-2">
                                <label class="form-label ts-label">Cod</label>
                                <input type="text" class="form-control ts-input" name="supcod" id="excSup_supcod" readonly>
                                <input type="hidden" class="form-control ts-input" name="id_recid" id="excSup_id_recid">
                            </div>
                            <div class="col">
                                <label class="form-label ts-label">Nome</label>
                                <input type="text" class="form-control ts-input" name="supnom" id="excSup_supnom" readonly>
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
            <div class="tab whiteborder" id="tab-filiais">Filiais</div>
            <div class="tab" id="tab-planos" onclick="buscaPlanos()">Planos</div>
            <div class="tab" id="tab-supervisor" onclick="buscaSupervisor()">Supervisor</div>

            <div class="line"></div>

            <div class="tabContent">
                <div class="table ts-divTabela60">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#inserirFiliaisModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    </div>
                    <table class="table table-sm table-hover">
                        <thead class="ts-headertabelafixo">
                            <tr class="ts-headerTabelaLinhaCima">
                                <th>Fil</th>
                                <th>Municipio</th>
                                <th>Inicio</th>
                                <th>Final</th>
                                <th>Lib</th>
                                <th>Uso</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id='dadosFiliais' class="fonteCorpo">

                        </tbody>
                    </table>

                </div>
            </div>

            <div class="tabContent">
                <div class="table mt-2 ts-divTabela60">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#inserirPlanosModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    </div>
                    <table class="table table-sm table-hover">
                        <thead class="ts-headertabelafixo">
                            <tr class="ts-headerTabelaLinhaCima">
                                <th>Plano</th>
                                <th>Descricao</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id='dadosPlanos' class="fonteCorpo">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tabContent">
                <div class="table mt-2 ts-divTabela60">
                    <div class="text-end mb-1">
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#inserirSupervisorModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    </div>
                    <table class="table table-sm table-hover">
                        <thead class="ts-headertabelafixo">
                            <tr class="ts-headerTabelaLinhaCima">
                                <th>Cod</th>
                                <th>Nome</th>
                                <th>Inicio</th>
                                <th>Final</th>
                                <th>Lib</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id='dadosSupervisor' class="fonteCorpo">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <?php //include 'modalAlterarSenha.php'; 
    ?>
    <?php include 'zoomEstab.php'; ?>
    <?php include 'zoomFinan.php'; ?>
    <script>
        window.onload = function() {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'filiais') {
                showTabsContent(0);
            }
            if (id === 'planos') {
                showTabsContent(1);
            }
            if (id === 'supervisor') {
                showTabsContent(2);
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

        // TABELA FILIAL
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../database/fincotacllib.php?operacao=buscar',
            beforeSend: function() {
                $("#dadosFiliais").html("Carregando...");
            },
            data: {
                fcccod: '<?php echo $fcccod ?>'
            },
            success: function(msg) {
                //alert(msg);
                var json = JSON.parse(msg);

                var linha = "";
                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];

                    linha = linha + "<tr>";

                    linha = linha + "<td>" + object.Etbcod + "</td>";
                    linha = linha + "<td>" + object.munic + "</td>";
                    linha = linha + "<td>" + formatDate(object.DtIVig) + "</td>";
                    linha = linha + "<td>" + formatDate(object.DtFVig) + "</td>";
                    linha = linha + "<td>" + object.CotasLib + "</td>";
                    linha = linha + "<td>" + object.cotasuso + "</td>";

                    linha = linha + "<td class='text-end pe-2'><a class=' btn btn-warning btn-sm' href='fincotacllib_alterar.php?fcccod=" + object.fcccod + "&Etbcod=" + object.Etbcod + "&DtIVig=" + object.DtIVig + "&id_recid=" + object.id_recid + "' role='button'><i class='bi bi-pencil-square'></i></a> ";

                    linha = linha + "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#excluirFiliaisModal'";
                    linha = linha + " data-Etbcod='" + object.Etbcod + "' ";
                    linha = linha + " data-munic='" + object.munic + "' ";
                    linha = linha + " data-id_recid='" + object.id_recid + "' ";
                    linha = linha + "><i class='bi bi-trash'></i></button></td>";

                    linha = linha + "</tr>";
                }
                $("#dadosFiliais").html(linha);
            }
        });

        function buscaPlanos() {

            // TABELA PLANOS
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/fincotaclplan.php?operacao=buscar',
                beforeSend: function() {
                    $("#dadosPlanos").html("Carregando...");
                },
                data: {
                    fcccod: '<?php echo $fcccod ?>'
                },
                success: function(msg) {
                    //alert(msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.fincod + "</td>";
                        linha = linha + "<td>" + object.finnom + "</td>";

                        linha = linha + "<td class='text-end pe-2'><button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#excluirPlanosModal'";
                        linha = linha + " data-fincod='" + object.fincod + "' ";
                        linha = linha + " data-id_recid='" + object.id_recid + "' ";
                        linha = linha + "><i class='bi bi-trash'></i></button></td>";

                        linha = linha + "</tr>";
                    }
                    $("#dadosPlanos").html(linha);
                }
            });
        }

        function buscaSupervisor() {

            // TABELA SUPERVISOR
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '../database/fincotasuplib.php?operacao=buscar',
                beforeSend: function() {
                    $("#dadosSupervisor").html("Carregando...");
                },
                data: {
                    fcccod: '<?php echo $fcccod ?>'
                },
                success: function(msg) {
                    //alert(msg);
                    var json = JSON.parse(msg);

                    var linha = "";
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];

                        linha = linha + "<tr>";

                        linha = linha + "<td>" + object.supcod + "</td>";
                        linha = linha + "<td>" + object.supnom + "</td>";
                        linha = linha + "<td>" + formatDate(object.DtIVig) + "</td>";
                        linha = linha + "<td>" + formatDate(object.DtFVig) + "</td>";
                        linha = linha + "<td>" + object.CotasLib + "</td>";

                        linha = linha + "<td class='text-end pe-2'><a class=' btn btn-warning btn-sm' id='btnteste' href='fincotasuplib_alterar.php?fcccod=" + object.fcccod + "&supcod=" + object.supcod + "&DtIVig=" + object.DtIVig + "&id_recid=" + object.id_recid + "' role='button'><i class='bi bi-pencil-square'></i></a> ";

                        linha = linha + "<button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#excluirSupervisorModal'";
                        linha = linha + " data-supcod='" + object.supcod + "' ";
                        linha = linha + " data-supnom='" + object.supnom + "' ";
                        linha = linha + " data-id_recid='" + object.id_recid + "' ";
                        linha = linha + "><i class='bi bi-trash'></i></button></td>";

                        linha = linha + "</tr>";
                    }
                    $("#dadosSupervisor").html(linha);
                }
            });

        }

        // MODAL FILIAIS EXCLUIR
        $(document).on('click', 'button[data-bs-target="#excluirFiliaisModal"]', function() {
            var Etbcod = $(this).attr("data-Etbcod");
            var munic = $(this).attr("data-munic");
            var id_recid = $(this).attr("data-id_recid");

            $('#exc_Etbcod').val(Etbcod);
            $('#exc_munic').val(munic);
            $('#exc_id_recid').val(id_recid);

            $('#excluirFiliaisModal').modal('show');

        });

        // MODAL PLANOS EXCLUIR
        $(document).on('click', 'button[data-bs-target="#excluirPlanosModal"]', function() {
            var fincod = $(this).attr("data-fincod");
            var id_recid = $(this).attr("data-id_recid");

            $('#excPlan_fincod').val(fincod);
            $('#excPlan_id_recid').val(id_recid);

            $('#excluirPlanosModal').modal('show');

        });

        // MODAL SUPERVISOR EXCLUIR
        $(document).on('click', 'button[data-bs-target="#excluirSupervisorModal"]', function() {
            var supcod = $(this).attr("data-supcod");
            var supnom = $(this).attr("data-supnom");
            var id_recid = $(this).attr("data-id_recid");

            $('#excSup_supcod').val(supcod);
            $('#excSup_supnom').val(supnom);
            $('#excSup_id_recid').val(id_recid);

            $('#excluirSupervisorModal').modal('show');

        });

        // Ao clicar no input Filial simula um click no botão do modal "Estabelecimentos"
        $(document).on('click', '.ts-acionaZoomEstab', function() {
            const elemento = document.getElementById('abreEstabModal');
            elemento.click()
        });

        // Ao selecionar um estabelecimento, passa Etbcod e munic para form inserir 
        $(document).on('click', '.ts-click', function() {
            var etbcod = $(this).attr("data-etbcod");
            var munic = $(this).attr("data-munic");

            $('#inserir_Etbcod').val(etbcod);
            $('#inserir_munic').val(munic);

            $('#zoomEstabModal').modal('hide');
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


        $("#alterarClusterForm").submit(function(event) {
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

        $("#inserirFiliaisForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotacllib.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage
            });
        });

        $("#excluirFiliaisForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotacllib.php?operacao=excluir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage
            });
        });

        $("#inserirPlanosForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotaclplan.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    var json = JSON.parse(data);

                    if (json['status'] == 400) {
                        alert(json['descricaoStatus'])
                    } else {
                        $('#inserirPlanosModal').modal('hide');
                        $('#insPlan_fincod').val('');
                        const elemento = document.getElementById('tab-planos');
                        elemento.click();
                    }


                }
            });
        });


        $("#excluirPlanosForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotaclplan.php?operacao=excluir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    $('#excluirPlanosModal').modal('hide');
                    const elemento = document.getElementById('tab-planos');
                    elemento.click();
                }
            });
        });

        $("#inserirSupervisorForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotasuplib.php?operacao=inserir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    //console.log(JSON.stringify(data, null, 2));
                    var json = JSON.parse(data);

                    if (json['status'] == 400) {
                        alert(json['descricaoStatus'])
                    } else {
                        $('#inserirSupervisorModal').modal('hide');
                        //$('#insSupervisor_fincod').val('');
                        const elemento = document.getElementById('tab-supervisor');
                        elemento.click();
                    }


                }
            });
        });


        $("#excluirSupervisorForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotasuplib.php?operacao=excluir",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    $('#excluirSupervisorModal').modal('hide');
                    const elemento = document.getElementById('tab-supervisor');
                    elemento.click();
                }
            });
        });

        function refreshPage() {
            window.location.reload();
        }


        function formatDate(dateString) {
            if (dateString !== null && !isNaN(new Date(dateString))) {
                var date = new Date(dateString);
                var day = date.getUTCDate().toString().padStart(2, '0');
                var month = (date.getUTCMonth() + 1).toString().padStart(2, '0');
                var year = date.getUTCFullYear().toString().padStart(4, '0');
                return day + "/" + month + "/" + year;
            }
            return "";
        }

        // Ao iniciar o programa, inseri a data atual no input. 
        $(document).ready(function() {
            var data = new Date(),
                dia = data.getDate().toString(),
                diaF = (dia.length == 1) ? '0' + dia : dia,
                mes = (data.getMonth() + 1).toString(), //+1 pois no getMonth Janeiro come�a com zero.
                mesF = (mes.length == 1) ? '0' + mes : mes,
                anoF = data.getFullYear();
            dataAtual = anoF + "-" + mesF + "-" + diaF;
            primeirodiadomes = anoF + "-" + mesF + "-" + "01";

            // offCanvas data
            const DtIVig = document.getElementById("inserir_DtIVig");
            DtIVig.value = dataAtual;

            const DtIVig_Supervisor = document.getElementById("inserir_DtIVig_Supervisor");
            DtIVig_Supervisor.value = dataAtual;
        });
    </script>


    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>