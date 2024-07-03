<?php
// gabriel 300323 11:24

include_once '../header.php';
if(isset($_GET['cart']) && $_GET['cart'] !== "[]") {
    $cartJSON = $_GET['cart'];
    $produtos = json_decode($cartJSON, true);
} else {
    $cartJSON = "[]";
}

?>

<!doctype html>
<html lang="pt-BR">
<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body class="ts-noScroll">
    <div class="container-fluid">
        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 card p-0">
                <div class="card-header">
                    <h3>Pré-Vendas Etb 188</h3>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-7 mt-3">
                            <input type="text" placeholder="Buscar Pré-Venda" class="form-control ts-input" id="buscaPrevenda" name="buscaPrevenda">
                        </div>
                        <div class="col-sm-4 col-5 mt-2" style="text-align:right">
                            <a href="cliente.php" class="btn btn-success">Nova Pré-Venda</a>
                        </div>
                    </div>
                </div>
                <div class="container-fluid mb-2">
                    <div class="table mt-4 ts-tableFiltros text-center">
                        <table class="table table-sm table-hover ts-tablecenter">
                            <thead class="ts-headertabelafixo">
                                <tr class="ts-headerTabelaLinhaCima">
                                    <th>Codigo</th>
                                    <th>Inclusão</th>
                                    <th>cliente</th>
                                    <th>Fechamento</th>
                                </tr>
                            </thead>

                            <tbody id='dados' class="fonteCorpo">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="container text-center my-1">
                    <button id="prevPage" class="btn btn-primary mr-2">Anterior</button>
                    <button id="nextPage" class="btn btn-primary">Proximo</button>
                </div>
            </div>
        </div>

    </div>

    

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<script>
    var pagina = 0;
    var etbcod = 188;

    buscar($("#FiltroMod").val());

    function limpar() {
        buscar(null);
        window.location.reload();
    }

    function buscar() {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo URLROOT ?>/vendas/database/prevenda.php?operacao=buscarPrevenda",
            beforeSend: function () {
                $("#dados").html("Carregando...");
            },
            data: {
                etbcod: etbcod,
                pagina: pagina
            },
            async: false,
            success: function (msg) {
                //console.log(msg);
                var json = JSON.parse(msg);
                var linha = "";

                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];

                    var dtinclu = new Date(object.dtinclu);
                    dtincluFormatada = dtinclu.toLocaleDateString("pt-BR");
                    var dtfechamento = new Date(object.dtfechamento);
                    dtfechamentoFormatada = dtfechamento.toLocaleDateString("pt-BR");

                    linha += "<tr>";
                    linha += "<td class='ts-click' data-precod='" + object.precod + "'>" + object.precod + "</td>";
                    linha += "<td class='ts-click' data-precod='" + object.precod + "'>" + (object.dtinclu !== null ? dtincluFormatada : '') + "</td>";
                    linha += "<td class='ts-click' data-precod='" + object.precod + "'>" + (object.clicod !== null ? object.clicod : '') + "</td>";
                    linha += "<td class='ts-click' data-precod='" + object.precod + "'>" + (object.dtfechamento !== null ? dtfechamentoFormatada : '') + "</td>";
                    linha += "</tr>";

                }


                $("#dados").html(linha);

                $("#prevPage, #nextPage").show();
                if (pagina == 0) {
                    $("#prevPage").hide();
                }
                if (json.length < 10) {
                    $("#nextPage").hide();
                }
            }
        });
    }
    $("#FiltroMod").change(function() {
            buscar($("#FiltroMod").val());
        })

    document.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            buscar($("#FiltroMod").val());
        }
    });
    
    $("#prevPage").click(function() {
        if (pagina > 0) {
            pagina -= 10; 
            buscar($("#buscaProduto").val(), pagina);
        }
    });

    $("#nextPage").click(function() {
        pagina += 10; 
        buscar($("#buscaProduto").val(),pagina);
    });

    <?php if(isset($_GET['cart']) && $_GET['cart'] !== "[]") {?>
        var cart = <?php echo json_encode($produtos); ?>;

        var totalCart = 0;
        for (var i = 0; i < cart.length; i++) {
            totalCart += cart[i].total;
        }

    <?php 
    } else {?>
        var cart = [];
    <?php } ?>

    $(document).on('click', '.ts-click', function () {
        var precod = $(this).attr("data-precod");
        var clicod = $(this).attr("data-clicod");
        window.location.href = 'pedido.php?precod=' + precod;
    });
</script>


<!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>
</html>