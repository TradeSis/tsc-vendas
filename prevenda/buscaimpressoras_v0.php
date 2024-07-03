<?php
    
    header('Content-Type: application/json; charset=utf-8');

    $impressoras = array();
    
    $impressora = array("text" => "Qualidade02", "value" => "Qualidade02");
    array_push($impressoras,$impressora);
    
    $impressora = array("text" => "wms-moveis-zeb", "value" => "wms-moveis-zeb");
    array_push($impressoras,$impressora);

    $json = array("impressoras" => $impressoras);

    echo json_encode($json);

?>