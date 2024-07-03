<?php
include_once '../header.php';

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>

  <div class="container-fluid">

    <!-- Header -->
    <div class="text-center">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 mt-4">
          <p class="text-lead text">Criar nova Pré-Venda</p>
        </div>
        <div class="container">
          <a class="brand">
            <img src="<?php echo URLROOT ?>/img/lebes.png" width="100px">  
          </a>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container align-items-center justify-content-center" id="body">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card shadow">
            <form role="form" id="clienteForm" action="../database/prevenda.php?operacao=inserir" method="post">
              <div class="card-body">
                <div class="col">
                  <label>Loja</label>
                  <input class="form-control ts-input my-1"  value="<?php echo isset($_COOKIE['codigoFilial']) ? $_COOKIE['codigoFilial'] : '' ?>"  
                    placeholder="Codigo Loja" type="text" id="codigoFilial" name="codigoFilial" required>
                  <label>CPF Cliente</label>
                  <input class="form-control ts-input my-1" placeholder="CPF" type="text" id="cpfCnpj" name="cpfCnpj">
                  <input class="form-control ts-input" type="text" id="clicod" name="clicod" hidden>
                  <label>Codigo Vendedor</label>
                  <input class="form-control ts-input my-1" placeholder="cod func" type="text" id="vencod" name="vencod" required>
                </div>
                <div class="text-center mt-2">
                  <button type="submit" class="btn btn-success">Criar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- footer -->
  <div class="container">
    <div class="copyright text-center">
      <p style="font-size: smaller;">Desenvolvido por TradeSis</p>
    </div>
  </div>

  
  <!--------- alerta --------->
  <div class="modal fade" id="confirma" tabindex="-1" aria-labelledby="confirmaLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          <div class="container text-center">
            <h3>CONFIRMA PRÉ-VENDA SEM CLIENTE?</h3>
              <button type="button" class="btn btn-danger" id="naoButton">NAO</button>
              <button type="button" class="btn btn-success" id="simButton">SIM</button>
          </div>
        </div>
      </div>
    </div>
  </div>



  </div>

  <!-- LOCAL PARA COLOCAR OS JS -->

  <?php include_once ROOT . "/vendor/footer_js.php"; ?>

  <script>
    $(document).ready(function() {
      var timer;
      $("input[name='cpfCnpj']").on("input", function () {
        clearTimeout(timer);  

        timer = setTimeout(function() {
          var codigoFilial = $("input[name='codigoFilial']").val();
          var cpfCnpj = $("input[name='cpfCnpj']").val();
          if (cpfCnpj.length === 11) {
            verificaCPF(codigoFilial, cpfCnpj);
          }
        }, 3000); 
      });

      function verificaCPF(codigoFilial, cpfCnpj) {
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '../database/cliente.php?operacao=busca',
          data: {
            codigoFilial: codigoFilial,
            cpfCnpj: cpfCnpj
          },
          success: function (data) {
            if (data.status == 404) {
              alert('Cliente nao cadastrado')
            } else {
              $("input[name='clicod']").val(data.clien);
            } 
          }
        });
      }

    });
    
    $("#clienteForm").on("submit", function(event) {
        var cpfCnpj = $("input[name='cpfCnpj']").val();
        if (!cpfCnpj) {
            event.preventDefault();
            $("#confirma").modal("show");
        }
    });

    $("#simButton").on("click", function() {
        $("#confirma").modal("hide");
        $("#clienteForm").off("submit").submit();
    });

    $("#naoButton").on("click", function() {
        $("#confirma").modal("hide");
    });

    document.addEventListener("DOMContentLoaded", function() {
      var input = document.getElementById('cpfCnpj');
      var placeholder = '00000000000';

      input.addEventListener('input', function(event) {
        var value = this.value.replace(/\D/g, '');
        var newValue = '';
        var j = value.length - 1;
        for (var i = placeholder.length - 1; i >= 0; i--) {
          if (placeholder[i] === '0' && value[j]) {
            newValue = value[j--] + newValue;
          } else {
            newValue = placeholder[i] + newValue;
          }
        }
        this.value = newValue;
      });

      input.addEventListener('keydown', function(event) {
        if (event.key === 'Backspace') {
          var currentValue = this.value.replace(/\D/g, '');
          if (currentValue.length > 0) {
            currentValue = currentValue.slice(0, -1);
            var newValue = '';
            var j = currentValue.length - 1;
            for (var i = placeholder.length - 1; i >= 0; i--) {
              if (placeholder[i] === '0' && currentValue[j]) {
                newValue = currentValue[j--] + newValue;
              } else {
                newValue = placeholder[i] + newValue;
              }
            }
            this.value = newValue;
          }
          event.preventDefault();
        }
      });
    });
    
  </script>
  <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>