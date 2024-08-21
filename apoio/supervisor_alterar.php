<?php
// Lucas 20082024
include_once('../header.php');
include_once('../database/supervisor.php');

$supcod = $_GET['supcod'];
$supervisor = buscaSupervisores($supcod);


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
                <a href="supervisor.php" style="text-decoration: none;"><h6 class="ts-tituloSecundaria">Supervisor</h6></a> &nbsp; / &nbsp;
                <h2 class="ts-tituloPrincipal">Alterar Supervisor</h2>
            </div>
            <div class="col-3">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="#" onclick="history.back()" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>

        <form method="post" id="alterarForm">
            <div class="row">
                <div class="col-2">
                    <label class="form-label ts-label">Cod</label>
                    <input type="text" class="form-control ts-input" name="supcod" value="<?php echo $supervisor[0]['supcod'] ?>" readonly>
                </div>
                <div class="col">
                    <label class="form-label ts-label">Nome</label>
                    <input type="text" class="form-control ts-input" name="supnom" value="<?php echo $supervisor[0]['supnom'] ?>">
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
            </div>
        </form>

    </div>


    <div class="container-fluid mt-3">

        <div class="table ts-divTabela60">
            <h2 class="ts-tituloPrincipal">Filiais</h2>
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>Filial</th>
                        <th>Nome</th>
                        <th>Municipio</th>    
                    </tr>
                </thead>

                <tbody id='dadosFilialSup' class="fonteCorpo">

                </tbody>
            </table>

        </div>

    </div>


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
   
    <script>
        // TABELA UTILIZACAO
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '../database/supervisor.php?operacao=buscarFilial',
            beforeSend: function() {
                $("#dadosEmpresa").html("Carregando...");
            },
            data: {
                supcod: '<?php echo $supcod ?>'
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

                    linha = linha + "<td>" + object.etbcod + "</td>";
                    linha = linha + "<td>" + object.etbnom + "</td>";
                    linha = linha + "<td>" + object.munic + "</td>";

                    linha = linha + "</tr>";
                }
                $("#dadosFilialSup").html(linha);
            }
        });

        $("#alterarForm").submit(function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "../database/supervisor.php?operacao=alterar",
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