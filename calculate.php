<?php

function calcularDataVencimento($diaFechamento, $diaVencimento) {
    $hoje = new DateTime();
    $hojeDia = 4; // $hoje->format('j');
    
    $mesAtual = 9; //$hoje->format('n');
    $mesAnterior = $mesAtual - 1;
    
    if ($hojeDia > $diaFechamento) {
        $mesAtual++;
        if ($mesAtual > 12) {
            $mesAtual = 1;
            $anoAtual = $hoje->format('Y') + 1;
        }
    }
    
    $proximoVencimento = new DateTime();
    $proximoVencimento->setDate(isset($anoAtual) ? $anoAtual : $hoje->format('Y'), $mesAtual, $diaVencimento);
    
    return $proximoVencimento->format('Y-m-d'); // Ajuste o formato conforme necessário
}

// Exemplo de uso
$diaFechamento = 3;
$diaVencimento = 10;
$dataVencimento = calcularDataVencimento($diaFechamento, $diaVencimento);
echo "A próxima data de vencimento será: $dataVencimento";