<?php
declare(strict_types=1);

// funcoes_agendamento.php
// Funções puras para isolar a lógica do salvar_agendamento.php
// Não fazem echo/exit nem acessam o banco. Usam objetos DateTime.

if (!function_exists('buildDateTime')) {
    /**
     * Constrói um DateTime a partir de data e hora (strings).
     *
     * @param string $date  Formato 'Y-m-d' (ou outro parseável por DateTime).
     * @param string $time  Formato 'H:i' (ou outro parseável por DateTime).
     * @return DateTime
     * @throws Exception
     */
    function buildDateTime(string $date, string $time): DateTime
    {
        // Concatena e cria DateTime; lança Exception se inválido.
        return new DateTime(trim($date) . ' ' . trim($time));
    }
}

if (!function_exists('calculateEnd')) {
    /**
     * Calcula o fim de um agendamento dado o início e duração em minutos.
     *
     * @param DateTime $start
     * @param int $durationMinutes
     * @return DateTime
     * @throws Exception
     */
    function calculateEnd(DateTime $start, int $durationMinutes): DateTime
    {
        $end = clone $start;
        if ($durationMinutes > 0) {
            $end->add(new DateInterval("PT{$durationMinutes}M"));
        }
        return $end;
    }
}

if (!function_exists('formatDateTimeSQL')) {
    /**
     * Formata DateTime para string SQL 'Y-m-d H:i:s'
     *
     * @param DateTime $dt
     * @return string
     */
    function formatDateTimeSQL(DateTime $dt): string
    {
        return $dt->format('Y-m-d H:i:s');
    }
}

if (!function_exists('weekdayNamePt')) {
    /**
     * Retorna o nome do dia da semana em português (sem acento)
     * a partir de uma data (string ou DateTime).
     *
     * @param string|DateTime $date
     * @return string Ex.: 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'
     * @throws Exception
     */
    function weekdayNamePt($date): string
    {
        if ($date instanceof DateTime) {
            $ts = $date->format('Y-m-d');
        } else {
            $ts = (string) $date;
        }

        $english = date('l', strtotime($ts));

        $map = [
            'Monday'    => 'segunda',
            'Tuesday'   => 'terca',
            'Wednesday' => 'quarta',
            'Thursday'  => 'quinta',
            'Friday'    => 'sexta',
            'Saturday'  => 'sabado',
            'Sunday'    => 'domingo',
        ];

        return $map[$english] ?? '';
    }
}

if (!function_exists('isDayOff')) {
    /**
     * Verifica se um dia (em pt) está dentro de uma lista CSV de dias de folga.
     *
     * @param string $diaPt         Ex.: 'segunda'
     * @param string $diasFolgaCsv  Ex.: 'domingo,segunda'
     * @return bool
     */
    function isDayOff(string $diaPt, string $diasFolgaCsv): bool
    {
        $dias = array_map('trim', explode(',', $diasFolgaCsv));
        // Filtra strings vazias
        $dias = array_values(array_filter($dias, fn($v) => $v !== ''));
        return in_array($diaPt, $dias, true);
    }
}

if (!function_exists('isWithinSchedule')) {
    /**
     * Verifica se o período [$start, $end] está totalmente contido no expediente [$hourStart, $hourEnd].
     *
     * @param DateTime $start
     * @param DateTime $end
     * @param DateTime $hourStart  Hora de início do expediente (mesma data que $start).
     * @param DateTime $hourEnd    Hora de fim do expediente (mesma data que $start).
     * @return bool True se start >= hourStart e end <= hourEnd
     */
    function isWithinSchedule(DateTime $start, DateTime $end, DateTime $hourStart, DateTime $hourEnd): bool
    {
        return ($start >= $hourStart) && ($end <= $hourEnd);
    }
}

if (!function_exists('intervalsOverlap')) {
    /**
     * Verifica se dois intervalos [startA, endA) e [startB, endB) se sobrepõem.
     *
     * Regra: há overlap se startA < endB && endA > startB
     *
     * @param DateTime $startA
     * @param DateTime $endA
     * @param DateTime $startB
     * @param DateTime $endB
     * @return bool
     */
    function intervalsOverlap(DateTime $startA, DateTime $endA, DateTime $startB, DateTime $endB): bool
    {
        return ($startA < $endB) && ($endA > $startB);
    }
}

if (!function_exists('findConflictingUnavailable')) {
    /**
     * Verifica se um agendamento [$start, $end] conflita com um conjunto de horários indisponíveis.
     *
     * $unavailabilities é um array de entradas; cada entrada deve ter ao menos:
     *   - 'hora' => string (formato 'H:i' ou 'H:i:s')
     *   - 'duracao' => int (opcional, em minutos). Se não fornecido, assume 60 minutos.
     *
     * Retorna a primeira entrada conflitante (array) ou null se não houver conflito.
     *
     * @param array $unavailabilities
     * @param DateTime $start
     * @param DateTime $end
     * @param string $dateString Data em 'Y-m-d' correspondente ao agendamento (usada para construir DateTime)
     * @return array|null
     * @throws Exception
     */
    function findConflictingUnavailable(array $unavailabilities, DateTime $start, DateTime $end, string $dateString): ?array
    {
        foreach ($unavailabilities as $entry) {
            if (!isset($entry['hora'])) {
                continue;
            }
            $hora = $entry['hora'];
            $duracao = isset($entry['duracao']) ? (int)$entry['duracao'] : 60;

            $inicioInd = buildDateTime($dateString, $hora);
            $fimInd = calculateEnd($inicioInd, $duracao);

            if (intervalsOverlap($start, $end, $inicioInd, $fimInd)) {
                return $entry;
            }
        }
        return null;
    }
}

if (!function_exists('findConflictingAppointments')) {
    /**
     * Dado um conjunto de agendamentos existentes, verifica se há conflito com um novo agendamento.
     *
     * $existingAppointments: array de entradas com:
     *   - 'data_agendamento' => string 'Y-m-d H:i:s' (início)
     *   - 'duracao' => int (minutos)
     *
     * Retorna a primeira entrada conflitante (array) ou null se não houver conflito.
     *
     * @param array $existingAppointments
     * @param DateTime $start
     * @param DateTime $end
     * @return array|null
     * @throws Exception
     */
    function findConflictingAppointments(array $existingAppointments, DateTime $start, DateTime $end): ?array
    {
        foreach ($existingAppointments as $appt) {
            if (!isset($appt['data_agendamento'])) {
                continue;
            }
            $inicioExist = new DateTime($appt['data_agendamento']);
            $duracao = isset($appt['duracao']) ? (int)$appt['duracao'] : 0;
            $fimExist = $duracao > 0 ? calculateEnd($inicioExist, $duracao) : $inicioExist;

            if (intervalsOverlap($start, $end, $inicioExist, $fimExist)) {
                return $appt;
            }
        }
        return null;
    }
}