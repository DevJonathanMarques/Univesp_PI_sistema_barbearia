<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../funcoes_agendamento.php';

final class FuncoesAgendamentoTest extends TestCase
{
    public function testBuildDateTimeCreatesValidDateTime(): void
    {
        $dt = buildDateTime('2025-10-28', '14:30');
        $this->assertInstanceOf(DateTime::class, $dt);
        $this->assertEquals('2025-10-28 14:30:00', $dt->format('Y-m-d H:i:s'));
    }

    public function testCalculateEndAddsDurationCorrectly(): void
    {
        $inicio = new DateTime('2025-10-28 10:00:00');
        $fim = calculateEnd($inicio, 90);
        $this->assertEquals('2025-10-28 11:30:00', $fim->format('Y-m-d H:i:s'));
    }

    public function testWeekdayNamePtReturnsPortugueseDay(): void
    {
        $this->assertEquals('segunda', weekdayNamePt('2025-10-27')); // Monday
        $this->assertEquals('domingo', weekdayNamePt('2025-11-02')); // Sunday
    }

    public function testIsDayOffReturnsTrueIfDayInFolgaList(): void
    {
        $this->assertTrue(isDayOff('segunda', 'domingo,segunda'));
        $this->assertFalse(isDayOff('terça', 'domingo,segunda'));
    }

    public function testIsWithinScheduleReturnsTrueIfInside(): void
    {
        $inicio = new DateTime('2025-10-28 10:00:00');
        $fim = new DateTime('2025-10-28 11:00:00');
        $horaInicio = new DateTime('2025-10-28 09:00:00');
        $horaFim = new DateTime('2025-10-28 18:00:00');

        $this->assertTrue(isWithinSchedule($inicio, $fim, $horaInicio, $horaFim));
    }

    public function testIsWithinScheduleReturnsFalseIfOutside(): void
    {
        $inicio = new DateTime('2025-10-28 08:00:00');
        $fim = new DateTime('2025-10-28 09:30:00');
        $horaInicio = new DateTime('2025-10-28 09:00:00');
        $horaFim = new DateTime('2025-10-28 18:00:00');

        $this->assertFalse(isWithinSchedule($inicio, $fim, $horaInicio, $horaFim));
    }

    public function testIntervalsOverlapDetectsOverlap(): void
    {
        $a1 = new DateTime('2025-10-28 10:00:00');
        $a2 = new DateTime('2025-10-28 11:00:00');
        $b1 = new DateTime('2025-10-28 10:30:00');
        $b2 = new DateTime('2025-10-28 11:30:00');

        $this->assertTrue(intervalsOverlap($a1, $a2, $b1, $b2));
    }

    public function testIntervalsOverlapReturnsFalseIfNoOverlap(): void
    {
        $a1 = new DateTime('2025-10-28 08:00:00');
        $a2 = new DateTime('2025-10-28 09:00:00');
        $b1 = new DateTime('2025-10-28 10:00:00');
        $b2 = new DateTime('2025-10-28 11:00:00');

        $this->assertFalse(intervalsOverlap($a1, $a2, $b1, $b2));
    }

    public function testFindConflictingUnavailableDetectsConflict(): void
    {
        $start = new DateTime('2025-10-28 10:00:00');
        $end = new DateTime('2025-10-28 11:00:00');
        $unavail = [
            ['hora' => '10:30', 'duracao' => 60],
            ['hora' => '14:00', 'duracao' => 60],
        ];

        $conflict = findConflictingUnavailable($unavail, $start, $end, '2025-10-28');
        $this->assertNotNull($conflict);
        $this->assertEquals('10:30', $conflict['hora']);
    }

    public function testFindConflictingUnavailableReturnsNullIfNoConflict(): void
    {
        $start = new DateTime('2025-10-28 10:00:00');
        $end = new DateTime('2025-10-28 11:00:00');
        $unavail = [
            ['hora' => '12:00', 'duracao' => 60],
            ['hora' => '14:00', 'duracao' => 60],
        ];

        $conflict = findConflictingUnavailable($unavail, $start, $end, '2025-10-28');
        $this->assertNull($conflict);
    }

    public function testFindConflictingAppointmentsDetectsOverlap(): void
    {
        $existing = [
            ['data_agendamento' => '2025-10-28 10:00:00', 'duracao' => 60],
            ['data_agendamento' => '2025-10-28 13:00:00', 'duracao' => 30],
        ];

        $novoInicio = new DateTime('2025-10-28 10:30:00');
        $novoFim = new DateTime('2025-10-28 11:30:00');

        $conflict = findConflictingAppointments($existing, $novoInicio, $novoFim);
        $this->assertNotNull($conflict);
        $this->assertEquals('2025-10-28 10:00:00', $conflict['data_agendamento']);
    }

    public function testFindConflictingAppointmentsReturnsNullIfNoOverlap(): void
    {
        $existing = [
            ['data_agendamento' => '2025-10-28 10:00:00', 'duracao' => 60],
            ['data_agendamento' => '2025-10-28 13:00:00', 'duracao' => 30],
        ];

        $novoInicio = new DateTime('2025-10-28 11:30:00');
        $novoFim = new DateTime('2025-10-28 12:00:00');

        $conflict = findConflictingAppointments($existing, $novoInicio, $novoFim);
        $this->assertNull($conflict);
    }
}