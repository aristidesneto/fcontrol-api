<?php

use Aristides\Helpers\Helpers;
use Carbon\Carbon;

function month_extension($value) {
    $month = Carbon::createFromFormat('Y-m-d', $value)->format('m');
    
    return Helpers::transformMonth($month);
}