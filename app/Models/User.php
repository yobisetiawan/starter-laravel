<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Base\File;
use App\Traits\HasBaseOwner;
use App\Traits\HasBaseTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasBaseOwner, HasBaseTable;

    const UPLOAD_PATH_AVATAR ="users/avatars";
    const EMAIL_IMPLEMENTER = 'implementer@62teknologi.com';
    const EMAIL_SUPERADMIN = 'superadmin@msglowclinic.id';
    const PASSWORD_DEFAULT = 'Pass1234';
    const TIMEZONE_DEFAULT = "Asia/Jakarta";

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function avatar()
    {
        return $this->morphOne(File::class, 'ref', 'ref_type', 'ref_id')
            ->where('ref_table', $this->table)
            ->where('slug', File::SLUG_FILE_PROFILE);
    }
}
