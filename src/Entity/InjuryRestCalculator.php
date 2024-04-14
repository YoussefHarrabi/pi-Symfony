<?php
namespace App\Entity;

class InjuryRestCalculator
{
    private static array $restDaysMap = [
        'Fracture' => ['Low' => 7, 'Medium' => 14, 'High' => 21],
        'Sprain' => ['Low' => 3, 'Medium' => 7, 'High' => 14],
        'Burn' => ['Low' => 5, 'Medium' => 10, 'High' => 14],
        'Cut' => ['Low' => 3, 'Medium' => 5, 'High' => 7],
        'Bruise' => ['Low' => 2, 'Medium' => 3, 'High' => 5],
        'Concussion' => ['Low' => 7, 'Medium' => 14, 'High' => 21],
        'Whiplash' => ['Low' => 10, 'Medium' => 14, 'High' => 21],
        'Laceration' => ['Low' => 3, 'Medium' => 5, 'High' => 7],
        'Abrasions' => ['Low' => 2, 'Medium' => 3, 'High' => 5],
        'Internal bleeding' => ['Low' => 10, 'Medium' => 14, 'High' => 21],
    ];

    public static function calculateRestDays(string $injuryType, string $severity): int
    {
        $severityRestDays = self::$restDaysMap[$injuryType] ?? [];
        return $severityRestDays[$severity] ?? 0;
    }
}
