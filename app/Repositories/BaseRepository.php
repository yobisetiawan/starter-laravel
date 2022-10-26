<?php

namespace App\Repositories;

use App\Traits\HasDBSafe;
use Illuminate\Support\Facades\DB;

class BaseRepository
{
    use HasDBSafe;
}
