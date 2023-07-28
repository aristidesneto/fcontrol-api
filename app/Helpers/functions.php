<?php

use Aristides\Helpers\Helpers;

function month_extension($value) {
    return Helpers::transformMonth($value->format('m'));
}