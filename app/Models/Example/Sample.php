<?php

namespace App\Models\Example;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBaseOwner;
use App\Traits\HasBaseTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sample extends Model
{
    use HasBaseOwner, HasBaseTable, SoftDeletes;

    protected $table = 'samples';

    protected $guarded = ['id', 'uuid'];


}
