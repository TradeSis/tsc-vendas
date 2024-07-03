<?php
// gabriel 300323 11:24

include_once '../header.php';
include_once '../database/prevenda.php';

if(isset($_GET['precod'])) {
    //echo json_encode($_GET['precod']);
    $precod = $_GET['precod'];
}
$prevenda = buscarPrevenda($precod);

$produtosprev = buscarPrevenprod($precod);

if(isset($_GET['cart']) && $_GET['cart'] !== "[]") {
    $cartJSON = $_GET['cart'];
    $produtos = json_decode($cartJSON, true);
} else {
    if ($produtosprev && isset($produtosprev[0]['procod'])) {
        foreach ($produtosprev as $prodPrev) {
            $produtos[] = [
                'procod' => $prodPrev['procod'],
                'pronom' => $prodPrev['pronom'],
                'precoVenda' => $prodPrev['precoVenda'],
                'precoProm' => $prodPrev['precoProm'],
                'quantidade' => $prodPrev['quantidade'],
                'total' => $prodPrev['total']
            ];
        }
    } else {
        $cartJSON = "[]";
    }
}
$totalPedido = 0;

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
                        <h3>Pedido <?php echo $precod ?> Loja <?php echo $prevenda['etbcod'] ?></h3>
                    </div>
                </div>
                <div class="container my-2">
                    <div class="row">
                        <div class="col">
                            <label>Nome Cliente</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $prevenda['clinom'] ?>" readonly>
                            <label>Func</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $prevenda['vencod'] ?> - <?php echo $prevenda['funape'] ?>" readonly>
                            <label>clicod</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $prevenda['clicod'] ?>" readonly>
                        </div>
                        <div class="col">
                            <label>Cpf</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $prevenda['cpfCnpj'] ?>" readonly>
                            <label>dtinclu</label>
                            <input type="text" class="form-control ts-input" value="<?php echo date('d/m/Y', strtotime($prevenda['dtinclu']))?>" readonly>
                            <label>etbcod</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $prevenda['etbcod'] ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4 col-4 mt-3">
                            <input type="text" placeholder="Inserir ID Produto" class="form-control ts-input" id="inserirProduto" name="inserirProduto">
                        </div>
                        <div class="col-sm-4 col-3 mt-3">
                        </div>
                        <div class="col-sm-4 col-5 mt-2" style="text-align:right">
                            <a href="#" id="btn-buscar" class="btn btn-primary">Buscar Produto</a>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="table mt-2 ts-tableFiltros text-center">
                        <table class="table table-sm table-hover ts-tablecenter">
                            <thead class="ts-headertabelafixo">
                                <tr class="ts-headerTabelaLinhaCima">
                                    <th>Prod</th>
                                    <th>Nome</th>
                                    <th>PreÃ§o</th>
                                    <th>Promo</th>
                                    <th>Qnt</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <?php
                            if(isset($produtos) && $produtos !== "[]") {
                            foreach ($produtos as $produto) {
                                $totalPedido += $produto['total'];
                                ?>
                                <tr>
                                    <td style="white-space: nowrap;">
                                        <?php echo $produto['procod'] ?>
                                    </td>
                                    <td class="ts-text"><?php echo $produto['pronom'] ?></td>
                                    <td class="ts-value"><?php echo number_format($produto['precoVenda'], 2, ',', '.') ?></td>
                                    <td class="ts-value"><?php echo number_format($produto['precoProm'], 2, ',', '.') ?></td>
                                    <td class="ts-value"><?php echo $produto['quantidade'] ?></td>
                                    <td class="ts-value"><?php echo number_format($produto['total'], 2, ',', '.') ?></td>
                                    <td style="white-space: nowrap;">
                                        <button type="button" class="btn btn-warning btn-sm" title="Alterar" id="alterarProduto"
                                        data-procod="<?php echo $produto['procod'] ?>" ><i class="bi bi-pencil-square"></i></button>
                                    </td>
                                </tr>
                            <?php } }  ?>

                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right">
                            <span>Total Pedido: R$<span><?php echo number_format($totalPedido, 2, ',', '.') ?></span></span>
                    </div>
                </div>
                <div class="container my-2">
                    <div style="text-align:right">
                        <button class="btn btn btn-success" type="button" href="#" id="btn-continuar">Fechar Pedido</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!--------- ALTERAR --------->
    <div class="modal fade bd-example-modal-md" id="alterarProdutoModal" tabindex="-1" aria-labelledby="alterarProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alterar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="form-alterarProduto">
                        <div class="row">
                            <div class="col">
                                <label>Codigo</label>
                                <input id="procod" type="text" class="form-control ts-input" readonly>
                                <label>Preco</label>
                                <input id="precoVenda" type="text" class="form-control ts-input ts-value" readonly>
                            </div>
                            <div class="col">
                                <label>Nome</label>
                                <input id="pronom" type="text" class="form-control ts-input" readonly>
                                <label>Promo</label>
                                <input id="precoProm" type="text" class="form-control ts-input ts-value" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div>
                        <button type="button" id="btn-subtract" class="btn btn-sm btn-secondary"><i class="bi bi-dash-square"></i></button>
                        <span id="quantidade" class="mx-2">1</span>
                        <button type="button" id="btn-sum" class="btn btn-sm btn-secondary"><i class="bi bi-plus-square"></i></button>
                    </div>
                    <button type="button" class="btn btn-success" id="btn-adicionar">Adicionar <span id="total"></span></button>
                    <button type="button" class="btn btn-danger" id="btn-excluir">Excluir</button>
                </div>
            </div>
        </div>
    </div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<script>
    var etbcod = 188;

    <?php if(isset($produtos) && $produtos !== "[]") {?>
        var cart = <?php echo json_encode($produtos); ?>;
    <?php 
    } else {?>
        var cart = [];
    <?php } ?>


    function VoltarComCarrinho() {
        var cartData = JSON.stringify(cart);
        window.location.href = 'prevenda.php?precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
    }
    
    function buscarProdu() {
        var cartData = JSON.stringify(cart);
        window.location.href = 'buscarprodu.php?precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
    }
    
    $('#btn-continuar').click(function() {
        $.ajax({
            url: "<?php echo URLROOT ?>/vendas/database/prevenda.php?operacao=finaliza",
            type: 'POST',
            dataType: 'json',
            data: {
                precod: <?php echo $prevenda['precod'] ?>,
                etbcod: <?php echo $prevenda['etbcod'] ?>
            },
            success: function (data) {
                if (data.status == 200) {
                    window.location.href = 'prevenda.php';
                } 
                else {
                    alert(data.descricaoStatus);
                } 
            },
        });
    });


    document.getElementById('btn-buscar').addEventListener('click', buscarProdu);

    
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 118) {
            buscarProdu();
        }
    });

    function enterProduto(event) {
        if (event.keyCode === 13) {
            var procod = document.getElementById('inserirProduto').value;
            if (!procod || procod.trim() === '') {
                alert("Digite o código do produto.");
                return;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: "<?php echo URLROOT ?>/vendas/database/prevenda.php?operacao=buscarProduto",
                data: {
                    procod: procod,
                    etbcod: etbcod
                },
                success: function (data) {
                    var existingProductIndex = cart.findIndex(function (product) {
                        return parseInt(product.procod) === parseInt(data.procod);
                    });

                    var precoProm = parseFloat(data.precoProm);
                    var precoVenda = parseFloat(data.precoVenda);

                    if (precoProm === 0 || isNaN(precoProm)) {
                        precoProm = precoVenda;
                    }

                    if (existingProductIndex !== -1) {
                        cart[existingProductIndex].quantidade += 1;
                        cart[existingProductIndex].total += precoProm;
                    } else {
                        var product = {
                            procod: data.procod,
                            pronom: data.pronom,
                            precoVenda: precoVenda,
                            precoProm: data.precoProm,
                            quantidade: 1,
                            total: precoProm
                        };
                        cart.push(product);
                    }

                    var produtoSalvo = existingProductIndex !== -1 ? cart[existingProductIndex] : product;

                    $.ajax({
                        url: "<?php echo URLROOT ?>/vendas/database/prevenda.php?operacao=salvar",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            precod: <?php echo $prevenda['precod']; ?>,
                            etbcod: <?php echo $prevenda['etbcod']; ?>,
                            procod: produtoSalvo.procod,
                            movpc: produtoSalvo.precoProm !== 0 ? produtoSalvo.precoProm : produtoSalvo.precoVenda,
                            movqtm: produtoSalvo.quantidade,
                            movtot: produtoSalvo.total,
                            precoori: produtoSalvo.precoVenda,
                            vencod: <?php echo $prevenda['vencod']; ?>
                        },
                        success: function (data) {
                            var cartData = JSON.stringify(cart);
                            window.location.href = 'pedido.php?precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
                        }
                    });
                }
            });
        }
    }


    document.getElementById('inserirProduto').addEventListener('keydown', enterProduto);

    $(document).on('click', '#alterarProduto', function() {
        var procod = $(this).attr("data-procod");

        ProdutoIndex = cart.findIndex(product => product.procod == procod);
        var produto = cart[ProdutoIndex];

        $('#procod').val(produto.procod);
        $('#pronom').val(produto.pronom);
        $('#precoVenda').val(produto.precoVenda);
        $('#precoProm').val(produto.precoProm);
        $('#quantidade').text(produto.quantidade);
        $('#total').text(produto.total.toFixed(2)); 
        $('#alterarProdutoModal').modal('show');
    });

    $('#btn-sum').click(function() {
        var quantidade = parseInt($('#quantidade').text());
        $('#quantidade').text(quantidade + 1);
        updateTotal();
    });

    $('#btn-subtract').click(function() {
        var quantidade = parseInt($('#quantidade').text());
        if (quantidade > 1) {
            $('#quantidade').text(quantidade - 1);
            updateTotal();
        }
    });

    function updateTotal() {
        var newquantidade = parseInt($('#quantidade').text());
        var precoProm = parseFloat($('#precoProm').val());
        var precoVenda = parseFloat($('#precoVenda').val());
        
        var preco = precoProm !== 0 ? precoProm : precoVenda;

        var total = newquantidade * preco;
        $('#total').text(total.toFixed(2));
    }

    $('#btn-adicionar').click(function() {
        var newquantidade = parseInt($('#quantidade').text());
        cart[ProdutoIndex].quantidade = newquantidade;
        cart[ProdutoIndex].total = newquantidade * parseFloat(cart[ProdutoIndex].precoProm || cart[ProdutoIndex].precoVenda);
        var cartData = JSON.stringify(cart);
        window.location.href = 'pedido.php?precod=<?php echo $precod; ?>&cart=' + encodeURIComponent(cartData);
    });

    $('#btn-excluir').click(function() {
        cart.splice(ProdutoIndex, 1);
        var cartData = JSON.stringify(cart);
        window.location.href = 'pedido.php?precod=<?php echo $precod; ?>&cart=' + encodeURIComponent(cartData);
    });

</script>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>
</html>