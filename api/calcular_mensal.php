<?php
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/src/CalculadoraMensal.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

$total_horas = $_POST['total_horas'] ?? null;

if (empty($total_horas)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Total de horas é obrigatório.']);
    exit;
}

try {
    $calculadora = new CalculadoraMensal();
    $resultado = $calculadora->calcular($total_horas);
    
    if ($resultado['sucesso']) {
        $fator = round(CalculadoraMensal::FATOR_CONVERSAO, 5);
        $resultado['detalhe_calculo'] = sprintf(
            '%s (Horas Informadas) &times; %s (Fator 60&divide;52.5) = %s (Noturnas + NotRed)',
            $resultado['total_informado'],
            $fator,
            $resultado['total_computado']
        );
    }

    echo json_encode($resultado);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao processar o cálculo: ' . $e->getMessage()]);
}