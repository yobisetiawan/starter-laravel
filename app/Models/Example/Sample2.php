<?php

namespace App\Models\Example;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBaseOwner;
use App\Traits\HasBaseTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sample2 extends Model
{
    use HasBaseOwner, HasBaseTable, SoftDeletes;

    protected $table = 'samples2';

    protected $guarded = ['id', 'uuid'];

    protected $cast = [
        'data' => 'array',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }
}
