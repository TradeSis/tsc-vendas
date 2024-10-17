<?php
// Lucas 16082024
include_once('../header.php');
include_once('../database/fincotacllib.php');
include_once('../database/fincotacluster.php');

$fcccod = $_GET['fcccod'];
$id_recid = $_GET['id_recid'];

$filial = buscaFilial($id_recid);

$cluster = buscaCluster($fcccod);

$motanomeFilial = 'Filial' . " " . $cluster['fcccod'] . " " . $cluster['fccnom'];


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
                <!-- <h2 class="ts-tituloPrincipal">Alterar Filial</h2> -->
                <a href="fincotacluster.php" style="text-decoration: none;"><h6 class="ts-tituloSecundaria">Cluster de Planos</h6></a> &nbsp; / &nbsp;
                <a href="fincotacluster_alterar.php?fcccod=<?php echo $fcccod ?>" style="text-decoration: none;"><h6 class="ts-tituloSecundaria"><?php echo $motanomeFilial ?></h6></a> &nbsp; / &nbsp;
                <h2 class="ts-tituloPrincipal">Alterar Filial</h2>
            </div>
            <div class="col-3">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="#" onclick="history.back()" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form method="post" id="alterarFiliaisForm">
            <div class="row">
                <div class="col-2">
                    <label class="form-label ts-label">Filial</label>
                    <input type="text" class="form-control ts-input" name="Etbcod" value="<?php echo $filial[0]['Etbcod'] ?>" readonly>
                    <input type="hidden" class="form-control ts-input" name="fcccod" value="<?php echo $filial[0]['fcccod'] ?>">
                </div>
                <div class="col">
                    <label class="form-label ts-label">Municipio</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $filial[0]['munic'] ?>" readonly>
                </div>
                <div class="col">
                    <label class="form-label ts-label">Inicio</label>
                    <input type="date" class="form-control ts-input" name="DtIVig" value="<?php echo $filial[0]['DtIVig'] ?>" readonly>
                </div>
                <div class="col">
                    <label class="form-label ts-label">Final</label>
                    <input type="date" class="form-control ts-input" name="DtFVig" value="<?php echo $filial[0]['DtFVig'] ?>">
                </div>
                <div class="col">
                    <label class="form-label ts-label">Cotas Liberadas</label>
                    <input type="text" class="form-control ts-input" name="CotasLib" value="<?php echo $filial[0]['CotasLib'] ?>">
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
            </div>
        </form>


    </div>

    <!--------- ALTERAR UTILIZACAO --------->
    <div class="modal" id="alterarUtilizacaoModal" tabindex="-1" aria-labelledby="alterarUtilizacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar Utilização</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form method="post" id="alterarUtilizacaoForm">
                        <div class="row">
                            <div class="col">
                                <label class="form-label ts-label">Ativo</label>
                                <select class="form-select ts-input" name="Ativo" id="altUti_Ativo">
                                    <option value="false">Desligado</option>
                                    <option value="true">Ligado</option>
                                </select>
                            </div>
                            <div class="col d-none">
                                <input type="hidden" class="form-control ts-input" name="Etbcod" id="altUti_Etbcod">
                                <input type="hidden" class="form-control ts-input" name="fincod" id="altUti_fincod">
                                <input type="hidden" class="form-control ts-input" name="DtIVig" id="altUti_DtIVig">
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


    <div class="container-fluid mt-3">

        <div class="table ts-divTabela60">
            <h2 class="ts-tituloPrincipal">Utilização</h2>
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>Plan</th>
                        <th>Descricao</th>
                        <th>Inicio</th>
                        <th>Final</th>
                        <th>Ativo</th>
                        <th>Uso</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dadosUtilizacao' class="fonteCorpo">

                </tbody>
            </table>

        </div>

    </div>





    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <?php //include 'modalAlterarSenha.php'; 
    ?>
    <?php //include 'zoomEstab.php'; ?>
    <script>
        // TABELA UTILIZACAO
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../database/fincotaetb.php?operacao=buscar',
            beforeSend: function() {
                $("#dadosEmpresa").html("Carregando...");
            },
            data: {
                id_recid: '<?php echo $id_recid ?>'
            },
            success: function(msg) {
                //alert(msg);
                var json = JSON.parse(msg);

                var linha = "";
                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];

                    if (object.Ativo == true) {
                        ativo = "Ligado"
                    } else {
                        ativo = "Desligado"
                    }

                    linha = linha + "<tr>";

                    linha = linha + "<td>" + object.fincod + "</td>";
                    linha = linha + "<td>" + object.finnom + "</td>";
                    linha = linha + "<td>" + formatDate(object.DtIVig) + "</td>";
                    linha = linha + "<td>" + formatDate(object.DtFVig) + "</td>";
                    linha = linha + "<td>" + ativo + "</td>";
                    linha = linha + "<td>" + object.CotasUso + "</td>";

                    linha = linha + "<td class='text-end'><button type='button' class='btn btn-warning btn-sm me-2' data-bs-toggle='modal' data-bs-target='#alterarUtilizacaoModal'";
                    linha = linha + " data-Etbcod='" + object.Etbcod + "' ";
                    linha = linha + " data-fincod='" + object.fincod + "' ";
                    linha = linha + " data-DtIVig='" + object.DtIVig + "' ";
                    linha = linha + " data-Ativo='" + object.Ativo + "' ";
                    linha = linha + "><i class='bi bi-pencil-square'></i></button></td>";

                    linha = linha + "</tr>";
                }
                $("#dadosUtilizacao").html(linha);
            }
        });

        // MODAL UTILIZACAO ALTERAR
        $(document).on('click', 'button[data-bs-target="#alterarUtilizacaoModal"]', function() {
            var Etbcod = $(this).attr("data-Etbcod");
            var fincod = $(this).attr("data-fincod");
            var DtIVig = $(this).attr("data-DtIVig");
            var Ativo = $(this).attr("data-Ativo");

            $('#altUti_Etbcod').val(Etbcod);
            $('#altUti_fincod').val(fincod);
            $('#altUti_DtIVig').val(DtIVig);
            $('#altUti_Ativo').val(Ativo);

            $('#alterarUtilizacaoModal').modal('show');

        });


        $("#alterarUtilizacaoForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotaetb.php?operacao=alterar",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: refreshPage
            });
        });

        $("#alterarFiliaisForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/fincotacllib.php?operacao=alterar",
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
    </script>


    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>