<?php
include_once '../header.php';
include_once '../database/prevenda.php';

$procod = $_GET['procod'];; 
$detproduto = buscarProduto($procod);

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
            <!-- MENSAGENS/ALERTAS -->
            <BR>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 card p-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-10 col-9">
                            <h3>Detalhes Produto</h3>
                        </div>
                        <div class="col-sm-2 col-3">
                            <a href="#" onclick="VoltarComCarrinho()" role="button" class="btn btn btn-primary">Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="container mt-2 mb-2">
                    <div class="row">
                        <div class="col">
                            <label>Codigo</label>
                            <input id="procod" type="text" class="form-control ts-input" value="<?php echo $detproduto['procod'] ?>" readonly>
                            <label>Preco</label>
                            <input id="precoVenda" type="text" class="form-control ts-input ts-value" value="<?php echo $detproduto['precoVenda'] ?>" readonly>
                        </div>
                        <div class="col">
                            <label>Nome</label>
                            <input id="pronom" type="text" class="form-control ts-input" value="<?php echo $detproduto['pronom'] ?>" readonly>
                            <label>Promo</label>
                            <input id="precoProm" type="text" class="form-control ts-input ts-value" value="<?php echo $detproduto['precoProm'] ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="container mt-2 mb-2">
                    <div class="row">
                        <div class="col-sm-4 col-6">
                        </div>
                            <div class="col-sm-8 col-6" style="text-align:right">
                                <button data-test="btn-subtract" class="btn btn-sm btn-secondary"><i class="bi bi-plus-square"></i></button>
                                <span data-test="quantidade">1</span>
                                <button data-test="btn-sum" class="btn btn-sm btn-secondary"><i class="bi bi-plus-square"></i></button>
                            <button id="btn-adicionar" class="btn btn-sm btn-success">Adicionar <span class="total"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        <?php if(isset($_GET['cart']) && $_GET['cart'] !== "[]") {?>
            var cart = <?php echo json_encode($produtos); ?>;
        <?php 
        } else {?>
            var cart = [];
        <?php } ?>

        $(document).ready(function() {
            var quantidade = 1;
            var precoProm = parseFloat($('#precoProm').val());
            updateTotal();

            $('[data-test="btn-subtract"]').on('click', function() {
                if (quantidade > 1) {
                    quantidade--;
                    updateQuantidade();
                    updateTotal();
                }
            });

            $('[data-test="btn-sum"]').on('click', function() {
                quantidade++;
                updateQuantidade();
                updateTotal();
            });

            function updateQuantidade() {
                $('[data-test="quantidade"]').text(quantidade);
            }

            function updateTotal() {
                var total = quantidade * precoProm;
                $('.total').text(total);
            }
        });

        function VoltarComCarrinho() {
            var cartData = JSON.stringify(cart);
            window.location.href = 'buscarprodu.php?precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
        }

        document.getElementById('btn-adicionar').addEventListener('click', function() {
            var procod = $('#procod').val();
            var pronom = $('#pronom').val();
            var precoVenda = $('#precoVenda').val();
            var precoProm = $('#precoProm').val();
            var quantidade = parseInt($('[data-test="quantidade"]').text());
            var total = parseFloat($('.total').text());

            var existingProductIndex = cart.findIndex(function(product) {
                return parseInt(product.procod) === parseInt(procod);
            });

            if (existingProductIndex !== -1) {
                cart[existingProductIndex].quantidade += quantidade;
                cart[existingProductIndex].total += total;
            } else {
                var product = {
                    procod: procod,
                    pronom: pronom,
                    quantidade: quantidade,
                    total: total,
                    precoVenda: precoVenda,
                    precoProm: precoProm,
                    total: total
                };
                cart.push(product);
            }

            var totalCart = 0;
            for (var i = 0; i < cart.length; i++) {
                totalCart += cart[i].total;
            }

            var cartData = JSON.stringify(cart);
            window.location.href = 'pedido.php?precod=' + <?php echo $precod; ?> + '&cart=' + encodeURIComponent(cartData);
        });
    </script><script>
        <?php if(isset($_GET['cart']) && $_GET['cart'] !== "[]") {?>
            var cart = <?php echo json_encode($produtos); ?>;
        <?php 
        } else {?>
            var cart = [];
        <?php } ?>

        $(document).ready(function() {
            var quantidade = 1;
            var precoVenda = parseFloat($('#precoVenda').val());
            var precoProm = parseFloat($('#precoProm').val());
            updateTotal();

            $('[data-test="btn-subtract"]').on('click', function() {
                if (quantidade > 1) {
                    quantidade--;
                    updateQuantidade();
                    updateTotal();
                }
            });

            $('[data-test="btn-sum"]').on('click', function() {
                quantidade++;
                updateQuantidade();
                updateTotal();
            });

            function updateQuantidade() {
                $('[data-test="quantidade"]').text(quantidade);
            }

            function updateTotal() {
                var total = precoProm === 0 ? quantidade * precoVenda : quantidade * precoProm;
                $('.total').text(total.toFixed(2)); 
            }
        });

        function VoltarComCarrinho() {
            var cartData = JSON.stringify(cart);
            window.location.href = 'buscarprodu.php?precod=<?php echo $precod; ?>&cart=' + encodeURIComponent(cartData);
        }

        document.getElementById('btn-adicionar').addEventListener('click', function() {
            var procod = $('#procod').val();
            var pronom = $('#pronom').val();
            var precoVenda = $('#precoVenda').val();
            var precoProm = $('#precoProm').val();
            var quantidade = parseInt($('[data-test="quantidade"]').text());
            var total = parseFloat($('.total').text());

            if (precoProm === "0" || precoProm === 0) {
                total = quantidade * precoVenda; 
            }

            var existingProductIndex = cart.findIndex(function(product) {
                return parseInt(product.procod) === parseInt(procod);
            });

            if (existingProductIndex !== -1) {
                cart[existingProductIndex].quantidade += quantidade;
                cart[existingProductIndex].total += total;
            } else {
                var product = {
                    procod: procod,
                    pronom: pronom,
                    precoVenda: precoVenda,
                    precoProm: precoProm,
                    quantidade: quantidade,
                    total: total
                };
                cart.push(product);
            }

            var totalCart = 0;
            for (var i = 0; i < cart.length; i++) {
                totalCart += cart[i].total;
            }

            var cartData = JSON.stringify(cart);
            window.location.href = 'pedido.php?precod=<?php echo $precod; ?>&cart=' + encodeURIComponent(cartData);
        });
    </script>
</body>

</html>
