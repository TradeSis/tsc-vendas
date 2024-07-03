<?php
// lucas 120320204 id884 bootstrap local - alterado head
// helio 03022023 - ajustes iniciais

include_once '../header.php';


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
            <div class="col-md-8 card p-0">
                <div class="card-header">
                    <h3>Produto</h3>
                </div>
                <div class="container">
                    <form action="produtodisp.php?parametros" method="POST">
                        <div class="row">
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Produto</label>
                                        <div class="col">
                                            <input type="text" class="enter" value="" id="procod" />
                                            <i class='bi bi-search' id='btpesquisa'></i>
                                            <span id='pronom'></span>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <label>Quantidade</label>
                                        <input type="number" class="form-control ts-input" value="1" id="inputqtt" />
                                    </div>
                                    <div class="col-2">
                                        <label>Preço</label>
                                        <input type="text" class="form-control ts-input" id="precoVenda" disabled="true" />
                                    </div>
                                    <div class="col-2">
                                        <label>Promocional</label>
                                        <input type="text" class="form-control ts-input" value="" id="precoProm" disabled="true" />
                                    </div>
                                    <div class="col-sm-2 mt-3" style="text-align:right">
                                        <input type="button" value="Registrar" onclick='registrar()' class='btn btn btn-success' id='btregistrar' />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="table mt-4 ts-divTabela ts-tableFiltros text-center ts-noScroll">
                                <table id="tabela" class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Prod</th>
                                            <th>Nome</th>
                                            <th>Qnt</th>
                                            <th>Preço</th>
                                            <th>Promo</th>
                                            <th>Total</th>
                                            <th><button id='btlimpatabela' title='Deletar todos os registros'><i class='bi bi-x-circle'></button></th>
                                        </tr>
                                    </thead>
                                    <tbody id='corpodatabela'>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <input type="button" value="Salvar" onclick="salvar()" class='btn btn btn-success' id='btsalvar' />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script type="text/javascript" src="prevenda.js"></script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>