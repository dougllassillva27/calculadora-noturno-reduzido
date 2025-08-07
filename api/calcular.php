<?php
header('Content-Type: application/json');

require_once dirname(__DIR__) . '/src/CalculadoraJornadaNoturna.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

$inicio_jornada = $_POST['inicio_jornada'] ?? null;
$fim_jornada = $_POST['fim_jornada'] ?? null;
$periodo_inicio = $_POST['periodo_inicio'] ?? '22:00';
$periodo_fim = $_POST['periodo_fim'] ?? '05:00';

if (empty($inicio_jornada) || empty($fim_jornada)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Datas de início e fim da jornada são obrigatórias.']);
    exit;
}

try {
    if (new DateTimeImmutable($inicio_jornada) >= new DateTimeImmutable($fim_jornada)) {
         throw new Exception('A data/hora de fim da jornada deve ser posterior à de início.');
    }
    
    $calculadora = new CalculadoraJornadaNoturna($inicio_jornada, $fim_jornada, $periodo_inicio, $periodo_fim);
    $resultado = $calculadora->calcular();

    if ($resultado['minutos_noturnos_trabalhados'] > 0) {
        $fator = round(CalculadoraJornadaNoturna::FATOR_CONVERSAO, 5);
        $resultado['detalhe_calculo'] = sprintf(
            '%s (Horas Noturnas) &times; %s (Fator 60&divide;52.5) = %s (Noturnas + NotRed)',
            $resultado['noturno_trabalhado'],
            $fator,
            $resultado['noturno_computado']
        );
    } else {
        $resultado['detalhe_calculo'] = "Nenhum minuto noturno foi trabalhado nesta jornada.";
    }
    
    unset($resultado['minutos_noturnos_trabalhados']);
    echo json_encode(['sucesso' => true, 'dados' => $resultado]);

} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao processar as datas: ' . $e->getMessage()]);
}