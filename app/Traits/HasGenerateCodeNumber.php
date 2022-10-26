<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasGenerateCodeNumber
{

    public function __generateNumber($id, $init = 'ENF', $length = 7)
    {
        return $init . '-' . Carbon::now()->format('Ymd') . sprintf("%0" . $length . "d", $id);
    }
}
