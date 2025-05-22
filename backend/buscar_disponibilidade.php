<?php
function buscarDatasDisponiveis($funcionario_id) {
    $dias_disponiveis = [];

    // Apenas gera os próximos 14 dias corridos
    for ($i = 0; $i < 14; $i++) {
        $data = date('Y-m-d', strtotime("+$i day"));
        $dias_disponiveis[] = $data;
    }

    return $dias_disponiveis;
}
?>