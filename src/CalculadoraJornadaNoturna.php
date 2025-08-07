<?php

class CalculadoraJornadaNoturna
{
    public const FATOR_CONVERSAO = 60 / 52.5;

    private DateTimeImmutable $inicioJornada;
    private DateTimeImmutable $fimJornada;
    private string $horaInicioNoturno;
    private string $horaFimNoturno;
    private int $duracaoPeriodoNoturnoHoras;

    public function __construct(string $inicioJornadaStr, string $fimJornadaStr, string $inicioNoturnoStr = '22:00', string $fimNoturnoStr = '05:00')
    {
        $this->inicioJornada = new DateTimeImmutable($inicioJornadaStr);
        $this->fimJornada = new DateTimeImmutable($fimJornadaStr);

        $this->horaInicioNoturno = $inicioNoturnoStr;
        $this->horaFimNoturno = $fimNoturnoStr;

        $inicio = new DateTimeImmutable($this->horaInicioNoturno);
        $fim = new DateTimeImmutable($this->horaFimNoturno);

        if ($fim <= $inicio) {
            $fim = $fim->modify('+1 day');
        }
        
        $diff = $inicio->diff($fim);
        $this->duracaoPeriodoNoturnoHoras = $diff->h + ($diff->days * 24);
    }

    public function calcular(): array
    {
        $minutosTrabalhadosTotal = $this->calcularDiferencaEmMinutos($this->inicioJornada, $this->fimJornada);
        $minutosNoturnosTrabalhados = $this->calcularMinutosNoturnos();

        if ($minutosNoturnosTrabalhados > 0) {
            $minutosNoturnosComputados = $minutosNoturnosTrabalhados * self::FATOR_CONVERSAO;
            $acrescimoMinutos = $minutosNoturnosComputados - $minutosNoturnosTrabalhados;
        } else {
            $minutosNoturnosComputados = 0;
            $acrescimoMinutos = 0;
        }

        return [
            'total_jornada' => $this->minutosParaTempo($minutosTrabalhadosTotal),
            'noturno_trabalhado' => $this->minutosParaTempo($minutosNoturnosTrabalhados),
            'noturno_computado' => $this->minutosParaTempo($minutosNoturnosComputados),
            'acrescimo' => $this->minutosParaTempo($acrescimoMinutos),
            'minutos_noturnos_trabalhados' => $minutosNoturnosTrabalhados,
        ];
    }

    private function calcularMinutosNoturnos(): int
    {
        $totalMinutosNoturnos = 0;
        $cursor = $this->inicioJornada;

        while ($cursor < $this->fimJornada) {
            $inicioPeriodoNoturno = $this->getInicioPeriodoNoturno($cursor);
            $fimPeriodoNoturno = $inicioPeriodoNoturno->modify('+' . $this->duracaoPeriodoNoturnoHoras . ' hours');

            $inicioIntersecao = max($this->inicioJornada, $inicioPeriodoNoturno);
            $fimIntersecao = min($this->fimJornada, $fimPeriodoNoturno);
            
            if ($inicioIntersecao < $fimIntersecao) {
                 $totalMinutosNoturnos += $this->calcularDiferencaEmMinutos($inicioIntersecao, $fimIntersecao);
            }
            
            $cursor = $fimPeriodoNoturno;
        }
        
        return $totalMinutosNoturnos;
    }

    private function getInicioPeriodoNoturno(DateTimeImmutable $dataReferencia): DateTimeImmutable
    {
        list($hFim, ) = explode(':', $this->horaFimNoturno);
        list($hInicio, $mInicio) = explode(':', $this->horaInicioNoturno);

        $horaReferencia = (int) $dataReferencia->format('H');

        if ($this->horaFimNoturno > $this->horaInicioNoturno) { // Período não cruza meia-noite
            return $dataReferencia->setTime((int)$hInicio, (int)$mInicio, 0);
        }

        // Período cruza meia-noite (padrão)
        if ($horaReferencia < (int)$hFim) {
            return $dataReferencia->modify('yesterday')->setTime((int)$hInicio, (int)$mInicio, 0);
        }
        return $dataReferencia->setTime((int)$hInicio, (int)$mInicio, 0);
    }
    
    private function calcularDiferencaEmMinutos(DateTimeImmutable $inicio, DateTimeImmutable $fim): int
    {
        $diferenca = $inicio->diff($fim);
        return ($diferenca->days * 24 * 60) + ($diferenca->h * 60) + $diferenca->i;
    }

    private function minutosParaTempo(float $total_minutos): string
    {
        $minutos_arredondados = round($total_minutos);
        $horas = floor($minutos_arredondados / 60);
        $minutos = $minutos_arredondados % 60;
        return sprintf('%02d:%02d', $horas, $minutos);
    }
}