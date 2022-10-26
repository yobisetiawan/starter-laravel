<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBaseOwner;
use App\Traits\HasBaseTable;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasBaseOwner, HasBaseTable, SoftDeletes;

    const SLUG_FILE_PROFILE = 'file-profile';

    protected $table = 'files';

    protected $guarded = ['id', 'uuid'];

    public $timestamps = true;

    protected $casts = [
        'data' => 'array',
    ];

    protected $publicRelations = [
        'refable',
    ];

    public function refable()
    {
        return $this->morphTo(__FUNCTION__, 'ref_type', 'ref_id');
    }

    public static function prepareInsert($relation)
    {
        return [
            'ref_id' => $relation->id,
            'ref_type' => $relation->getMorphClass(),
            'ref_table' => $relation->getTable(),
        ];
    }
}
