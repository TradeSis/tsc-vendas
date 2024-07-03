<?php
// gabriel 300323 11:24

include_once '../header.php';

if(isset($_GET['cart']) && $_GET['cart'] !== "[]") {
    $cartJSON = $_GET['cart'];
    $produtos = json_decode($cartJSON, true);
} else {
    $cartJSON = "[]";
}

if(isset($_GET['precod'])) {
    $precod = $_GET['precod'];
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
                    
                    <div class="row">
                        <div class="col-sm-10 col-9">
                            <h3>Busca Produtos</h3>
                        </div>
                        <div class="col-sm-2 col-3">
                            <a href="#" onclick="VoltarComCarrinho()" role="button" class="btn btn btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-10 col-9 mt-3">
                            <input type="text" placeholder="Buscar Produtos" class="form-control ts-input" id="buscaProduto" name="buscaProduto">
                        </div>
                        <div class="col-sm-2 col-3 mt-2">
                            <button class="btn btn btn-success" type="button" id="buscar">Buscar</i></button>
                        </div>
                    </div>
                    
                </div>
                <div class="container-fluid mb-2">
                    <div class="table mt-4 ts-tableFiltros text-center">
                        <table class="table table-sm table-hover ts-tablecenter">
                            <thead class="ts-headertabelafixo">
                                <tr class="ts-headerTabelaLinhaCima">
                                    <th>Prod</th>
                                    <th>Nome</th>
                                    <th>Preço</th>
                                    <th>Promo</th>
                                </tr>
                            </thead>

                            <tbody id='dados' class="fonteCorpo">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="container text-center my-1">
                    <button id="prevPage" class="btn btn-primary mr-2" style="display:none;">Anterior</button>
                    <button id="nextPage" class="btn btn-primary" style="display:none;">Proximo</button>
                </div>
            </div>
        </div>
    </div>



<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<script>
    var pagina = 0;
    var etbcod = 188;

    function limpar() {
        buscar(null);
        window.location.reload();
    }

    function buscar(buscaProduto,pagina) {
        //alert (buscaProduto);
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "<?php echo URLROOT ?>/vendas/database/prevenda.php?operacao=buscarProduto",
            beforeSend: function() {
                $("#dados").html("Carregando...");
            },
            data: {
                buscaProduto: buscaProduto,
                pagina: pagina,
                etbcod: etbcod
            },
            async: false,
            success: function(msg) {
                //console.log(msg);
                var json = JSON.parse(msg);
                var linha = "";
                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];

                    linha = linha + "<tr>";
                    linha = linha + "<td class='ts-click' data-procod='" + object.procod + "'>" + object.procod + "</td>";
                    linha = linha + "<td class='ts-click ts-text' data-procod='" + object.procod + "'>" + object.pronom + "</td>";
                    linha = linha + "<td class='ts-click ts-value' data-procod='" + object.procod + "'>" + object.precoVenda + "</td>";
                    linha = linha + "<td class='ts-click ts-value' data-procod='" + object.procod + "'>" + object.precoProm + "</td>";
                    linha = linha + "</tr>";
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
    $("#buscar").click(function() {
            buscar($("#buscaProduto").val(), pagina);
        })

    document.addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            buscar($("#buscaProduto").val(), pagina);
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

    function VoltarComCarrinho() {
        var cartData = JSON.stringify(cart);
        window.location.href = 'pedido.php?precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
    }

    $(document).on('click', '.ts-click', function () {
        var procod = $(this).attr("data-procod");
        var cartData = JSON.stringify(cart);
        window.location.href = 'produtodet.php?procod=' + procod + '&precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
    });

   
</script>


<!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>
</html>

