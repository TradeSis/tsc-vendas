<!--------- MODAL --------->
<div class="modal" id="zoomPlanosModal" tabindex="-1" role="dialog" aria-labelledby="zoomPlanosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Busca Planos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 d-flex gap-2">
                                    <input type="text" placeholder="Digite o codigo do plano"
                                        class="form-control ts-input" id="buscaPlano" name="buscaPlano">
                                        <button class="btn btn btn-success" type="button" id="btnBuscarPlano">Buscar</i></button>
                                </div>
                            </div>

                        </div>
                        <div class="container-fluid mb-2">
                            <div class="table mt-4 ts-tableFiltros text-center">
                                <table class="table table-sm table-hover ts-tablecenter">
                                    <thead class="ts-headertabelafixo">
                                        <tr class="ts-headerTabelaLinhaCima">
                                            <th>Cod</th>
                                            <th>Descricao</th>
                                        </tr>
                                    </thead>

                                    <tbody id='dadosFinan' class="fonteCorpo">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="container text-center my-1">
                            <button id="prevPage_planos" class="btn btn-primary mr-2" style="display:none;">Anterior</button>
                            <button id="nextPage_planos" class="btn btn-primary" style="display:none;">Proximo</button>
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
    var pagina = 0;


    $(document).on('click', 'button[data-bs-target="#zoomPlanosModal"]', function() {
        buscarPlan($("#buscaPlano").val(), pagina);
    });


    function buscarPlan(buscaPlano, pagina) {
        //alert (buscaPlano);
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo URLROOT ?>/crediario/database/finan.php?operacao=buscar",
            beforeSend: function () {
                $("#dadosFinan").html("Carregando...");
            },
            data: {
                fincod: buscaPlano,
                pagina: pagina
            },
            async: false,
            success: function (msg) {
                //alert(msg)
                var json = JSON.parse(msg);
                var linha = "";
                if (json === null) {
                        $("#dadosFinan").html("Erro ao buscar");
                } 
                if (json.status === 400) {
                        $("#dadosFinan").html("Nenhum estabelecimento foi encontrado");
                } else {
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        //alert(object.fincod)
                        linha = linha + "<tr>";
                        linha = linha + "<td class='ts-clickPlanos' data-fincod='" + object.fincod + "' data-finnom='" + object.finnom + "'>" + object.fincod + "</td>";
                        linha = linha + "<td class='ts-clickPlanos' data-fincod='" + object.fincod + "' data-finnom='" + object.finnom + "'>" + object.finnom + "</td>";
                        linha = linha + "</tr>";
                    }
                    $("#dadosFinan").html(linha);

                    $("#prevPage_planos, #nextPage_planos").show();
                    if (pagina == 0) {
                        $("#prevPage_planos").hide();
                    }
                    if (json.length < 10) {
                        $("#nextPage_planos").hide();
                    }
                }
            }
        });
    }
    $("#btnBuscarPlano").click(function () {
        buscarPlan($("#buscaPlano").val(), pagina);
    })

    document.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            buscarPlan($("#buscaPlano").val(), pagina);
        }
    });

    $("#prevPage_planos").click(function () {
        if (pagina > 0) {
            pagina -= 10;
            buscarPlan($("#buscaPlano").val(), pagina);
        }
    });

    $("#nextPage_planos").click(function () {
        pagina += 10;
        buscarPlan($("#buscaPlano").val(), pagina);
    });

</script>


<!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>