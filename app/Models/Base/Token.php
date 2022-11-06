<?php

namespace App\Models\Base;

use App\Traits\HasBaseOwner;
use App\Traits\HasBaseTable;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasBaseTable, HasBaseOwner;

    const VERIFY_RESET_PASSWORD = 'VERIFY_RESET_PASSWORD';

    const VERIFY_EMAIL = 'VERIFY_EMAIL';

    const SLUG_FCM_TOKEN = 'fcm-token';

    protected $guarded = ['id', 'uuid'];

    protected $casts = [
        'active' => 'boolean',
    ];

}
