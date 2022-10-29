<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Http\Modules\BaseCrud;
use App\Http\Resources\V1\Profile\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseCrud
{
    public $model = User::class;
    public $resource = UserResource::class;


    public function index(Request $req)
    {
        $user = Auth::user();
        return $this->show($user->id);
    }

    public function deleteUser()
    {
        $user = Auth::user();
        return $this->destroy($user->id);
    }

    public function __beforeDestroy()
    {
        $this->row->update([
            'data' => ['before_deleted' => $this->row->toArray()],
            'email' => null,
            'phone' => null,
            'is_active' => false
        ]);
    }
}
