

<?php

class CalculadoraMensal
{
    public const FATOR_CONVERSAO = 60 / 52.5;

    // Converte uma string de tempo HHH:MM em total de minutos.
    private function tempoParaMinutos(string $tempo_str): int
    {
        $partes = explode(':', $tempo_str);
        $horas = isset($partes[0]) ? intval($partes[0]) : 0;
        $minutos = isset($partes[1]) ? intval($partes[1]) : 0;
        return ($horas * 60) + $minutos;
    }

    private function minutosParaTempo(float $total_minutos): string
    {
        $minutos_arredondados = round($total_minutos);
        $horas = floor($minutos_arredondados / 60);
        $minutos = $minutos_arredondados % 60;
        return sprintf('%02d:%02d', $horas, $minutos);
    }
    
    public function calcular(string $totalHorasStr): array
    {
        $minutosTrabalhados = $this->tempoParaMinutos($totalHorasStr);

        if ($minutosTrabalhados <= 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'O total de horas deve ser maior que zero.'
            ];
        }

        $minutosComputados = $minutosTrabalhados * self::FATOR_CONVERSAO;
        $acrescimoMinutos = $minutosComputados - $minutosTrabalhados;
        
        return [
            'sucesso' => true,
            'total_informado' => $this->minutosParaTempo($minutosTrabalhados),
            'total_computado' => $this->minutosParaTempo($minutosComputados),
            'acrescimo' => $this->minutosParaTempo($acrescimoMinutos)
        ];
    }
}