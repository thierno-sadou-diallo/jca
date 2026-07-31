<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentCalendar
{
    /**
     * @param  Collection<int, object>  $slots
     * @return array<int, array<string, mixed>>
     */
    public static function months(Collection $slots, int $monthCount = 2): array
    {
        $slotsByDate = $slots->groupBy(fn (object $slot): string => Carbon::parse($slot->starts_at)->toDateString());
        $months = [];
        $start = now()->startOfMonth();

        for ($index = 0; $index < $monthCount; $index++) {
            $month = $start->copy()->addMonths($index);
            $firstDay = $month->copy()->startOfMonth();
            $lastDay = $month->copy()->endOfMonth();
            $days = [];

            for ($blank = 1; $blank < $firstDay->isoWeekday(); $blank++) {
                $days[] = ['blank' => true];
            }

            for ($day = $firstDay->copy(); $day->lte($lastDay); $day->addDay()) {
                $date = $day->toDateString();
                $daySlots = $slotsByDate->get($date, collect())
                    ->sortBy('starts_at')
                    ->values()
                    ->map(fn (object $slot): array => [
                        'id' => $slot->id,
                        'time' => Carbon::parse($slot->starts_at)->format('H:i'),
                        'ends' => Carbon::parse($slot->ends_at)->format('H:i'),
                        'status' => $slot->status,
                    ]);

                $days[] = [
                    'blank' => false,
                    'date' => $date,
                    'number' => $day->day,
                    'isPast' => $day->isBefore(now()->startOfDay()),
                    'isToday' => $day->isToday(),
                    'isWeekend' => in_array($day->isoWeekday(), [6, 7], true),
                    'slots' => $daySlots,
                ];
            }

            $months[] = [
                'key' => $month->format('Y-m'),
                'label' => ucfirst($month->translatedFormat('F Y')),
                'days' => $days,
            ];
        }

        return $months;
    }
}
