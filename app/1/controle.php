<?php
// helio 22022023 - incluido crediario/cliente e crediario/contrato
// helio 17022023 - incluido consultaMargemDesconto - é fake
// helio 03022023 - adaptacao de produto para produtos
// helio 03022023 - seguros

//echo "metodo=".$metodo."\n";
//echo "funcao=".$funcao."\n";
//echo "parametro=".$parametro."\n";

if ($metodo == "GET") {
  
  switch ($funcao) {
    case "produtos":
      include 'produtos.php';
      break;

    case "consultaMargemDesconto":
      include 'consultaMargemDesconto.php';
      break;

    case "cupomcashback":
      include 'cupomcashbackcliente.php';
      break;

    case "consultabonuscliente":
      include 'consultabonuscliente.php';
      break;

    case "consultaprodutodisp":
      include 'consultaprodutodisp.php';
      break;

    case "token":
      include 'token.php';
      break;

    case "prevenda":
      include 'prevenda.php';
      break;

    case "prevenprod":
      include 'prevenprod.php';
      break;

    case "fincotacluster":
      include 'fincotacluster.php';
    break;

    case "fincotacllib":
      include 'fincotacllib.php';
    break;

    case "fincotaclplan":
      include 'fincotaclplan.php';
      break;

    case "fincotaetb":
      include 'fincotaetb.php';
      break;

    case "fincotasuplib":
      include 'fincotasuplib.php';
      break;

    case "fincotasup":
      include 'fincotasup.php';
      break;
    
    case "supervisor":
      include 'supervisor.php';
      break;

    case "filialsupervisor":
      include 'filialsupervisor.php';
      break;

    case "zoomsupervisor":
      include 'zoomsupervisor.php';
      break;
      
    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}

if ($metodo == "PUT") {
  switch ($funcao) {

    case "token":
      include 'token_inserir.php';
      break;

    case "prevenda":
      include 'prevenda_inserir.php';
      break;

    case "prevenprod":
      include 'prevenprod_salvar.php';
      break;

    case "fincotacluster":
      include 'fincotacluster_inserir.php';
      break;
    
    case "fincotacllib":
      include 'fincotacllib_inserir.php';
      break;

    case "fincotaclplan":
      include 'fincotaclplan_inserir.php';
      break;

    case "fincotasuplib":
      include 'fincotasuplib_inserir.php';
      break;

    case "supervisor":
      include 'supervisor_inserir.php';
      break;

    case "fincotaclcarga":
      include 'fincotaclcarga.php';
      break;

    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}

if ($metodo == "POST") {
  if ($funcao == "token" && $parametro == "ativar") {
    $funcao = "token/ativar";
    $parametro = null;
  }
  if ($funcao == "token" && $parametro == "verifica") {
    $funcao = "token/verifica";
    $parametro = null;
  }

  switch ($funcao) {

    case "token/ativar":
      include 'token_ativar.php';
      break;

    case "token/verifica":
      include 'token_verifica.php';
      break;

    case "prevenda":
      include 'prevenda_finaliza.php';
      break;

    case "fincotacluster":
      include 'fincotacluster_alterar.php';
      break;

    case "fincotacllib":
      include 'fincotacllib_alterar.php';
      break;

    case "fincotaetb":
      include 'fincotaetb_alterar.php';
      break;

    case "fincotasuplib":
      include 'fincotasuplib_alterar.php';
      break;

    case "supervisor":
      include 'supervisor_alterar.php';
      break;

    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}

if ($metodo == "DELETE") {
  switch ($funcao) {

    case "token":
      include 'token_excluir.php';
      break;

    case "fincotacllib":
      include 'fincotacllib_excluir.php';
      break;

    case "fincotaclplan":
      include 'fincotaclplan_excluir.php';
      break;

    case "fincotasuplib":
      include 'fincotasuplib_excluir.php';
      break;

    case "supervisor":
      include 'supervisor_excluir.php';
      break;
  

    default:
      $jsonSaida = json_decode(
        json_encode(
          array(
            "status" => "400",
            "retorno" => "Aplicacao " . $aplicacao . " Versao " . $versao . " Funcao " . $funcao . " Invalida" . " Metodo " . $metodo . " Invalido "
          )
        ),
        TRUE
      );
      break;
  }
}