<!--------- MODAL --------->
<div class="modal" id="zoomSupervisorModal" tabindex="-1" role="dialog" aria-labelledby="zoomSupervisorModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Busca Supervisor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 d-flex gap-2">
                                    <input type="text" placeholder="Digite o codigo do supervisor"
                                        class="form-control ts-input" id="buscaSupervisor" name="buscaSupervisor">
                                        <button class="btn btn btn-success" type="button" id="btnBuscarSupervisor">Buscar</i></button>
                                </div>
                            </div>

                        </div>
                        <div class="container-fluid mb-2">
                            <div class="table mt-4 ts-tableFiltros text-center">
                                <table class="table table-sm table-hover ts-tablecenter">
                                    <thead class="ts-headertabelafixo">
                                        <tr class="ts-headerTabelaLinhaCima">
                                            <th>Cod</th>
                                            <th>Nome</th>
                                        </tr>
                                    </thead>

                                    <tbody id='dadosZoomSupervisor' class="fonteCorpo">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="container text-center my-1">
                            <button id="prevPage_supervisor" class="btn btn-primary mr-2" style="display:none;">Anterior</button>
                            <button id="nextPage_supervisor" class="btn btn-primary" style="display:none;">Proximo</button>
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


    $(document).on('click', 'button[data-bs-target="#zoomSupervisorModal"]', function() {
        buscarSup($("#buscaSupervisor").val(), pagina);
    });


    function buscarSup(buscaSupervisor, pagina) {
        //alert (buscaSupervisor);
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo URLROOT ?>/vendas/database/zoomsupervisor.php?operacao=buscar",
            beforeSend: function () {
                $("#dadosZoomSupervisor").html("Carregando...");
            },
            data: {
                supcod: buscaSupervisor,
                pagina: pagina
            },
            async: false,
            success: function (msg) {
                //alert(msg)
                var json = JSON.parse(msg);
                var linhasup = "";
                if (json === null) {
                    alert("x1")
                        $("#dadosZoomSupervisor").html("Erro ao buscar");
                } 
                if (json.status === 400) {
                    alert("x2")
                        $("#dadosZoomSupervisor").html("Nenhum supervisor foi encontrado");
                } else {
                    //alert("x3")
                    $("#dadosZoomSupervisor").html("<tr><td>aqqqqqq</td></tr>");
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        //alert(object.supnom)
                        linhasup = linhasup + "<tr>";
                        linhasup = linhasup + "<td class='ts-clickSupervisor' data-supcod='" + object.supcod + "' data-supnom='" + object.supnom + "'>" + object.supcod + "</td>";
                        linhasup = linhasup + "<td class='ts-clickSupervisor' data-supcod='" + object.supcod + "' data-supnom='" + object.supnom + "'>" + object.supnom + "</td>";
                        linhasup = linhasup + "</tr>";
                    }
                    $("#dadosZoomSupervisor").html(linhasup);

                    $("#prevPage_supervisor, #nextPage_supervisor").show();
                    if (pagina == 0) {
                        $("#prevPage_supervisor").hide();
                    }
                    if (json.length < 10) {
                        $("#nextPage_supervisor").hide();
                    }
                }
            }
        });
    }
    $("#btnBuscarSupervisor").click(function () {
        buscarSup($("#buscaSupervisor").val(), 0);
    })

    document.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            buscarSup($("#buscaSupervisor").val(), 0);
        }
    });

    $("#prevPage_supervisor").click(function () {
        if (pagina > 0) {
            pagina -= 10;
            buscarSup($("#buscaSupervisor").val(), pagina);
        }
    });

    $("#nextPage_supervisor").click(function () {
        pagina += 10;
        buscarSup($("#buscaSupervisor").val(), pagina);
    });

</script>


<!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>