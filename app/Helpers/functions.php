<?php

use Aristides\Helpers\Helpers;
use Carbon\Carbon;

if (! function_exists('month_extension')) {
    function month_extension($value)
    {
        return Helpers::transformMonth($value->format('m'));
    }
}

if (! function_exists('letter_avatar')) {
    function letter_avatar($name)
    {
        $words = explode(' ', trim($name));
        
        $initials = isset($words[1]) ? mb_substr($words[0], 0, 1, 'UTF-8') . mb_substr($words[1], 0, 1, 'UTF-8') : mb_substr($words[0], 0, 2, 'UTF-8');
        
        return strtoupper($initials);
    }
}

if (! function_exists('calculate_due_date')) {
    function calculate_due_date($due_date, $best_date, $current_date = null)
    {   
        $now = Carbon::now()->tz(auth()->user()->timezone);

        if ($current_date) {
            $now = Carbon::createFromFormat('Y-m-d', $current_date, auth()->user()->timezone);
        }
        
        $day = $now->format('d');
        $month = $now->format('m');

        if ($day >= $best_date) {
            $month++;
            if ($month > 12) {
                $month = 1;
                $year = $now->format('Y') + 1;
            }
        }
        
        $next_date = Carbon::now()->tz(auth()->user()->timezone);
        $next_date->setDate(isset($year) ? $year : $now->format('Y'), $month, $due_date);

        return $next_date->format('Y-m-d');        
    }
}
