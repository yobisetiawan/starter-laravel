<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasDBSafe
{
    public $thMessage;
    public $thData;

    public function DBSafe($func)
    {
        try {
            DB::beginTransaction();

            $data = $func();

            DB::commit();
            return $data;
        } catch (\Throwable $th) {

            DB::rollBack();
            return $this->__errorDBSafe($th);
        }
    }

    public function __errorDBSafe($th)
    {
        if (!empty($this->thMessage)) {
            return $this->thMessage;
        }

        throw $th;
    }
}
