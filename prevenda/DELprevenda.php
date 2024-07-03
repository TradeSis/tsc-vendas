<?php
// lucas 120320204 id884 bootstrap local - alterado head
// helio 03022023 - ajustes iniciais

include_once "../header.php";

?>


<!DOCTYPE html>
    <html lang="pt-BR">
    <head>

        <link rel="stylesheet" type="text/css" href="../css/etiqueta_normal_styles.css"/>
        <?php include_once ROOT . "/vendor/head_css.php"; ?>
             
      <style rel="stylesheet" type="text/css">
       .estilo1{ background-color:#2FB12B; border: 0px solid;  }  
       .rowfull {
            height: 80vh;
                      
        }
        .my-custom-scrollbar {
            position: relative;
            height: 350px;
            overflow: auto;
            }
        .table-wrapper-scroll-y {
        display: block;
        }
      </style>
    </head>
<body>

<div class="container-fluid">

<div class="row  estilo1">
      
        <div class="col-md-12 rowfull estilo1"> 
            <div class="row estilo1">

                <div class="col-4 estilo1">
                    <div class="row">Produto</div>
                    <div class="row">
                        <div class="col estilo1">
                            <input type="text" class="enter" value="" id="procod"/>
                            <i class='fa fa-search' id='btpesquisa' style='font-size: 25px;'></i>
                            <span id='pronom'></span>
                        </div>
                    </div>
                </div>
                <div class="col-2 estilo1">
                    <div class="row">Quantidade</div>
                    <div class="row">
                        <input type="number" class="enter" value="1" id="inputqtt"/>
                    </div>
                </div>
                <div class="col-2  estilo1 ">
                    <div class="row">Preço</div>
                    <div class="row">
                        <input type="text" id="precoVenda" disabled="true"/>
                    </div>
                </div>
                <div class="col-2  estilo1 ">
                    <div class="row">Promocional</div>
                    <div class="row">
                        <input type="text" Value="" id="precoProm" disabled="true"/>
                    </div>
                </div>
                <div class="col-2 estilo1 ">
                    <div class="row"></div>
                    <div class="row">
                        <input type="button" value="Registrar" onclick='registrar()' class='botao col-sm' id='btregistrar'/>
                    </div>
                </div>

            </div>

            <div class="table-wrapper-scroll-y my-custom-scrollbar">

                <table id="tabela" class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Produto    </th>
                        <th>Nome       </th>
                        <th>Quantidade</th>
                        <th>Preço Normal </th>
                        <th>Promocional</th>
                        <th>Total      </th>                        
                        <th><button id='btlimpatabela' title='Deletar todos os registros'><i class='fa fa-times'></button></th>
                    </tr>
                </thead>
                <tbody id='corpodatabela'>
                </tbody>
                </table>
            </div>

            <div class="row estilo1" id='partedebaixo'>

                    <input type="button" value="Salvar" onclick="salvar()" class='col-sm botao' id='btsalvar'/>

                    

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